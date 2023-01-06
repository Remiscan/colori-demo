import { BracketFragments, BracketPairList } from 'bracket-pairs';
import Couleur from 'colori';



const black = new Couleur('black');
const staticFields = Object.getOwnPropertyDescriptors(Couleur); // Couleur class's static fields
const fields = Object.getOwnPropertyDescriptors(Object.getPrototypeOf(black)); // Couleur class's non-static fields

// Sort the different types of fields in the Couleur class
const coloriFields = {
  staticMethods: Object.fromEntries(
    Object.entries(staticFields).filter(([key, descriptor]) => (typeof Couleur[key] === 'function'))
  ),

  methods: Object.fromEntries(
    Object.entries(fields).filter(([key, descriptor]) => (typeof black[key] === 'function'))
  ),

  getters: Object.fromEntries(
    Object.entries(fields).filter(([key, descriptor]) => (typeof descriptor.get === 'function'))
  ),

  setters: Object.fromEntries(
    Object.entries(fields).filter(([key, descriptor]) => (typeof descriptor.set === 'function'))
  ),
};

// Regular expressions to detect applied Couleur methods in strings.
// They will be used on strings containing references to argument lists instead of arguments themselves,
// so we know there won't be any parentheses in these references: they are of shape ${f[id]}.
const regexps = {
  staticMethods: new RegExp(`^Couleur\\.(${Object.keys(coloriFields.staticMethods).join('|')})\\(([^\(\)]*?)\\)$`),
  methods: new RegExp(`^(.+?)\\.(${Object.keys(coloriFields.methods).join('|')})\\(([^\(\)]*?)\\)$`),
  getters: new RegExp(`^(.+?)\\.(${Object.keys(coloriFields.getters).join('|')})$`),
};



/** 
 * List of bracket-balanced argument strings 
 * built from a string that is supposed to be an arguments list,
 * i.e. of shape "arg1, arg2, arg3".
 */
export class ArgumentList {
  arguments = [];

  constructor(string, separator = ',') {
    let currentArg = '';

    // Checks if a potential argument is bracket-balanced.
    const isBalanced = arg => {
      const brackets = new BracketPairList(arg, '()[]{}');
      return brackets.balanced;
    };

    // For each character of the string, check if it's a comma that could be separating arguments.
    for (let k = 0; k < string.length; k++) {
      const char = string[k];

      // If the current character is a comma, check if the previous characters form a bracket-balanced string.
      if (char === separator) {
        // If the current potential argument is bracket-balanced, it's an actual argument.
        if (isBalanced(currentArg)) {
          currentArg = ArgumentList.parseArgument(currentArg);
          this.arguments.push(currentArg);
          currentArg = '';
          continue;
        }
      }

      // Build the current potential argument.
      currentArg = `${currentArg}${char}`;

      // If the string is over, stop and check the last argument.
      if (k === string.length - 1 && isBalanced(currentArg)) {
        currentArg = ArgumentList.parseArgument(currentArg);
        this.arguments.push(currentArg);
      }
    }
  }


  toString() {
    return this.arguments.map(arg => JSON.stringify(arg)).join(', ');
  }


  toStringArray() {
    return this.arguments.map(arg => JSON.stringify(arg));
  }


  /** Parse an argument string into its correct type. */
  static parseArgument(arg) {
    let currentArg = arg.trim();

    // Check if the argument is a Couleur, an array or an object
    let type;
    if (currentArg.startsWith('[') && currentArg.endsWith(']')) type = 'array';
    else if (currentArg.startsWith('{') && currentArg.endsWith('}')) type = 'object';
    else if (!isNaN(parseFloat(currentArg))) type = 'number';
    else type = 'string';

    // If the argument is an array or object, check its contents and return them as their own arguments list
    switch (type) {
      case 'array': {
        const contents = new ArgumentList(currentArg.slice(1, -1));
        currentArg = contents.arguments;
      } break;

      case 'object': {
        const contents = new ArgumentList(currentArg.slice(1, -1));
        currentArg = {};
        for (let j = 0; j < contents.arguments.length; j++) {
          const keyvalue = (new ArgumentList(contents.arguments[j], ':')).arguments;
          if (keyvalue.length !== 2) throw new Error(`Incorrect object syntax in ${contents[j]}`);
          const [key, value] = keyvalue;
          currentArg[key] = value;
        }
      } break;

      case 'number': {
        currentArg = parseFloat(currentArg);
      } break;

      case 'string': {
        const stringChars = `'"\``;
        for (const char of stringChars) {
          if (currentArg.startsWith(char) && currentArg.endsWith(char)) {
            currentArg = currentArg.slice(1, -1);
          }
        }
      } break;
    }

    return currentArg;
  }
}



/**
 * Takes a string that's javascript-like but with color expressions instead of variable names,
 * and replaces those expressions by actual javascript that creates the color.
 * For example :              "red.interpolate(blue, 5, oklch)"
 * will be transformed into:  "(new Couleur('red')).interpolate((new Couleur('blue')), 5, 'oklch')".
 * @param {string} string - The string to parse.
 * @returns {string} The properly transformed string.
 */
export function parseColorsInString(string) {
  const fragmentsObj = new BracketFragments(string, '()');
  const fragments = fragmentsObj.fragments;

  /**
   * Checks if a javascript-like argument is a color expression,
   * and returns that expression modified into correct JavaScript.
   * If it is not, return null.
   * @param {string} arg - The JSON-stringified argument to check.
   * @returns {string|null} The transformed argument.
   */
  const stringifyColorArg = arg => {
    let color;
    const parsedArg = JSON.parse(arg);
    try {
      eval(`color = ${parsedArg}`);
      if (color instanceof Couleur) return parsedArg;
      else throw new Error('Not a color');
    } catch (e) {
      try {
        const doubleParsedArg = parseColorsInString(parsedArg) ?? null;
        return doubleParsedArg !== parsedArg ? doubleParsedArg : null;
      } catch (e) {
        return null;
      }
    }
  }

  // For each fragment of the original string, check if it's an interesting form,
  // i.e. something like Couleur.method() or object.method() or object.getter
  for (let k = 0; k < fragments.length; k++) {
    // Insert previous fragments in the current fragment if it contains them
    const originalFragment = fragments[k];

    // Check if the whole fragment is a color expression
    let color;
    const modifiedFragment = fragmentsObj.insertInto(originalFragment);
    try {
      color = new Couleur(modifiedFragment);
    } catch (e) {}
    if (color) {
      // Replace the fragment by a valid JS expression that creates the color.
      fragments[k] = `(new Couleur('${modifiedFragment}'))`;
      continue;
    }

    // Test if the whole fragment is Couleur.{a static method}
    let match;
    match = originalFragment.match(regexps.staticMethods);
    if (match) {
      const methodName = fragmentsObj.insertInto(match[1]);
      const methodArgs = fragmentsObj.insertInto(match[2]);
      const parsedMethodArgs = (new ArgumentList(methodArgs)).toStringArray().map(arg => stringifyColorArg(arg) ?? arg);
      // Replace the fragment by a valid JS expression that applies the method.
      fragments[k] = `Couleur.${methodName}(${parsedMethodArgs.join(', ')})`;
      continue;
    }

    // Test if the whole fragment is couleurObject.{a method}
    match = originalFragment.match(regexps.methods);
    if (match) {
      const colorString = fragmentsObj.insertInto(match[1]);
      const methodName = fragmentsObj.insertInto(match[2]);
      const methodArgs = fragmentsObj.insertInto(match[3]);
      // Parse the method arguments, to determine if they need to be replaced by valid JS expressions.
      const parsedMethodArgs = (new ArgumentList(methodArgs)).toStringArray().map(arg => stringifyColorArg(arg) ?? arg);

      let color;
      try {
        color = new Couleur(colorString);
      } catch (e) {}
      if (color) {
        // If the colorString is a valid color, replace it by a valid JS expression that creates the color.
        fragments[k] = `(new Couleur('${colorString}')).${methodName}(${parsedMethodArgs.join(', ')})`;
        continue;
      } else {
        // If it's not a valid color, it should itself be an interesting form. Recursively parse it and insert the results.
        fragments[k] = `${parseColorsInString(colorString)}.${methodName}(${parsedMethodArgs.join(', ')})`;
        continue;
      }
    }

    // Test if the whole fragment is couleurObject.{a getter}
    match = originalFragment.match(regexps.getters);
    if (match) {
      const colorString = fragmentsObj.insertInto(match[1]);
      const getterName = fragmentsObj.insertInto(match[2]);
      let color;
      try {
        color = new Couleur(colorString);
      } catch (e) {}
      if (color) {
        // If the colorString is a valid color, replace it by a valid JS expression that creates the color.
        fragments[k] = `((new Couleur('${colorString}')).${getterName})`;
        continue;
      } else {
        // If it's not a valid color, it should itself be an interesting form. Recursively parse it and insert the results.
        fragments[k] = `(${parseColorsInString(colorString)}.${getterName})`;
        continue;
      }
    }
  }

  // Return the last modified fragment, which is the whole original string with every fragment
  // properly replaced by valid JS expressions if needed.
  return fragments[fragments.length - 1];
}



/**
 * Transforms a string in the simplified colori-demo syntax into valid JavaScript,
 * then evaluates it with colori.
 * @param {string} string - The string to transform.
 * @returns {any} The result obtained after applying colori to the transformed string.
 */
export function resolveInput(string) {
  const parsedString = parseColorsInString(string);
  let value;
  try {
    value = eval(parsedString);
  } catch (e) {}
  return value;
}
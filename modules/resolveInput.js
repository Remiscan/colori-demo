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
 * The index of a bracket in a string.
 * @typedef { { index: number, type: string } } BracketIndex
 */
/** 
 * A pair of brackets in a string.
 * @typedef { { start: BracketIndex, end: BracketIndex } } BracketPair 
 */

/** 
 * An array of bracket pairs at a given depth.
 * @typedef {BracketPair[]} BracketDepth 
 */

/** 
 * An array where:
 * - the keys are the current depth
 * - the value if an array of bracket pairs at that depth.
 * @typedef {BracketDepth[]} BracketData 
 */



/**
 * Analyzes a string and returns data about its bracket pairs.
 * The second element in the returned array is the remaining stack at the end of the function.
 * If stack.length === 0, the brackets are balanced (i.e. every open bracket is closed).
 * @param {string} string - The string to analyze.
 * @param {string} bracketTypes - Bracket characters to check
 * @returns {[BracketData, number[]]} The bracket pair data.
 */
export function bracketPairs(string, bracketTypes = '()[]{}') {
  const stack = [];
  const pairs = [];

  // For each character in the string, check if it's a bracket
  stringLoop: for (const [index, character] of Object.entries(string)) {
    // For each bracket type, check if the current character is it
    typeLoop: for (let charIndex = 0; charIndex < bracketTypes.length; charIndex++) {
      // If the current character is an opening bracket
      if (charIndex % 2 === 0 && character === bracketTypes[charIndex]) {
        const depth = stack.length;
        stack.push({ index: Number(index), type: bracketTypes[charIndex] });
        if (!pairs[depth]) pairs.push([]);
        continue stringLoop;
      }
      
      // If the current character is a closing bracket
      else if (charIndex % 1 === 0 && character === bracketTypes[charIndex]) {
        const lastOpen = stack.pop();

        // If no bracket was open before, ignore the current closing bracket
        if (typeof lastOpen === 'undefined') continue stringLoop;

        // If the current closing bracket corresponds to the previous opening bracket
        if (lastOpen.type === bracketTypes[charIndex - 1]) {
          const depth = stack.length;
          pairs[depth].push({
            start: lastOpen,
            end: { index: Number(index), type: bracketTypes[charIndex] },
          });
          continue stringLoop;
        } else {
          continue typeLoop;
        }
      }
    }
  }
  return [pairs, stack];
}



/**
 * Gets the ancestor bracket pair of a bracket pair, i.e. the pair containing it.
 * @param {BracketPair} pair - The pair whose ancestors we want.
 * @param {number} depth - The depth of the pair.
 * @param {BracketData} reversedDepths - The bracket data.
 * @returns {BracketPair|null} The ancestor bracket pair if there is one, null if not.
 */
export function getAncestorPair(pair, depth, reversedDepths) {
  const start = pair.start.index;
  const end = pair.end.index;

  // If we're already at the last depth level, there's no way to look for an ancestor deeper.
  if (depth + 1 >= reversedDepths.length) return null;

  for (const potentialAncestor of reversedDepths[depth + 1]) {
    const pStart = potentialAncestor.start.index;
    const pEnd = potentialAncestor.end.index;
    // If the potential ancestor contains the current pair, then it's the actual ancestor!
    if (pStart < start && pEnd > end) {
      return potentialAncestor;
    }
  }
  return null;
}



/**
 * Parses a string containing bracket pairs, and returns an array of ordered string fragments contained in those pairs.
 * The last element of the returned array is the full string.
 * @param {string} string - The string to parse.
 * @returns {string[]} An array of string fragments.
 */
export function parseStringWithBrackets(string, bracketTypes = undefined) {
  const [pairsOfBrackets, stack] = bracketPairs(string, bracketTypes);
  if (stack.length > 0) throw 'Unbalanced bracket pairs';

  const reversedDepths = [...pairsOfBrackets].reverse();
  let modifiedString = string;
  const stringFragments = [];

  let id = 0;

  //const logPairs = () => console.log([...reversedDepths].map(d => { return [...d].map(p => { return { start: {...(p.start)}, end: {...(p.end)} } })}));

  // For each depth level, extract the string fragments into an array,
  // and replace the fragment in the original string by ${f[id]} where id
  // is the key of the associated fragment in the fragments array.
  for (let depth = 0; depth < reversedDepths.length; depth++) {
    const pairs = reversedDepths[depth];

    // For each pair of brackets, extract the fragment that is between the brackets
    // and replace it in the original string.
    for (let k = 0; k < pairs.length; k++) {
      const pair = pairs[k];
      
      // Extract the fragment
      const fragment = modifiedString.slice(pair.start.index + 1, pair.end.index);
      stringFragments.push(fragment);

      // Replace it in the original string
      const replacement = `\${f[${id}]}`;
      id++;
      const prefix = modifiedString.slice(0, pair.start.index);
      const suffix = modifiedString.slice(pair.end.index + 1);
      modifiedString = `${prefix}${pair.start.type}${replacement}${pair.end.type}${suffix}`;
      
      // Compute the length difference between the fragment and its replacement,
      // and then shift all bracket pair indexes accordingly.
      const shiftBy = replacement.length - fragment.length;

      // Shift the end index of ancestor pairs
      let editedPair = pair;
      for (let subDepth = depth + 1; subDepth < reversedDepths.length; subDepth++) {
        const ancestor = getAncestorPair(editedPair, subDepth - 1, reversedDepths);
        if (!ancestor) break;
        ancestor.end.index += shiftBy;
        editedPair = ancestor;
      }

      // Shift the start and end indexes of following sibling pairs
      for (const p of pairs) {
        if (p.start.index <= pair.start.index) continue;
        p.start.index += shiftBy;
        p.end.index += shiftBy;
      }

      // Shift the end index of the current pair,
      // after shifting the ancestors and siblings so that they compare their old indexes.
      pair.end.index += shiftBy;
    }
  }

  return [...stringFragments, modifiedString];
}


/**
 * Takes a string that's supposed to be an arguments list "arg1, arg2, arg3...",
 * and returns an array of bracket-balanced arguments.
 * @param {string} string - The string of the list of arguments.
 * @returns {string[]} The array of bracket-balanced arguments.
 */
export function parseArgumentsList(string) {
  const args = [];
  let currentArg = '';

  // Checks if a potential argument is bracket-balanced.
  const isBalanced = arg => {
    const [pairs, stack] = bracketPairs(arg, '()[]{}');
    return stack.length === 0;
  };

  // For each character of the string, check if it's a comma that could be separating arguments.
  for (let k = 0; k < string.length; k++) {
    const char = string[k];

    // If the current character is a comma, check if the previous characters form a bracket-balanced string.
    if (char === ',') {
      // If the current potential argument is bracket-balanced, it's an actual argument.
      if (isBalanced(currentArg)) {
        args.push(currentArg.trim());
        currentArg = '';
        continue;
      }
    }

    // Build the current potential argument.
    currentArg = `${currentArg}${char}`;

    // If the string is over, stop and check the last argument.
    if (k === string.length - 1 && isBalanced(currentArg)) {
      args.push(currentArg.trim());
    }
  }

  return args;
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
  /**
   * Checks if an argument is an actual value (like a color or number).
   * If it is, return it as it is.
   * If it is not, return a string containing that argument.
   * @param {string} arg - The argument to check.
   * @returns {string} The transformed argument.
   */
  const stringifyArg = arg => {
    let value;
    try {
      eval(`value = ${arg}`);
    } catch (e) {
      return `"${arg}"`
    }
    return arg;
  };

  const fragments = parseStringWithBrackets(string, '()');
  //const fragments = [string];

  /**
   * Takes a string with fragment references (i.e. ${f[id]})
   * and replaces them with the actual fragments.
   * @param {string} string - The string with fragment references.
   * @returns {string} The string with actual fragments inserted.
   */
  const insertFragments = (string) => {
    let modifiedString = string;
    for (let id = 0; id < fragments.length; id++) {
      modifiedString = modifiedString.replace(`\${f[${id}]}`, fragments[id]);
    }
    return modifiedString;
  }

  // For each fragment of the original string, check if it's an interesting form,
  // i.e. something like Couleur.method() or object.method() or object.getter
  for (let k = 0; k < fragments.length; k++) {
    // Insert previous fragments in the current fragment if it contains them
    const originalFragment = fragments[k];

    // Check if the whole fragment is a color expression
    let color;
    const modifiedFragment = insertFragments(originalFragment);
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
      const methodName = insertFragments(match[1]);
      const methodArgs = insertFragments(match[2]);
      const parsedMethodArgs = parseArgumentsList(methodArgs).map(arg => {
        return stringifyArg(
          arg.trim()
        )
      });
      // Replace the fragment by a valid JS expression that applies the method.
      fragments[k] = `Couleur.${methodName}(${parsedMethodArgs.join(', ')})`;
      continue;
    }

    // Test if the whole fragment is couleurObject.{a method}
    match = originalFragment.match(regexps.methods);
    if (match) {
      const colorString = insertFragments(match[1]);
      const methodName = insertFragments(match[2]);
      const methodArgs = insertFragments(match[3]);
      // Parse the method arguments, to determine if they need to be replaced by valid JS expressions.
      const parsedMethodArgs = parseArgumentsList(methodArgs).map(arg => {
        return stringifyArg(
          arg.trim()
        )
      });

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
      const colorString = insertFragments(match[1]);
      const getterName = insertFragments(match[2]);
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
  return eval(parsedString);
}
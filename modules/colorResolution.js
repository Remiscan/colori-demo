import { default as Couleur, CSSFormats } from 'colori';



const RegExps = CSSFormats.RegExps;
// RegExp du séparateur entre arguments : virgule puis espace(s) optionnel(s)
const vSep = '\\,(?: +)?';
// RegExp des options d'une méthode
const vOpt = 'true|false|\\{(?:.+)?\\}';
// RegExp des formats
const vFormats = `rgb|hsl|hwb|lab|lch|oklab|oklch`;
// RegExp des propriétés
const vProp = Couleur.properties.join('|');

// Liste des méthodes supportées par le champ
const methodes = [
  {
    name: 'change',
    args: [
      new RegExp(`(${vProp})${vSep}(${RegExps.numberOrPercentage})${vSep}(${vOpt})`),
      new RegExp(`(${vProp})${vSep}(${RegExps.numberOrPercentage})`)
    ]
  }, {
    name: 'replace',
    args: [
      new RegExp(`(${vProp})${vSep}(${RegExps.numberOrPercentage})${vSep}(${vOpt})`),
      new RegExp(`(${vProp})${vSep}(${RegExps.numberOrPercentage})`)
    ]
  }, {
    name: 'scale',
    args: [
      new RegExp(`(${vProp})${vSep}(${RegExps.numberOrPercentage})${vSep}(${vOpt})`),
      new RegExp(`(${vProp})${vSep}(${RegExps.numberOrPercentage})`)
    ]
  }, {
    name: 'greyscale',
    args: null
  }, {
    name: 'grayscale',
    args: null
  }, {
    name: 'sepia',
    args: null
  }, {
    name: 'complement',
    args: null
  }, {
    name: 'negative',
    args: null
  }, {
    name: 'invert',
    args: null
  }, {
    name: 'blend',
    args: new RegExp(`^(.+)$`),
    argIsColor: [true]
  }, {
    name: 'unblend',
    args: new RegExp(`^(.+)$`),
    argIsColor: [true]
  }, {
    name: 'whatToBlend',
    args: [
      new RegExp(`^(.+)${vSep}(${RegExps.number})${vSep}(${RegExps.number})$`),
      new RegExp(`^(.+)${vSep}(${RegExps.number})$`),
      new RegExp(`^(.+)$`)
    ],
    argIsColor: [true, false, false]
  }, {
    name: 'contrast',
    args: [
      new RegExp(`^(.+)${vSep}([A-Za-z0-9]+?)$`),
      new RegExp(`^(.+)$`)
    ],
    argIsColor: [true]
  }, {
    name: 'bestColorScheme',
    args: [
      new RegExp(`^([A-Za-z]+?)$`),
      new RegExp('^$')
    ]
  }, {
    name: 'improveContrast',
    args: [
      new RegExp(`^(.+)${vSep}(${RegExps.number})${vSep}(${RegExps.number})$`),
      new RegExp(`^(.+)${vSep}(${RegExps.number})$`)
    ],
    argIsColor: [true, false, false]
  }, {
    name: 'distance',
    args: [
      new RegExp(`^(.+)${vSep}(.+)$`),
      new RegExp(`^(.+)$`)
    ],
    argIsColor: [true, false]
  }, {
    name: 'same',
    args: [
      new RegExp(`^(.+)${vSep}(${RegExps.number})$`),
      new RegExp(`^(.+)$`)
    ],
    argIsColor: [true, false]
  }, {
    name: 'gradient',
    args: [
      new RegExp(`^(.+)${vSep}(${RegExps.number})${vSep}(${vFormats})$`),
      new RegExp(`^(.+)${vSep}(${RegExps.number})$`),
      new RegExp(`^(.+)$`)
    ],
    argIsColor: [true, false, false]
  }, {
    name: 'valuesTo',
    args: new RegExp(`^(.+)$`),
    argIsColor: [false]
  }
];

const methodesSimples = methodes.filter(method => !(method.argIsColor || []).reduce((sum, a) => sum + a, 0));
const methodesRecursives = methodes.filter(method => !!(method.argIsColor || []).reduce((sum, a) => sum + a, 0));

// Expression régulières pour détecter les méthodes supportées et leurs arguments
const regexps = {
  simple: new RegExp(`(.+)\\.(${ methodesSimples.map(method => method.name).join('|') })\\((.+)?\\)$`),
  recursive: new RegExp(`^((?:[^\\(\\)]+(?:\\(.+\\))?)+)\\.(${ methodesRecursives.map(method => method.name).join('|') })\\((.+)\\)$`)
};



///////////////////////////////////////////////////
// Calcule une couleur à partir du string en entrée
export function resolveColor(input) {
  // premCouleur sera égale à la première couleur entrée en input (de forme premCouleur.resteDeLInput),
  // elle commence égale à input et sera réduite si nécessaire par la boucle suivante
  let premCouleur = input;
  const methodesAppliquees = [];

  let loop = 0;
  while (loop < 100) {
    loop++;

    // On vérifie si la valeur de l'input est de la forme couleur.methodeRecursive() ou couleur.methodeNonRecursive()
    const match = premCouleur.match(regexps.recursive) || premCouleur.match(regexps.simple);

    // Si la valeur de l'input ne vérifie couleur.methode() pour aucune methode de acceptedMethods,
    // on passe à l'étape suivante (vérifier si la valeur de l'input est une expression valide de couleur)
    if (match === null) break;

    // On détermine quelle méthode est appliquée à la couleur
    const k = methodesRecursives.findIndex(m => m.name == match[2]);
    const method = (k > -1) ? methodesRecursives[k]
                            : methodesSimples[methodesSimples.findIndex(m => m.name == match[2])];

    // On détermine les arguments passés à la méthode
    let args = [];
    for (const regex of [method.args].flat()) {
      const temp = (match[3] || '').match(regex);
      if (temp == null) continue;
      args = [...(temp || [])].slice(1);
      break;
    }

    // On ajoute cette méthode (et ses arguments) à la liste de méthodes appliquées à la couleur
    // (on recrée l'objet méthode plutôt que de le modifier, sinon on corrompt methodes)
    methodesAppliquees.push({
      name: method.name,
      args: args,
      argIsColor: method.argIsColor || Array(args.length).fill(false),
      resultIsValue: method.resultIsValue
    });

    // On récupère le reste de l'input pour le tester (boucle)
    premCouleur = match[1];
  }

  // Si la valeur restante de l'input est une expression valide de couleur, on pourra continuer.
  // Sinon, la valeur est invalide.
  try {
    try { premCouleur = new Couleur(premCouleur); }
    catch (error) { throw 'ignore'; }

    // On se prépare à appliquer les méthodes dans l'ordre à premCouleur
    methodesAppliquees.reverse();
    let couleur = premCouleur;
    // On boucle sur les méthodes pour les appliquer successivement à premCouleur
    for (const method of methodesAppliquees) {
      // On boucle sur les arguments de la méthode pour les résoudre si ce sont des couleurs
      for (const [i, arg] of method.args.entries()) {
        if (!!(method.argIsColor || [])[i]) {
          try           { method.args[i] = resolveColor(arg)[0]; }
          catch (error) { throw 'Couleur en argument invalide'; }
        }
      }

      // On applique la méthode (avec ses arguments résolus)
      couleur = Couleur.prototype[method.name]
                       .call(couleur, ...method.args.map(arg => arg === 'true' ? true : arg === 'false' ? false : arg));
      if (!couleur instanceof Couleur) break;
    }

    // On a notre résultat : la couleur après application de toutes les méthodes !!
    return [couleur, methodesAppliquees[methodesAppliquees.length - 1]?.name, premCouleur.rgb];
  }
  catch (error) {
    if (error != 'ignore') console.log(error);
    return null;
  }
}
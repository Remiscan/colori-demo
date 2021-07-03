// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import Couleur from '/colori/colori.js';
import { resolveColor } from './colorResolution.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



let entree;
let lastTry;



/////////////////////////////////////////////////////
// Update the interface with the newly detected color
export async function updateCouleur(couleur, delai = 100) {
  const thisTry = Date.now();
  lastTry = thisTry;

  await new Promise(resolve => setTimeout(resolve, delai));
  if (lastTry != thisTry) return;

  // Hide the numerical result by default
  const donnees = document.getElementById('donnees');
  donnees.classList.remove('valeur', 'gradient');
  
  try {
    entree = resolveColor(couleur);
    let methode, input;
    if (entree !== null && entree.length == 3) {
      [entree, methode, input] = entree;
    }

    if (entree instanceof Couleur) {
      colorInterface(entree);
      populateColorData(entree);
    } else if (entree != null) {
      const valeur = document.querySelector('.format.valeur code');

      // If the result is a number or a boolean, display it in the results
      if (typeof entree == 'number' || typeof entree == 'boolean') {
        valeur.innerHTML = entree;
        donnees.classList.add('valeur');
      }

      // If the result is an array of colors, display their gradient as the input background
      else if (Array.isArray(entree) && entree.length > 0 && entree.reduce((sum, e) => sum + (e instanceof Couleur), 0)) {
        if (methode == 'gradient') {
          const gradient = `linear-gradient(to right, ${entree.map(c => c.name || c.rgb).join(', ')})`;
          
          colorInterface(entree[0]);
          populateColorData(entree[0]);
          valeur.innerHTML = gradient;
          Prism.highlightElement(valeur);
          donnees.classList.add('valeur', 'gradient');

          document.querySelector('.format.gradient').style.setProperty('--gradient', gradient);

        } else if (methode == 'whatToBlend') {
          const gradient = `linear-gradient(to right, ${entree.map(c => c.name || c.rgb).join(', ')})`;
          let array = `[\n`;
          for (const c of entree) {
            array += `  ${c.name || c.rgb},\n`
          }
          array += `]`;
          
          colorInterface(entree[0]);
          populateColorData(entree[0]);
          valeur.innerHTML = array;
          Prism.highlightElement(valeur);
          donnees.classList.add('valeur', 'gradient', 'whatToBlend');

          document.querySelector('.format.gradient').style.setProperty('--bg', input);
          document.querySelector('.format.gradient').style.setProperty('--gradient', gradient);
        }
      }

      // If not any of these, display the results in the console
      else console.log(`${couleur} == ${entree}`);
    }
    return;
  }
  catch(error) {
    return console.error(error);
  }
}



///////////////////////
// Colors the interface
export function colorInterface(couleur = entree) {
  const element = document.documentElement;
  element.style.setProperty('--user-color', couleur.rgb);
  element.style.setProperty('--user-hue', Math.round(couleur.h * 360));
  element.style.setProperty('--user-saturation', Math.round(couleur.s * 100) + '%');

  let cssBoth, cssLight, cssDark;
  const meta = document.querySelector('meta[name=theme-color]');
  const colorPreview = (new Couleur('white')).blend(couleur);

  // Calculate colors that are the same for both light and dark themes
  const cieh = couleur.cieh * 360;
  both: {
    cssBoth = ``;
  }

  // Calculate colors for light theme
  light: {
    const ciec = Math.min(couleur.ciec, 60);
    const bodyColor = new Couleur(`lch(75% ${ciec} ${cieh})`);
    meta.dataset.light = bodyColor.hsl;
    const sectionColor = new Couleur(`lch(85% ${.6 * ciec} ${cieh})`);
    const codeColor = new Couleur(`lch(90% ${.3 * ciec} ${cieh})`);
    cssLight = `
      /* Background colors */
      --body-color: ${bodyColor.hsl};
      --section-color: ${sectionColor.hsl};
      --frame-color: ${codeColor.improveContrast(colorPreview, 2.5).hsl};
      --code-color: ${codeColor.hsl};
      --tab-hover-color: ${sectionColor.replace('a', .7).hsl};
      /* Text colors */
      --h1-color: ${(new Couleur(`lch(30% ${.6 * ciec} ${cieh})`)).hsl};
      --h3-color: ${(new Couleur(`lch(45% ${ciec} ${cieh})`)).hsl};
      --text-color: black;
      --link-color: ${(new Couleur(`lch(30% ${ciec} ${cieh})`)).hsl};
      --link-underline-color: ${(new Couleur(`lch(30% ${2 * ciec} ${cieh} / .5)`)).hsl};
      /* Input colors */
      --input-bg-color: ${(new Couleur(`lch(95% ${.3 * ciec} ${cieh})`)).hsl};
      --input-active-bg-color: ${(new Couleur(`lch(99% ${.1 * ciec} ${cieh})`)).hsl};
      --input-placeholder-color: ${(new Couleur(`lch(25% ${.5 * ciec} ${cieh} / .5)`)).hsl};
      /* Syntax coloring colors */
      --token-number: ${(new Couleur(`lch(50% 70 ${cieh - 90})`)).hsl};
      --token-string: ${(new Couleur(`lch(50% 70 ${cieh + 45})`)).hsl};
      --token-operator: ${(new Couleur(`lch(50% 70 ${cieh - 45})`)).hsl};
      --token-keyword: ${(new Couleur(`lch(50% 70 ${cieh + 135})`)).hsl};
      /* Button colors */
      --button-bg-color: ${(new Couleur(`lch(95% ${ciec} ${cieh} / .4)`)).hsl};
      --button-border-color: ${(new Couleur(`lch(45% ${ciec} ${cieh} / .6)`)).hsl};
      --button-hover-bg-color: ${(new Couleur(`lch(95% ${ciec} ${cieh} / .8)`)).hsl};
      --button-hover-border-color: ${(new Couleur(`lch(45% ${ciec} ${cieh})`)).hsl};
    `;
  }

  // Calculate colors for dark theme
  dark: {
    const ciec = Math.min(.3 * couleur.ciec, 10);
    const bodyColor = new Couleur(`lch(8% ${.6 * ciec} ${cieh})`);
    meta.dataset.dark = bodyColor.hsl;
    const sectionColor = new Couleur(`lch(20% ${ciec} ${cieh})`);
    const codeColor = bodyColor;
    cssDark = `
      /* Background colors */
      --body-color: ${bodyColor.hsl};
      --section-color: ${sectionColor.hsl};
      --frame-color: ${codeColor.improveContrast(colorPreview, 2.5).hsl};
      --code-color: ${codeColor.hsl};
      --tab-hover-color: ${sectionColor.replace('a', .7).hsl};
      /* Text colors */
      --h1-color: ${(new Couleur(`lch(80% ${ciec} ${cieh})`)).hsl};
      --h3-color: ${(new Couleur(`lch(70% ${1.7 * ciec} ${cieh})`)).hsl};
      --text-color: ${(new Couleur(`lch(90% ${.2 * ciec} ${cieh})`)).hsl};
      --link-color: ${(new Couleur(`lch(80% ${1.7 * ciec} ${cieh})`)).hsl};
      --link-underline-color: ${(new Couleur(`lch(80% ${2 * 1.7 * ciec} ${cieh} / .5)`)).hsl};
      /* Input colors */
      --input-bg-color: ${(new Couleur(`lch(30% ${1.5 * ciec} ${cieh})`)).hsl};
      --input-active-bg-color: ${(new Couleur(`lch(10% ${.6 * ciec} ${cieh})`)).hsl};
      --input-placeholder-color: ${(new Couleur(`lch(90% ${.5 * ciec} ${cieh} / .5)`)).hsl};
      /* Syntax coloring colors */
      --token-number: ${(new Couleur(`lch(80% 70 ${cieh - 90})`)).hsl};
      --token-string: ${(new Couleur(`lch(80% 70 ${cieh + 45})`)).hsl};
      --token-operator: ${(new Couleur(`lch(80% 70 ${cieh - 45})`)).hsl};
      --token-keyword: ${(new Couleur(`lch(80% 70 ${cieh + 135})`)).hsl};
      /* Button colors */
      --button-bg-color: ${(new Couleur(`lch(80% ${1.7 * ciec} ${cieh} / .1)`)).hsl};
      --button-border-color: ${(new Couleur(`lch(70% ${1.7 * ciec} ${cieh} / .5)`)).hsl};
      --button-hover-bg-color: ${(new Couleur(`lch(80% ${1.7 * ciec} ${cieh} / .2)`)).hsl};
      --button-hover-border-color: ${(new Couleur(`lch(70% ${1.7 * ciec} ${cieh})`)).hsl};
    `;
  }

  // Let's generate the stylesheet for the interface colors
  const style = document.getElementById('theme-variables');
  style.innerHTML = `
    :root {
      ${cssLight}
      ${cssBoth}
    }

    @media (prefers-color-scheme: dark) {
      :root {
        ${cssDark}
      }
    }

    :root[data-theme="light"] {
      ${cssLight}
    }

    :root[data-theme="dark"] {
      ${cssDark}
    }
  `;
}



//////////////////////////////////////////////////////
// Adds data about the selected color to the interface
export function populateColorData(couleur) {
  // Populates results in all formats
  for (const format of [...document.querySelectorAll('#donnees>[data-format]')]) {
    const code = format.querySelector('code');
    if (format.dataset.format == 'name') {
      if (couleur.name == null) {
        document.querySelector('.name').classList.remove('oui');
        code.innerHTML = '';
      } else {
        document.querySelector('.name').classList.add('oui');
        code.innerHTML = couleur.name;
      }
    } else {
      code.innerHTML = couleur[format.dataset.format];
    }
    Prism.highlightElement(code);
  }

  // Changes the input field placeholder text
  const champ = document.getElementById('entree');
  champ.placeholder = couleur.name || couleur.hex;

  // Changes the values of all input ranges
  for (const range of [...document.querySelectorAll('input[data-property]')]) {
    const prop = range.dataset.property;
    let coeff = range.max;
    switch (prop) {
      case 'ciea':
      case 'cieb':
      case 'ciec':
        coeff = 1;
        break;
    }
    range.value = Math.round(coeff * couleur[prop]);
  }
}
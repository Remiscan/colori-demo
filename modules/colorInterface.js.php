// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import Couleur from '/colori/colori.js';
import { resolveColor } from './colorResolution.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



/////////////////////////////////////////////////////
// Update the interface with the newly detected color
let lastTry;
export async function updateInterface(couleur, source = 'text', delai = 20) {
  const thisTry = Date.now();
  lastTry = thisTry;

  await new Promise(resolve => setTimeout(resolve, delai));
  if (lastTry != thisTry) return;

  // Hide non-format results by default
  const donnees = document.querySelector('.donnees');
  donnees.removeAttribute('data-type');
  
  let entree;
  try {
    // Resolve the user input (by detecting the typed colors or functions)
    entree = resolveColor(couleur);
    let methode, input;
    if (entree !== null && entree.length == 3) {
      [entree, methode, input] = entree;
    }

    // If the user input resolves to a color, adapt the interface
    if (entree instanceof Couleur) {
      computeCSS(entree);
      populateColorData(entree, source);
    }
    
    // If the user input resolves to another type of data...
    else if (entree != null) {
      const valeur = document.querySelector('.format.valeur code');

      // If the result is a number or a boolean, display it in the results
      if (typeof entree == 'number' || typeof entree == 'boolean') {
        valeur.innerHTML = entree;
        donnees.dataset.type = 'valeur';
      }

      // If the result is an array of colors, display their gradient as the input background
      else if (Array.isArray(entree) && entree.length > 0 && entree.reduce((sum, e) => sum + (e instanceof Couleur), 0)) {
        const gradient = `linear-gradient(to right, ${entree.map(c => c.name || c.rgb).join(', ')})`;
        computeCSS(entree[0]);
        populateColorData(entree[0]);

        if (methode == 'gradient') {
          valeur.innerHTML = gradient;
          donnees.dataset.type = 'valeur,gradient';
        }
        else if (methode == 'whatToBlend') {
          let array = `[\n`;
          for (const c of entree) {
            array += `  ${c.name || c.rgb},\n`
          }
          array += `]`;

          valeur.innerHTML = array;
          donnees.dataset.type = 'valeur,gradient,whatToBlend';
          document.querySelector('.format.gradient').style.setProperty('--bg', input);
        }

        Prism.highlightElement(valeur);
        document.querySelector('.format.gradient').style.setProperty('--gradient', gradient);
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
function computeCSS(couleur) {
  const element = document.documentElement;
  element.style.setProperty('--user-color', couleur.rgb);
  element.style.setProperty('--user-hue', Math.round(couleur.h));
  element.style.setProperty('--user-saturation', Math.round(couleur.s * 100) + '%');

  let cssBoth, cssLight, cssDark;
  const meta = document.querySelector('meta[name=theme-color]');
  const colorPreview = (new Couleur('white')).blend(couleur);

  // Calculate colors that are the same for both light and dark themes
  const cieh = couleur.cieh;
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
      --button-bg-color: ${(new Couleur(`lch(90% ${.6 * ciec} ${cieh})`)).hsl};
      --button-active-bg-color: ${(new Couleur(`lch(98% ${.3 * ciec} ${cieh})`)).hsl};
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
      --button-bg-color: ${(new Couleur(`lch(25% ${.75 * ciec} ${cieh} / .25)`)).hsl};
      --button-active-bg-color: ${(new Couleur(`lch(35% ${1.5 * ciec} ${cieh})`)).hsl};
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
export function populateColorData(couleur, source = 'text') {
  // Populates results in all formats
  const name = couleur.name;
  for (const format of [...document.querySelectorAll('.donnees>[data-format]')]) {
    const code = format.querySelector('code');
    if (format.dataset.format == 'name') {
      if (name == null) {
        format.setAttribute('hidden', 'true');
        code.innerHTML = '';
      } else {
        format.removeAttribute('hidden');
        code.innerHTML = name;
      }
    } else {
      code.innerHTML = couleur[format.dataset.format];
    }
    Prism.highlightElement(code);
  }

  // Changes the input field placeholder text
  const champ = document.getElementById('entree');
  champ.placeholder = name || couleur.hex;

  // Updates all input ranges
  updateSliders(couleur, source);
}



///////////////////////////////////////////////////////////
// Updates the color selection sliders to fit a given color
export function updateSliders(_couleur, source = 'text') {
  let couleur = Couleur.check(_couleur);

  const ranges = [...document.querySelectorAll('input[type="range"][data-property]')];
  const getRange = prop => ranges.find(r => r.dataset.property == prop);
  for (const range of ranges) {
    const prop = range.dataset.property;
    
    // Update value
    let coeff = range.max;
    switch (prop) {
      case 'ciea':
      case 'cieb':
      case 'ciec':
      case 'h':
      case 'cieh':
        coeff = 1;
        break;
    }

    const newValue = Math.max(range.min, Math.min(Math.round(coeff * couleur[prop]), range.max));
    let oldValue;
    switch (prop) {
      case 'r': case 'g': case 'b':                         oldValue = 255 * range.value; break;
      case 's': case 'l': case 'w': case 'bk': case 'ciel': oldValue = `${range.value}%`; break;
      default:                                              oldValue = range.value;
    }

    // Only update a slider value if its old value doesn't make the same color
    if (!Couleur.same(couleur, couleur.replace(prop, oldValue))) {
      // Update slider value
      range.value = newValue;

      // Update corresponding numeric input value
      const numericInput = document.querySelector(`input[type="number"][data-property="${prop}"]`);
      if (numericInput) numericInput.style.setProperty('--pos', (range.value - range.min) / (range.max - range.min));
      numericInput.value = range.value;
    }

    // Update background gradient
    let gradient = [];
    let start, end;
    let h, s, l, w, bk, ciel, ciea, cieb, ciec, cieh;
    switch (prop) {
      case 'r':
        start = `rgb(0, ${255 * couleur.g}, ${255 * couleur.b}, ${couleur.a})`;
        end = `rgb(255, ${255 * couleur.g}, ${255 * couleur.b}, ${couleur.a})`;
        gradient = [start, end];
        break;
      case 'g':
        start = `rgb(${255 * couleur.r}, 0, ${255 * couleur.b}, ${couleur.a})`;
        end = `rgb(${255 * couleur.r}, 255, ${255 * couleur.b}, ${couleur.a})`;
        gradient = [start, end];
        break;
      case 'b':
        start = `rgb(${255 * couleur.r}, ${255 * couleur.g}, 0, ${couleur.a})`;
        end = `rgb(${255 * couleur.r}, ${255 * couleur.g}, 255, ${couleur.a})`;
        gradient = [start, end];
        break;
      case 'a':
        start = `rgb(${255 * couleur.r}, ${255 * couleur.g}, ${255 * couleur.b}, 0)`;
        end = `rgb(${255 * couleur.r}, ${255 * couleur.g}, ${255 * couleur.b}, 1)`;
        gradient = [start, end];
        break;
      case 'h':
        s = getRange('s').value, l = getRange('l').value;
        for (let i = 0; i <= 6; i ++) {
          start = `hsl(${i * 60}, ${s}%, ${l}%, ${couleur.a})`;
          gradient = [...gradient, start];
        }
        break;
      case 's':
        h = getRange('h').value, l = getRange('l').value;
        gradient = [
          `hsl(${h}, 0%, ${l}%, ${couleur.a})`,
          `hsl(${h}, 50%, ${l}%, ${couleur.a})`,
          `hsl(${h}, 100%, ${l}%, ${couleur.a})`
        ];
        break;
      case 'l':
        h = getRange('h').value, s = getRange('s').value;
        gradient = [
          `hsl(${h}, ${s}%, 0%, ${couleur.a})`,
          `hsl(${h}, ${s}%, 50%, ${couleur.a})`,
          `hsl(${h}, ${s}%, 100%, ${couleur.a})`
        ];
        break;
      case 'w':
        h = getRange('h').value, bk = getRange('bk').value;
        gradient = [
          new Couleur(`hwb(${h}, 0%, ${bk}%, ${couleur.a})`),
          new Couleur(`hwb(${h}, 50%, ${bk}%, ${couleur.a})`),
          new Couleur(`hwb(${h}, 100%, ${bk}%, ${couleur.a})`)
        ];
        break;
      case 'bk':
        h = getRange('h').value, w = getRange('w').value;
        gradient = [
          new Couleur(`hwb(${h}, ${w}%, 0%, ${couleur.a})`),
          new Couleur(`hwb(${h}, ${w}%, 50%, ${couleur.a})`),
          new Couleur(`hwb(${h}, ${w}%, 100%, ${couleur.a})`)
        ];
        break;
      case 'ciel':
        ciec = getRange('ciec').value, cieh = getRange('cieh').value;
        for (let i = 0; i < 2; i ++) {
          gradient.pop();
          start = `lch(${i * 50}% ${ciec} ${cieh} / ${couleur.a})`;
          end = `lch(${(i + 1) * 50}% ${ciec} ${cieh} / ${couleur.a})`;
          gradient = [...gradient, ...Couleur.gradient(start, end, 5)];
        }
        break;
      case 'ciec':
        ciel = getRange('ciel').value, cieh = getRange('cieh').value;
        start = `lch(${ciel}% 0 ${cieh} / ${couleur.a})`;
        end = `lch(${ciel}% ${range.max} ${cieh} / ${couleur.a})`;
        gradient = Couleur.gradient(start, end, 5);
        break;
      case 'cieh':
        ciel = getRange('ciel').value, ciec = getRange('ciec').value;
        for (let i = 0; i < 6; i ++) {
          gradient.pop();
          start = `lch(${ciel}% ${ciec} ${i / 6} / ${couleur.a})`;
          end = `lch(${ciel}% ${ciec} ${(i + 1) / 6} / ${couleur.a})`;
          gradient = [...gradient, ...Couleur.gradient(start, end, 3)];
        }
        break;
      case 'ciea':
        ciel = getRange('ciel').value, cieb = getRange('cieb').value;
        start = new Couleur(`lab(${ciel}% ${range.min} ${cieb} / ${couleur.a})`);
        end = new Couleur(`lab(${ciel}% ${range.max} ${cieb} / ${couleur.a})`);
        gradient = Couleur.gradient(start, end, 10, 'lab');
        break;
      case 'cieb':
        ciel = getRange('ciel').value, ciea = getRange('ciea').value;
        start = new Couleur(`lab(${ciel}% ${ciea} ${range.min} / ${couleur.a})`);
        end = new Couleur(`lab(${ciel}% ${ciea} ${range.max} / ${couleur.a})`);
        gradient = Couleur.gradient(start, end, 10, 'lab');
        break;
    }
    range.style.setProperty('--couleurs', gradient.map((c, k) => {
      if (k == 0) {
        return `${c.rgb || c} 0, ${c.rgb || c} 6px`;
      } else if (k == gradient.length - 1) {
        return `${c.rgb || c} calc(100% - 6px), ${c.rgb || c} 100%`;
      } else return c.rgb || c
    }).join(', '));
  }
}
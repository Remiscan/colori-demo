// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import '/_common/components/theme-selector/theme-selector.js.php';
import '/_common/components/tab-label/tab-label.js.php';
import Cookie from '/colori/demo/modules/cookies.js.php';
import { Traduction } from '/colori/demo/modules/traduction.js.php';
import { updateCouleur, updateSliders } from '/colori/demo/modules/colorDetection.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



/////////////////////////////////////////////////
// Detects user input in the "type a color" field
const champ = document.getElementById('entree');
champ.addEventListener('input', event => {
  let evt = event || window.event;
  updateCouleur(evt.target.value.replace(/'/g, ''), 50)
  .catch(error => {});
});


/////////////////////////////////////
// Detect user choice of color format
for (const input of [...document.querySelectorAll('input[name="choix-format"]')]) {
  input.addEventListener('change', event => {
    if (!input.checked) return;
    document.querySelector('#ranges').dataset.format = input.value;
  });
}


////////////////////////////////////
// Detect user input on input ranges
const rangeValue = prop => document.querySelector(`input[type="range"][data-property="${prop}"]`).value;
for (const input of [...document.querySelectorAll('input[type="range"][data-property]')]) {
  // Create corresponding numeric input
  const numericInput = document.createElement('input');
  numericInput.type = "number";
  numericInput.dataset.property = input.dataset.property;
  numericInput.min = input.min;
  numericInput.max = input.max;
  numericInput.step = 1;
  numericInput.value = input.value;
  input.parentElement.appendChild(numericInput);

  // Update interface color on range change
  input.addEventListener('change', event => {
    const format = document.querySelector('#ranges').dataset.format;
    let couleur;
    const a = rangeValue('a') / 100;
    switch (format) {
      case 'rgb': couleur = `rgb(${rangeValue('r')}, ${rangeValue('g')}, ${rangeValue('b')}, ${a})`; break;
      case 'hsl': couleur = `hsl(${rangeValue('h')}, ${rangeValue('s')}%, ${rangeValue('l')}%, ${a})`; break;
      case 'hwb': couleur = `hwb(${rangeValue('h')} ${rangeValue('w')}% ${rangeValue('bk')}% / ${a})`; break;
      case 'lab': couleur = `lab(${rangeValue('ciel')}% ${rangeValue('ciea')} ${rangeValue('cieb')} / ${a})`; break;
      case 'lch': couleur = `lch(${rangeValue('ciel')}% ${rangeValue('ciec')} ${rangeValue('cieh')} / ${a})`; break;
    }

    updateCouleur(couleur, 50)
    .catch(error => console.error(error));
  });

  // Move numeric input on range drag
  input.addEventListener('input', event => {
    if (numericInput.value == input.value) return;
    if (![input, numericInput].includes(document.activeElement)) input.focus();
    numericInput.value = input.value;
    numericInput.style.setProperty('--pos', (input.value - input.min) / (input.max - input.min));
  });

  // Move numeric input and update range input value on range change
  numericInput.addEventListener('change', event => {
    input.value = numericInput.value;
    numericInput.style.setProperty('--pos', (input.value - input.min) / (input.max - input.min));
    input.dispatchEvent(new Event('change'));
  });
}


/////////////////////////////////////
// Show documentation on user request
const docuButton = document.querySelector('.show-documentation');
docuButton.addEventListener('click', () => {
  document.documentElement.dataset.showDocumentation = 'true';
  Prism.highlightAll(document.querySelector('.documentation'));
  document.querySelector('a[href="#show-documentation"]').href = '#documentation';
});


////////////////////////////////////////////////
// Switch between js and php version of the page
window.addEventListener('tabchange', event => {
  if (event.detail.group != 'tabs-prog-language') return;
  switch (event.detail.value) {
    case 'docu-js-fr':
    case 'docu-js-en':
      document.documentElement.dataset.progLanguage = 'js';
      break;
    case 'docu-php-fr':
    case 'docu-php-en':
      document.documentElement.dataset.progLanguage = 'php';
      break;
  }
});


/////////////////////
// On language change
window.addEventListener('langchange', event => {
  // Check the correct prog-language-choice tab
  const lang = event.detail.lang;
  let progLang;
  switch (document.querySelector('input[name="tabs-prog-language"]:checked').value) {
    case 'docu-js-fr':
    case 'docu-js-en':
      progLang = 'js';
      break;
    case 'docu-php-fr':
    case 'docu-php-en':
      progLang = 'php';
      break;
  }
  document.querySelector(`#input-for-docu-${progLang}-${lang}`).click();
});


//////////////////
// On theme change
window.addEventListener('themechange', event => {
  document.documentElement.dataset.resolvedTheme = event.detail.resolvedTheme;

  const meta = document.querySelector('meta[name=theme-color]');
  meta.content = meta.dataset[event.detail.resolvedTheme];

  if (event.detail.theme != 'auto') {
    new Cookie('theme', event.detail.theme);
    new Cookie('resolvedTheme', event.detail.resolvedTheme);
  } else {
    Cookie.delete('theme');
    Cookie.delete('resolvedTheme');
  }
});


///////////////
// On page load
window.addEventListener('DOMContentLoaded', async () => {
  await Traduction.traduire();

  // Detect clicks on example buttons
  for (const exemple of [...document.querySelectorAll('#demo button.exemple')]) {
    exemple.addEventListener('click', () => {
      if (exemple.textContent == '+') {
        for (const hiddenElement of [...document.querySelectorAll('.inst-hidden')]) {
          hiddenElement.classList.toggle('off');
        }
      } else {
        champ.value = exemple.textContent;
        champ.dispatchEvent(new Event('input'), { bubbles: true });
      }
    })
  }

  // Customize theme-selector
  document.querySelector('theme-selector .selector-title').classList.add('h4');
  document.querySelector('theme-selector .selector-cookie-notice').classList.add('h6');

  Prism.highlightAll(document.querySelector('#demo'));
  updateSliders(document.documentElement.dataset.startColor);
});
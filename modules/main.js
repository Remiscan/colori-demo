import 'color-swatch';
import { updateInterface, updateSliders } from 'colorInterface';
import Cookie from 'cookies';
import 'remiscan-logo';
import 'tab-label';
import 'theme-selector';



////////////////////////////////////////////////
// Detect user input in the "type a color" field
const champ = document.getElementById('entree');
champ.addEventListener('input', event => {
  let evt = event || window.event;
  updateInterface(evt.target.value.replace(/'/g, ''));
});


///////////////////////////////////
// Detect clicks on example buttons
for (const exemple of [...document.querySelectorAll('.demo button.exemple')]) {
  exemple.addEventListener('click', () => {
    if (exemple.dataset.label == 'more-examples') {
      for (const hiddenElement of [...document.querySelectorAll('#saisie [data-hidden]')]) {
        hiddenElement.classList.toggle('off');
      }
    } else {
      champ.value = exemple.textContent;
      champ.dispatchEvent(new Event('input'), { bubbles: true });
    }
  })
}


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
  const numericInput = document.querySelector(`input[type="number"][data-property="${input.dataset.property}"]`);
  numericInput.style.setProperty('--pos', (input.value - input.min) / (input.max - input.min));

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
      case 'oklab': couleur = `oklab(${rangeValue('okl')}% ${rangeValue('oka')} ${rangeValue('okb')} / ${a})`; break;
      case 'oklch': couleur = `oklch(${rangeValue('okl')}% ${rangeValue('okc')} ${rangeValue('okh')} / ${a})`; break;
    }
    updateInterface(couleur, `range-${input.dataset.property}`);
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
/*const docuButton = document.querySelector('.show-documentation');
const documentation = document.querySelector('.documentation');
docuButton.addEventListener('click', () => {
  document.documentElement.dataset.showDocumentation = 'true';
  const visibleArticle = documentation.querySelector('article:not([hidden]');
  if (visibleArticle.dataset.highlighted !== 'true') {
    visibleArticle.dataset.highlighted = 'true';
    Prism.highlightAllUnder(documentation.querySelector('article:not([hidden]'));
  }
});*/


////////////////////////////////////////////////
// Switch between js and php version of the page
window.addEventListener('tabchange', event => {
  if (event.detail.group != 'tabs-prog-language') return;
  document.documentElement.dataset.progLanguage = event.detail.value.replace('doc-', '');
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



// Customize theme-selector
const themeSelector = document.querySelector('theme-selector');
themeSelector.querySelector('.selector-title').classList.add('h4');
themeSelector.querySelector('.selector-cookie-notice').classList.add('h6');

// Syntax highlighting on start color formats
//Prism.highlightAllUnder(document.querySelector('#resultats'));

// Update sliders based on start color
updateSliders(document.documentElement.dataset.startColor, 'init');

// Mark tappable custom elements as such
for (const e of [...document.querySelectorAll('theme-selector button, tab-label label')]) {
  e.dataset.tappable = '';
}
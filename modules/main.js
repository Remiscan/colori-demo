import { CanceledAsyncWarning } from 'cancelable-async';
import 'color-picker';
import 'color-swatch';
import { updateInterface } from 'colorInterface';
import Cookie from 'cookies';
import 'remiscan-logo';
import 'tab-list';
import 'theme-selector';



////////////////////////////////////////////////
// Detect user input in the "type a color" field
const champ = document.getElementById('entree');
champ.addEventListener('input', async event => {
  try {
    await updateInterface(event.target.value);
  } catch (e) {
    if (e instanceof CanceledAsyncWarning) {}
    else console.error(e);
  }
});


////////////////////////////////////
// Detect user input in color picker
const colorPicker = document.querySelector('color-picker');
colorPicker.addEventListener('input', async event => {
  const colorExpr = event.detail?.color;
  if (!colorExpr) return;
  champ.value = colorExpr;
  try {
    await updateInterface(colorExpr, 'color-picker');
  } catch (e) {
    if (e instanceof CanceledAsyncWarning) {}
    else console.error(e);
  }
});


///////////////////////////////////
// Detect clicks on example buttons
for (const exemple of [...document.querySelectorAll('#demo button.exemple')]) {
  exemple.addEventListener('click', () => {
    if (exemple.dataset.action == 'more-examples') {
      const container = document.querySelector('#saisie');
      const isOpen = container.getAttribute('data-details') != null;
      if (isOpen) container.removeAttribute('data-details');
      else        container.setAttribute('data-details', '');
    } else {
      champ.value = exemple.textContent;
      champ.dispatchEvent(new Event('input'), { bubbles: true });
    }
  })
}


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

  const visibleArticle = documentation.querySelector('article:not([hidden]');
  if (visibleArticle.dataset.highlighted !== 'true') {
    visibleArticle.dataset.highlighted = 'true';
    //Prism.highlightAllUnder(documentation.querySelector('article:not([hidden]'));
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



// Customize theme-selector
const themeSelector = document.querySelector('theme-selector');
themeSelector.querySelector('.selector-title').classList.add('h4');
themeSelector.querySelector('.selector-cookie-notice').classList.add('h6');

// Mark tappable custom elements as such
for (const e of [...document.querySelectorAll('theme-selector button, tab-list button')]) {
  e.dataset.tappable = '';
}



// Make colori accessible from dev tools
try {
  window.Couleur = (await import('colori')).default;
  console.log('You can use the Couleur class here in the console to try it out!');
} catch (error) {}
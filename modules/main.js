import 'color-swatch';
import { updateInterface } from 'colorInterface';
import Cookie from 'cookies';
import 'remiscan-logo';
import 'tab-label';
import 'theme-selector';



if ('paintWorklet' in CSS) {
  import('color-picker');
}



////////////////////////////////////////////////
// Detect user input in the "type a color" field
const champ = document.getElementById('entree');
champ.addEventListener('input', event => {
  updateInterface(event.target.value.replace(/'/g, ''));
});


////////////////////////////////////
// Detect user input in color picker
const colorPicker = document.querySelector('color-picker');
colorPicker.addEventListener('input', event => {
  const colorExpr = event.detail?.color;
  if (!colorExpr) return;
  champ.value = colorExpr;
  updateInterface(colorExpr, 'color-picker');
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


/////////////////////////////////////////////////////
// Close documentation index on clicking a link in it
const docIndex = document.querySelector('.nav-rapide-container');
[...docIndex.querySelectorAll('.nav-rapide a')].forEach(link => {
  link.addEventListener('click', event => {
    docIndex.removeAttribute('open');
  });
});


////////////////////////////////////////////////
// Switch between js and php version of the page
window.addEventListener('tabchange', event => {
  if (event.detail.group == 'tabs-prog-language') {
    document.documentElement.dataset.progLanguage = event.detail.value.replace('doc-', '');
  }
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
for (const e of [...document.querySelectorAll('theme-selector button, tab-label label')]) {
  e.dataset.tappable = '';
}
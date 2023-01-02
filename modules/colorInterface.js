import { messageWorker } from 'messageWorker';



let lastTry;

/** Update the interface with the newly detected color. */
export async function updateInterface(couleur, source = 'text', delai = 10) {
  const thisTry = Date.now();
  lastTry = thisTry;

  await new Promise(resolve => setTimeout(resolve, delai));
  if (lastTry != thisTry) return;

  const formats = [...document.querySelectorAll('.donnees [data-format]')];

  // Send all the data to the worker and wait for its response
  const response = await messageWorker('compute-interface', {
    colorString: couleur,
  });

  if (typeof response.type === 'undefined') return;
  //updateSliders(response.colorValuesClipped, source);

  // Hide non-format results by default
  const donnees = document.querySelector('#resultats');
  donnees.removeAttribute('data-type');
  donnees.dataset.type = response.type;

  const valeur = document.querySelector('.format.valeur code');
  valeur.innerHTML = response.value;

  if (response.input !== null) document.querySelector('.format.gradient').style.setProperty('--bg', response.input);
  if (response.type.startsWith('array')) {
    document.querySelector('.format.gradient').style.setProperty('--gradient', response.gradient);
    //Prism.highlightElement(valeur);
  }

  if (response.type === null) console.log(`${couleur} == ${entree}`);

  // Populate results in all formats
  const colorSwatches = [...document.querySelectorAll('.donnees color-swatch')];
  if (response.type === 'Couleur') {
    const vals = response.colorValues;
    for (const swatch of colorSwatches) {
      swatch.setAttribute('color', `color(srgb ${vals[0]} ${vals[1]} ${vals[2]} / ${vals[3]})`);
    }

    // Changes the input field placeholder text
    const champ = document.getElementById('entree');
    champ.placeholder = response.colorName || response.colorHex;


    // Update the color picker
    if ('paintWorklet' in CSS && source !== 'color-picker') {
      const colorPicker = document.querySelector('color-picker');
      const vals = response.colorValues;
      colorPicker.selectColor(`color(srgb ${vals[0]} ${vals[1]} ${vals[2]} / ${vals[3]})`);
    }
  }

  const nameSwatchRow = document.querySelector('#results-named-formats color-swatch[format="name"]');
  if (response.colorName) nameSwatchRow.classList.remove('off');
  else                    nameSwatchRow.classList.add('off');

  if (!!response.css) {
    const meta = document.querySelector('meta[name=theme-color]');
    meta.dataset.light = response.metaLight;
    meta.dataset.dark = response.metaDark;

    const style = document.getElementById('theme-variables');
    style.innerHTML = response.css;
  }
}
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
  updateSliders(response.colorValues, source);

  // Hide non-format results by default
  const donnees = document.querySelector('#resultats');
  donnees.removeAttribute('data-type');
  donnees.dataset.type = response.type;

  const valeur = document.querySelector('.format.valeur code');
  valeur.innerHTML = response.value;

  if (response.input !== null) document.querySelector('.format.gradient').style.setProperty('--bg', response.input);
  if (response.type.startsWith('array')) {
    document.querySelector('.format.gradient').style.setProperty('--gradient', response.gradient);
    Prism.highlightElement(valeur);
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



/** Updates the color selection sliders to fit a given color. */
export async function updateSliders(couleur, source = 'text') {
  if (couleur.length === 0) return;
  const ranges = [...document.querySelectorAll('input[type="range"][data-property]')];
  let rangeData = ranges.map(range => {
    return {
      prop: range.dataset.property,
      min: range.min,
      max: range.max,
      step: range.step,
      value: range.value,
      numericInputPos: null,
      gradient: null
    };
  });

  const rangesContainer = document.querySelector('#ranges');
  const visibleFormat = rangesContainer.getAttribute('hidden') !== 'hidden' ? rangesContainer.dataset.format : '';
  let visibleProps;

  [rangeData, visibleProps] = await messageWorker('compute-sliders', {
    rangeData,
    couleur,
    visibleFormat
  });

  // Loop 3: apply the changes
  for (const [k, range] of Object.entries(ranges)) {
    const prop = rangeData[k].prop;

    let shouldUpdate = true;
    if (source.substring(0, 5) == 'range') {
      const isVisible = visibleProps.includes(prop);
      shouldUpdate = !isVisible;
    }

    // Only update sliders that are not being used
    if (shouldUpdate && source !== 'init') {
      // Update slider value
      range.value = rangeData[k].newValue;

      // Update corresponding numeric input value
      const numericInput = document.querySelector(`input[type="number"][data-property="${prop}"]`);
      if (numericInput) numericInput.style.setProperty('--pos', rangeData[k].numericInputPos);
      numericInput.value = range.value;
    }

    // Display the new gradients
    const gradient = rangeData[k].gradient;
    range.style.setProperty('--couleurs', gradient);
  }
}
import { cancelableAsync } from 'cancelable-async';
import { messageWorker } from 'messageWorker';



function updateThemeCss(css, metaLightColor, metaDarkColor) {
  const meta = document.querySelector('meta[name=theme-color]');
  meta.dataset.light = metaLightColor;
  meta.dataset.dark = metaDarkColor;
  const currentTheme = document.documentElement.getAttribute('data-resolved-theme');
  meta.setAttribute('content', currentTheme === 'dark' ? metaDarkColor : metaLightColor);

  const style = document.getElementById('theme-variables');
  style.innerHTML = css;
}



/** Update the interface with the newly detected color. */
function* updateInterface(couleur, source = 'text') {
  // Wait until next frame
  yield new Promise(resolve =>
    requestAnimationFrame(t1 =>
      requestAnimationFrame(t2 => resolve(t2 - t1))
    )
  );

  // Send all the data to the worker and wait for its response
  const response = yield messageWorker('compute-interface', {
    colorString: couleur,
    placeholder: document.querySelector('#entree').getAttribute('placeholder')
  });

  if (typeof response.type === 'undefined') return;

  // Set response data type
  const container = document.querySelector('#resultats');
  container.removeAttribute('data-type');
  container.dataset.type = response.type ?? '';

  if (response.colors.length >= 0) {
    // Create color-swatches in #results-multiple
    const colorsContainer = document.querySelector('#results-multiple');
    let html = '';
    const kMax = response.colors.length - 1;
    const gradient = `${response.colorsClamped?.map((c, k) => `${c} ${100*k/kMax}%`).join(', ')}`;
    html += /*html*/`
      <div class="format">
        <div class="gradient" style="--gradient: linear-gradient(to bottom, ${gradient})"></div>
      </div>
    `;
    for (const color of response.colors) {
      const hasName = !(color.startsWith('color'));
      const format = hasName ? 'name' : 'rgb';
      html += `<color-swatch format="${format}" color="${color}"></color-swatch>`;
    }
    colorsContainer.innerHTML = html;
  }

  if (response.interfaceColorExpr) {
    // Update color-swatches in #results-named-formats & #results-color-spaces
    const swatches = document.querySelectorAll('#results-named-formats color-swatch, #results-color-spaces color-swatch');
    for (const swatch of swatches) {
      swatch.setAttribute('color', response.interfaceColorExpr);
    }

    // Update input field placeholder
    const input = document.getElementById('entree');
    input.placeholder = response.interfaceColorName || response.interfaceColorHex;

    // Update the color-picker
    if (source !== 'color-picker') {
      const colorPicker = document.querySelector('color-picker');
      colorPicker.selectColor(response.interfaceColorExpr);
    }

    // Hide or display the name color-swatch
    const nameSwatch = document.querySelector('#results-named-formats color-swatch[format="name"]');
    if (response.interfaceColorName) nameSwatch.classList.remove('off');
    else                             nameSwatch.classList.add('off');
  }

  if (response.value) {
    document.querySelector('[data-result="value"]').innerHTML = response.value;
  }

  if (response.css) {
    if (source === 'text' && 'startViewTransition' in document) {
      document.startViewTransition(() => {
        updateThemeCss(response.css, response.metaLight, response.metaDark);
      });
    } else {
      updateThemeCss(response.css, response.metaLight, response.metaDark);
    }
  }
}

updateInterface = cancelableAsync(updateInterface);
export { updateInterface };


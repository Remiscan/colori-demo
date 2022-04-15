const template = document.createElement('template');
template.innerHTML = `
  <div class="color-swatch-preview"></div>
  <code class="color-swatch-expression in-gamut"></code>
  <code class="color-swatch-expression out-of-gamut"></code>

  <button type="button" class="color-swatch-see-alt out-of-gamut">
    <svg viewBox="0 0 24 24">
      <style>.warn-icon { fill: currentColor; }</style>
      <path class="warn-icon" d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
    </svg>
  </button>

  <button type="button" class="color-swatch-copy">
    <svg viewBox="0 0 24 24">
      <style>.copy-icon { fill: currentColor; }</style>
      <path class="copy-icon" d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
    </svg>
  </button>
`;

export default template;
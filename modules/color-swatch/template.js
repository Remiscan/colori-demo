const template = document.createElement('template');
template.innerHTML = `
  <div class="color-swatch-preview"></div>
  <span class="color-swatch-expression"></span>
  <button type="button" class="color-swatch-copy">
    <svg viewBox="0 0 24 24">
      <style>.copy-icon { fill: currentColor; }</style>
      <path class="copy-icon" d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
    </svg>
  </button>
`;

export default template;
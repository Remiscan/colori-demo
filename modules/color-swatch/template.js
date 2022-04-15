const template = document.createElement('template');
template.innerHTML = `
  <div class="color-swatch-preview"></div>
  <code class="color-swatch-expression in-gamut"></code>
  <code class="color-swatch-expression out-of-gamut"></code>

  <span lang="fr" class="color-swatch-in-gamut-warning">Mappé vers le gamut <span class="color-swatch-format"></span></span>
  <span lang="en" class="color-swatch-in-gamut-warning">Mapped to <span class="color-swatch-format"></span> gamut</span>
  <span lang="fr" class="color-swatch-out-of-gamut-warning">Hors du gamut <span class="color-swatch-format"></span></span>
  <span lang="en" class="color-swatch-out-of-gamut-warning">Out of <span class="color-swatch-format"></span> gamut</span>

  <button type="button" class="color-swatch-see-alt out-of-gamut">
    <svg viewBox="1 1 22 22">
      <style>.alt-icon { fill: currentColor; }</style>
      <path class="alt-icon" d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/>
    </svg>
    <span lang="fr" class="in-gamut">Voir l'expression hors du gamut</span>
    <span lang="en" class="in-gamut">See out-of-gamut expression</span>
    <span lang="fr" class="out-of-gamut">Voir l'expression dans le gamut</span>
    <span lang="en" class="out-of-gamut">See in-gamut expression</span>
  </button>

  <button type="button" class="color-swatch-copy">
    <svg viewBox="0 0 24 24">
      <style>.copy-icon { fill: currentColor; }</style>
      <path class="copy-icon" d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
    </svg>
    <span lang="fr">Copier</span>
    <span lang="en">Copy</span>
  </button>
`;

export default template;
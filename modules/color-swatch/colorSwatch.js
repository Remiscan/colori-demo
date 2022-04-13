import sheet from './styles.css' assert { type: 'css' };
import template from './template.js';

import Couleur from 'colori';



class ColorSwatch extends HTMLElement {
  constructor() {
    super();
    this.ready = false;
    this.color = null;
    this.copyHandle = () => {};
  }


  update(attr, oldValue, newValue) {
    if (!this.ready) return;

    switch (attr) {
      case 'color': {
        this.color = new Couleur(newValue);
        const inGamut = this.color.inGamut('srgb');

        const preview = this.querySelector('.color-swatch-preview');
        const expression = this.querySelector('.color-swatch-expression');
        expression.classList.remove('clipped');
        
        const format = this.getAttribute('format');
        let value = '';
        switch (format) {
          case 'name': value = this.color.name; break;
          case 'hex':  value = this.color.hex; break;
          default:     value = this.color.expr(this.getAttribute('format'), {
            precision: format.startsWith('color-') ? 4 : 2,
            clamp: true
          });
        }
        expression.innerHTML = value;
        
        preview.style.setProperty('--color', this.color.rgb);
        if (!inGamut) {
          expression.classList.add('clipped');
        }
      } break;
    }
  }


  connectedCallback() {
    // Add HTML and CSS to the element
    if (!document.adoptedStyleSheets.includes(sheet))
      document.adoptedStyleSheets = [...document.adoptedStyleSheets, sheet];
    this.appendChild(template.content.cloneNode(true));
    
    // Copy the color expression by clicking the copy button
    const copyButton = this.querySelector('.color-swatch-copy');
    copyButton.addEventListener('click', this.copyHandle = event => {
      const expression = this.querySelector('.color-swatch-expression');
      const valueToCopy = expression.innerText;

      try {
        navigator.permissions.query({ name: 'clipboard-write' }).then(result => {
          if (result.state === 'granted' || result.state === 'prompt') {
            navigator.clipboard.writeText(valueToCopy);
          }
        });
      } catch {}
    });

    this.ready = true;
    for (const attr of ColorSwatch.observedAttributes) {
      this.update(attr, '', this.getAttribute(attr));
    }
  }


  disconnectedCallback() {
    const copyButton = this.querySelector('.color-swatch-copy');
    copyButton.removeEventListener('click', this.copyHandle);
  }


  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue === newValue) return;
    this.update(name, oldValue, newValue);
  }

  static get observedAttributes() {
    return ['color', 'format'];
  }
}

if (!customElements.get('color-swatch')) customElements.define('color-swatch', ColorSwatch);
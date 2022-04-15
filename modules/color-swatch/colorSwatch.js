import sheet from './styles.css' assert { type: 'css' };
import template from './template.js';

import Couleur from 'colori';



class ColorSwatch extends HTMLElement {
  constructor() {
    super();
    this.ready = false;
    this.color = null;
    this.copyHandle = () => {};
    this.altHandle = () => {};
  }


  update(attr, oldValue, newValue) {
    if (!this.ready) return;

    switch (attr) {
      case 'color': {
        this.color = new Couleur(newValue);

        const preview = this.querySelector('.color-swatch-preview');
        const expression = this.querySelector('.color-swatch-expression.in-gamut');
        this.removeAttribute('clipped');
        
        const format = this.getAttribute('format');
        let value = '';
        switch (format) {
          case 'name': value = this.color.name; break;
          case 'hex':  value = this.color.hex; break;
          default:     value = this.color.expr(this.getAttribute('format'), {
            precision: format.startsWith('color-') ? 4 : 2,
            clamp: format.startsWith('color-') ? false : true
          });
        }
        expression.innerHTML = value;
        preview.style.setProperty('--color', this.color.rgb);

        if (!['name', 'hex'].includes(format)) {
          const inGamut = this.color.inGamut(format.replace('color-', ''));
          if (!inGamut && !format.startsWith('color-')) {
            this.setAttribute('clipped', '');
            const expressionAlt = this.querySelector('.color-swatch-expression.out-of-gamut');
            expressionAlt.innerHTML = this.color.expr(this.getAttribute('format'), {
              precision: format.startsWith('color-') ? 4 : 2,
              clamp: false
            });
          }
        }
      } break;

      case 'format': {
        this.update('color', '', this.getAttribute('color'));
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
      const expression = this.getAttribute('alt') != null ? this.querySelector('.color-swatch-expression.out-of-gamut')
                                                          : this.querySelector('.color-swatch-expression.in-gamut');
      const valueToCopy = expression.innerText;

      try {
        navigator.permissions.query({ name: 'clipboard-write' }).then(result => {
          if (result.state === 'granted' || result.state === 'prompt') {
            navigator.clipboard.writeText(valueToCopy);
          }
        });
      } catch {}
    });

    // Switch to the out-of-gamut expression by clicking the warning button
    const altButton = this.querySelector('.color-swatch-see-alt');
    altButton.addEventListener('click', this.altHandle = event => {
      if (this.getAttribute('alt') == null) this.setAttribute('alt', '');
      else                                  this.removeAttribute('alt'); 
    });

    this.ready = true;
    for (const attr of ColorSwatch.observedAttributes) {
      this.update(attr, '', this.getAttribute(attr));
    }
  }


  disconnectedCallback() {
    const copyButton = this.querySelector('.color-swatch-copy');
    copyButton.removeEventListener('click', this.copyHandle);

    const altButton = this.querySelector('.color-swatch-see-alt');
    altButton.removeEventListener('click', this.altHandle);
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
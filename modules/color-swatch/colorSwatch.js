import strings from 'color-swatch-strings' assert { type: 'json' };
import sheet from 'color-swatch-styles' assert { type: 'css' };
import template from 'color-swatch-template';
import translationObserver from 'translation-observer';

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
            clamp: true
          });
        }
        expression.innerHTML = value;
        preview.style.setProperty('--color', CSS.supports(`color: ${value}`) ? value : this.color.rgb);
        preview.style.removeProperty('--alt-color');

        const space = ['name', 'hex'].includes(format) ? 'srgb' : format;
        const inGamut = this.color.inGamut(space.replace('color-', ''));
        if (!inGamut) {
          this.setAttribute('clipped', '');
          const expressionAlt = this.querySelector('.color-swatch-expression.out-of-gamut');
          const value = this.color.expr(space, {
            precision: format.startsWith('color-') ? 4 : 2,
            clamp: false
          });
          expressionAlt.innerHTML = value;

          let gamut = space.replace('color-', '');
          switch (gamut) {
            case 'rgb':
            case 'hex':
            case 'name':
            case 'hsl':
            case 'hwb':
              gamut = 'srgb';
              break;

            default:
              preview.style.setProperty('--alt-color', CSS.supports(`color: ${value}`) ? value : this.color.rgb);
          }
          for (const e of [...this.querySelectorAll('.color-swatch-format')]) {
            e.innerHTML = gamut.toUpperCase();
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

    translationObserver.serve(this);
    
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

    translationObserver.unserve(this);
  }


  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue === newValue) return;
    if (name === 'lang') {
      const lang = newValue;
      const defaultLang = 'en';
      const getString = id => strings[lang]?.[id] ?? strings[defaultLang]?.[id] ?? 'undefined string';

      // Translate element content
      for (const e of [...this.querySelectorAll('[data-string]')]) {
        if (e.tagName == 'IMG') e.alt = getString(e.dataset.string);
        else                    e.innerHTML = getString(e.dataset.string);
      }
      for (const e of [...this.querySelectorAll('[data-label]')]) {
        e.setAttribute('aria-label', getString(e.dataset.label));
      }
    } else {
      this.update(name, oldValue, newValue);
    }
  }

  static get observedAttributes() {
    return ['color', 'format', 'lang'];
  }
}

if (!customElements.get('color-swatch')) customElements.define('color-swatch', ColorSwatch);
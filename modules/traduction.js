import DefTraduction from 'default-traduction';



class ExtTraduction extends DefTraduction {
  constructor() {
    const path = document.querySelector('link#strings').getAttribute('href');
    super('colori', path, 'fr');
  }

  async traduire(element = document) {
    await super.traduire(element);
    const themeSelector = document.querySelector('theme-selector');
    if (element.contains(themeSelector)) {
      document.querySelector('theme-selector').dataset.tolabel = getString('change-theme');
    }

    for (const e of [...element.querySelectorAll('[data-label-id]')]) {
      e.setAttribute('label', getString(e.dataset.labelId));
    }
    return;
  }
}



export const Traduction = new ExtTraduction();
export const getString = Traduction.getString.bind(Traduction);
import Couleur from 'colori';
import { resolveColor } from 'colorResolution';



/** Compute all color data from the new user input color. */
export function computeInterface({ colorString }) {
  const colorData = resolveColor(colorString);
  let userColor, method, input;
  if (Array.isArray(colorData) && colorData.length === 3) {
    [userColor, method, input] = colorData;
  }

  // If the user input resolves to a color, adapt the interface
  let responseType;
  let interfaceColor = null;
  let interfaceColorClipped = null;
  let value = null;
  let gradient = null;
  let name, hex;

  if (userColor instanceof Couleur) {
    responseType = 'Couleur';
    interfaceColor = userColor;
    interfaceColorClipped = userColor.toGamut('srgb');
  }

  else if (typeof userColor === 'number' || typeof userColor === 'boolean' || typeof userColor === 'string') {
    responseType = 'value';
    value = userColor;
  }

  else if (Array.isArray(userColor) && userColor.length > 0 && userColor.reduce((sum, e) => sum + (e instanceof Couleur), 0)) {
    responseType = 'array,value';
    interfaceColor = userColor[0];
    interfaceColorClipped = userColor[0].toGamut('srgb');
    gradient = `linear-gradient(to right,  ${userColor.map(c => c.name || c.rgb).join(',  ')})`;
    if (method === 'interpolate') {
      responseType += ',gradient';
      value = `linear-gradient(to right,\n  ${userColor.map(c => c.name || c.rgb).join(',\n  ')}\n)`;;
    }
    else if (method === 'whatToBlend') {
      responseType += ',gradient,whatToBlend';
      value = `[\n  ${userColor.map(c => c.name || c.rgb).join(',\n  ')}\n]`;
    }
  }

  else if (typeof userColor !== 'undefined') {
    console.log(`${colorString} == ${userColor}`);
  }

  const response = {
    type: responseType,
    colorValues: interfaceColor instanceof Couleur ? [...interfaceColor.values, interfaceColor.a] : [],
    colorValuesClipped: interfaceColorClipped instanceof Couleur ? [...interfaceColorClipped.values, interfaceColorClipped.a] : [],
    colorName: interfaceColor instanceof Couleur ? interfaceColor.name : '',
    colorHex: interfaceColor instanceof Couleur ? interfaceColor.hex : '',
    value,
    input,
    gradient,
    css: null,
    metaLight: null,
    metaDark: null,
  };

  if (interfaceColor instanceof Couleur) {   
    [response.css, response.metaLight, response.metaDark] = makeCSS(interfaceColor);
  }

  return response;
}



/** Computes the CSS variables for the interface colors. */
function makeCSS(userColor) {
  let cssBoth, cssLight, cssDark;
  let metaLight, metaDark;
  const colorPreview = Couleur.blend('white', userColor);

  // Calculate colors that are the same for both light and dark themes
  const cieh = userColor.cieh;
  both: {
    cssBoth = ``;
  }

  // Calculate colors for light theme
  light: {
    const ciec = Math.min(userColor.ciec, 75/Math.sqrt(3));
    const bodyColor = new Couleur(`lch(75% ${ciec} ${cieh})`);
    metaLight = bodyColor.rgb;
    const sectionColor = new Couleur(`lch(85% ${.6 * ciec} ${cieh})`);
    const codeColor = new Couleur(`lch(92% ${.3 * ciec} ${cieh})`);
    cssLight = `
      /* Background colors */
      --body-color: ${bodyColor.hsl};
      --section-color: ${sectionColor.hsl};
      --frame-color: ${codeColor.improveContrast(colorPreview, 2.5).hsl};
      --code-color: ${codeColor.hsl};
      --tab-hover-color: ${sectionColor.replace('a', .7).hsl};
      --note-color: ${Couleur.blend(sectionColor, bodyColor.replace('a', .3)).hsl};
      /* Text colors */
      --h1-color: ${(new Couleur(`lch(30% ${.6 * ciec} ${cieh})`)).hsl};
      --h3-color: ${(new Couleur(`lch(45% ${ciec} ${cieh})`)).hsl};
      --text-color: black;
      --link-color: ${(new Couleur(`lch(30% ${ciec} ${cieh})`)).hsl};
      --link-underline-color: ${(new Couleur(`lch(30% ${2 * ciec} ${cieh} / .5)`)).hsl};
      /* Input colors */
      --input-bg-color: ${(new Couleur(`lch(95% ${.3 * ciec} ${cieh})`)).hsl};
      --input-active-bg-color: ${(new Couleur(`lch(99% ${.1 * ciec} ${cieh})`)).hsl};
      --input-placeholder-color: ${(new Couleur(`lch(25% ${.5 * ciec} ${cieh} / .5)`)).hsl};
      /* Syntax coloring colors */
      --token-number: ${(new Couleur(`lch(50% 70 ${cieh - 90})`)).hsl};
      --token-string: ${(new Couleur(`lch(50% 70 ${cieh + 45})`)).hsl};
      --token-operator: ${(new Couleur(`lch(50% 70 ${cieh - 45})`)).hsl};
      --token-keyword: ${(new Couleur(`lch(50% 70 ${cieh + 135})`)).hsl};
      /* Button colors */
      --button-bg-color: ${(new Couleur(`lch(90% ${.6 * ciec} ${cieh})`)).hsl};
      --button-active-bg-color: ${(new Couleur(`lch(98% ${.3 * ciec} ${cieh})`)).hsl};
    `;
  }

  // Calculate colors for dark theme
  dark: {
    //const ciec = Math.min(.3 * userColor.ciec, 10);
    const ciec = Math.min(userColor.ciec, 8/Math.sqrt(1.040816));
    const bodyColor = new Couleur(`lch(8% ${.6 * ciec} ${cieh})`);
    metaDark = bodyColor.rgb;
    const sectionColor = new Couleur(`lch(20% ${ciec} ${cieh})`);
    const codeColor = bodyColor;
    cssDark = `
      /* Background colors */
      --body-color: ${bodyColor.hsl};
      --section-color: ${sectionColor.hsl};
      --frame-color: ${codeColor.improveContrast(colorPreview, 2.5).hsl};
      --code-color: ${codeColor.hsl};
      --tab-hover-color: ${sectionColor.replace('a', .7).hsl};
      --note-color: ${Couleur.blend(bodyColor, sectionColor.replace('a', .5)).hsl};
      /* Text colors */
      --h1-color: ${(new Couleur(`lch(80% ${ciec} ${cieh})`)).hsl};
      --h3-color: ${(new Couleur(`lch(70% ${1.7 * ciec} ${cieh})`)).hsl};
      --text-color: ${(new Couleur(`lch(90% ${.2 * ciec} ${cieh})`)).hsl};
      --link-color: ${(new Couleur(`lch(80% ${1.7 * ciec} ${cieh})`)).hsl};
      --link-underline-color: ${(new Couleur(`lch(80% ${2 * 1.7 * ciec} ${cieh} / .5)`)).hsl};
      /* Input colors */
      --input-bg-color: ${(new Couleur(`lch(30% ${1.5 * ciec} ${cieh})`)).hsl};
      --input-active-bg-color: ${(new Couleur(`lch(10% ${.6 * ciec} ${cieh})`)).hsl};
      --input-placeholder-color: ${(new Couleur(`lch(90% ${.5 * ciec} ${cieh} / .5)`)).hsl};
      /* Syntax coloring colors */
      --token-number: ${(new Couleur(`lch(80% 70 ${cieh - 90})`)).hsl};
      --token-string: ${(new Couleur(`lch(80% 70 ${cieh + 45})`)).hsl};
      --token-operator: ${(new Couleur(`lch(80% 70 ${cieh - 45})`)).hsl};
      --token-keyword: ${(new Couleur(`lch(80% 70 ${cieh + 135})`)).hsl};
      /* Button colors */
      --button-bg-color: ${(new Couleur(`lch(25% ${.75 * ciec} ${cieh})`)).hsl};
      --button-active-bg-color: ${(new Couleur(`lch(35% ${1.5 * ciec} ${cieh})`)).hsl};
    `;
  }

  // Let's generate the stylesheet for the interface colors
  const css = `
    :root {
      ${cssLight}
      ${cssBoth}
    }

    @media (prefers-color-scheme: dark) {
      :root {
        ${cssDark}
      }
    }

    :root[data-theme="light"] {
      ${cssLight}
    }

    :root[data-theme="dark"] {
      ${cssDark}
    }
  `;

  return [
    css,
    metaLight,
    metaDark,
  ];
}



/** Computes all sliders data and background-gradients. */
export function computeSliders({ rangeData, couleur, visibleFormat }) {
  const userColor = Couleur.makeInstance(couleur);
  const visibleProps = [...Couleur.propertiesOf(visibleFormat), 'a'];
  const getRangeValue = prop => rangeData.find(r => r.prop == prop)[visibleProps.includes(prop) ? 'value' : 'newValue'];

  // Loop 1: compute new range values
  for (const range of rangeData) {
    const prop = range.prop;
    
    const precision = 1 / range.step;
    let coeff = range.max;
    switch (prop) {
      case 'ciea':
      case 'cieb':
      case 'ciec':
      case 'h':
      case 'cieh':
      case 'okh':
      case 'oka':
      case 'okb':
      case 'okc':
        coeff = 1;
        break;
    }

    const newValue = Math.max(range.min, Math.min(Math.round(precision * coeff * userColor[prop]) / precision, range.max));
    range.newValue = newValue;
    range.numericInputPos = (range.newValue - range.min) / (range.max - range.min);
  }

  // Loop 2: compute new range gradient colors
  for (const range of rangeData) {
    const prop = range.prop;

    let gradient = [];
    let steps, min, max;
    let h, s, l, w, bk, ciel, ciea, cieb, ciec, cieh, okl, oka, okb, okc, okh;
    switch (prop) {
      case 'r':
        gradient.push(`rgb(0, ${255 * userColor.g}, ${255 * userColor.b}, ${userColor.a})`);
        gradient.push(`rgb(255, ${255 * userColor.g}, ${255 * userColor.b}, ${userColor.a})`);
        break;
      case 'g':
        gradient.push(`rgb(${255 * userColor.r}, 0, ${255 * userColor.b}, ${userColor.a})`);
        gradient.push(`rgb(${255 * userColor.r}, 255, ${255 * userColor.b}, ${userColor.a})`);
        break;
      case 'b':
        gradient.push(`rgb(${255 * userColor.r}, ${255 * userColor.g}, 0, ${userColor.a})`);
        gradient.push(`rgb(${255 * userColor.r}, ${255 * userColor.g}, 255, ${userColor.a})`);
        break;
      case 'a':
        gradient.push(`rgb(${255 * userColor.r}, ${255 * userColor.g}, ${255 * userColor.b}, 0)`);
        gradient.push(`rgb(${255 * userColor.r}, ${255 * userColor.g}, ${255 * userColor.b}, 1)`);
        break;
      case 'h':
        s = getRangeValue('s'), l = getRangeValue('l');
        steps = 6;
        for (let i = 0; i <= steps; i ++) {
          gradient.push(`hsl(${i * 360 / steps}, ${s}%, ${l}%, ${userColor.a})`);
        }
        break;
      case 's':
        h = getRangeValue('h'), l = getRangeValue('l');
        steps = 5;
        for (let i = 0; i <= steps; i++) {
          gradient.push(`hsl(${h}, ${i * 100 / steps}%, ${l}%, ${userColor.a})`)
        }
        break;
      case 'l':
        h = getRangeValue('h'), s = getRangeValue('s');
        steps = 5;
        for (let i = 0; i <= steps; i++) {
          gradient.push(`hsl(${h}, ${s}%, ${i * 100 / steps}%, ${userColor.a})`)
        }
        break;
      case 'w':
        h = getRangeValue('h'), bk = getRangeValue('bk');
        steps = 5;
        for (let i = 0; i <= steps; i++) {
          gradient.push((new Couleur(`hwb(${h} ${i * 100 / steps}% ${bk}% / ${userColor.a})`)).rgb)
        }
        break;
      case 'bk':
        h = getRangeValue('h'), w = getRangeValue('w');
        steps = 5;
        for (let i = 0; i <= steps; i++) {
          gradient.push((new Couleur(`hwb(${h} ${w}% ${i * 100 / steps}% / ${userColor.a})`)).rgb)
        }
        break;
      case 'ciel':
        ciec = getRangeValue('ciec'), cieh = getRangeValue('cieh');
        steps = 20;
        for (let i = 0; i <= steps; i ++) {
          gradient.push((new Couleur(`lch(${i * 100 / steps}% ${ciec} ${cieh} / ${userColor.a})`)).rgb)
        }
        break;
      case 'ciec':
        ciel = getRangeValue('ciel'), cieh = getRangeValue('cieh');
        max = Number(range.max);
        steps = 20;
        for (let i = 0; i <= steps; i++) {
          gradient.push((new Couleur(`lch(${ciel}% ${i * max / steps} ${cieh} / ${userColor.a})`)).rgb);
        }
        break;
      case 'cieh':
        ciel = getRangeValue('ciel'), ciec = getRangeValue('ciec');
        steps = 20;
        for (let i = 0; i <= steps; i++) {
          gradient.push((new Couleur(`lch(${ciel}% ${ciec} ${i * 360 / steps} / ${userColor.a})`)).rgb);
        }
        break;
      case 'ciea':
        ciel = getRangeValue('ciel'), cieb = getRangeValue('cieb');
        min = Number(range.min), max = Number(range.max);
        steps = 20;
        for (let i = 0; i <= steps; i++) {
          gradient.push((new Couleur(`lab(${ciel}% ${min + i * (max - min) / steps} ${cieb} / ${userColor.a})`)).rgb);
        }
        break;
      case 'cieb':
        ciel = getRangeValue('ciel'), ciea = getRangeValue('ciea');
        min = Number(range.min), max = Number(range.max);
        steps = 20;
        for (let i = 0; i <= steps; i++) {
          gradient.push((new Couleur(`lab(${ciel}% ${ciea} ${min + i * (max - min) / steps} / ${userColor.a})`)).rgb);
        }
        break;
      case 'okl':
        okc = getRangeValue('okc'), okh = getRangeValue('okh');
        steps = 20;
        for (let i = 0; i <= steps; i ++) {
          gradient.push((new Couleur(`oklch(${i * 100 / steps}% ${okc} ${okh} / ${userColor.a})`)).rgb)
        }
        break;
      case 'okc':
        okl = getRangeValue('okl'), okh = getRangeValue('okh');
        max = Number(range.max);
        steps = 20;
        for (let i = 0; i <= steps; i++) {
          gradient.push((new Couleur(`oklch(${okl}% ${i * max / steps} ${okh} / ${userColor.a})`)).rgb);
        }
        break;
      case 'okh':
        okl = getRangeValue('okl'), okc = getRangeValue('okc');
        steps = 20;
        for (let i = 0; i <= steps; i++) {
          gradient.push((new Couleur(`oklch(${okl}% ${okc} ${i * 360 / steps} / ${userColor.a})`)).rgb);
        }
        break;
      case 'oka':
        okl = getRangeValue('okl'), okb = getRangeValue('okb');
        min = Number(range.min), max = Number(range.max);
        steps = 20;
        for (let i = 0; i <= steps; i++) {
          gradient.push((new Couleur(`oklab(${okl}% ${min + i * (max - min) / steps} ${okb} / ${userColor.a})`)).rgb);
        }
        break;
      case 'okb':
        okl = getRangeValue('okl'), oka = getRangeValue('oka');
        min = Number(range.min), max = Number(range.max);
        steps = 20;
        for (let i = 0; i <= steps; i++) {
          gradient.push((new Couleur(`oklab(${okl}% ${oka} ${min + i * (max - min) / steps} / ${userColor.a})`)).rgb);
        }
        break;
    }

    range.gradient = gradient.map((c, k) => {
      const color = c instanceof Couleur ? c.rgb : c;
      if (k == 0)                        return `${color} 0, ${color} 7px`;
      else if (k == gradient.length - 1) return `${color} calc(100% - 7px), ${color} 100%`;
      else                               return color;
    }).join(', ');
  }

  return [
    rangeData,
    visibleProps,
  ];
}
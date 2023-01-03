import { resolveColor } from 'colorResolution';
import Couleur from 'colori';



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
      //value = `linear-gradient(to right,\n  ${userColor.map(c => c.name || c.rgb).join(',\n  ')}\n)`;
      value = `[\n  ${userColor.map(c => c.name || c.rgb).join(',\n  ')}\n]`;
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
  const colorPreview = Couleur.blend('white', userColor.toGamut('srgb'));

  // Calculate colors that are the same for both light and dark themes
  const okh = userColor.okh;
  both: {
    cssBoth = ``;
  }

  // Calculate colors for light theme
  light: {
    const okc = Math.min(userColor.okc, 0.1236);
    const bodyColor = new Couleur(`oklch(77% ${okc} ${okh})`);
    metaLight = bodyColor.rgb;
    const sectionColor = new Couleur(`oklch(86.2% ${.6 * okc} ${okh})`);
    const codeColor = new Couleur(`oklch(92.7% ${.3 * okc} ${okh})`);
    cssLight = `
      /* Background colors */
      --body-color: ${bodyColor.hsl};
      --section-color: ${sectionColor.hsl};
      --frame-color: ${codeColor.hsl};
      --code-color: ${codeColor.hsl};
      --tab-hover-color: ${sectionColor.replace('a', .7).hsl};
      --note-color: ${Couleur.blend(sectionColor, bodyColor.replace('a', .3)).hsl};
      /* Text colors */
      --h1-color: ${(new Couleur(`oklch(38.8% ${.6 * okc} ${okh})`)).hsl};
      --h3-color: ${(new Couleur(`oklch(51.2% ${okc} ${okh})`)).hsl};
      --text-color: black;
      --link-color: ${(new Couleur(`oklch(38.3% ${okc} ${okh})`)).hsl};
      --link-underline-color: ${(new Couleur(`oklch(37.3% ${2 * okc} ${okh} / .5)`)).hsl};
      /* Input colors */
      --input-bg-color: ${(new Couleur(`oklch(95.3% ${.3 * okc} ${okh})`)).hsl};
      --input-active-bg-color: ${(new Couleur(`oklch(99% ${.1 * okc} ${okh})`)).hsl};
      --input-placeholder-color: ${(new Couleur(`oklch(34.6% ${.5 * okc} ${okh} / .5)`)).hsl};
      /* Syntax coloring colors */
      --token-number: ${(new Couleur(`oklch(55% 0.1876 ${okh - 90})`)).hsl};
      --token-string: ${(new Couleur(`oklch(55% 0.1876 ${okh + 45})`)).hsl};
      --token-operator: ${(new Couleur(`oklch(55% 0.1876 ${okh - 45})`)).hsl};
      --token-keyword: ${(new Couleur(`oklch(55% 0.1876 ${okh + 135})`)).hsl};
      /* Button colors */
      --button-bg-color: ${(new Couleur(`oklch(90.5% ${.6 * okc} ${okh})`)).hsl};
      --button-active-bg-color: ${(new Couleur(`oklch(97.8% ${.3 * okc} ${okh})`)).hsl};
    `;
  }

  // Calculate colors for dark theme
  dark: {
    const okc = Math.min(userColor.okc, 0.023);
    const bodyColor = new Couleur(`oklch(20.5% ${.6 * okc} ${okh})`);
    metaDark = bodyColor.rgb;
    const sectionColor = new Couleur(`oklch(30.7% ${okc} ${okh})`);
    const codeColor = bodyColor;
    cssDark = `
      /* Background colors */
      --body-color: ${bodyColor.hsl};
      --section-color: ${sectionColor.hsl};
      --frame-color: ${codeColor.hsl};
      --code-color: ${codeColor.hsl};
      --tab-hover-color: ${sectionColor.replace('a', .7).hsl};
      --note-color: ${Couleur.blend(bodyColor, sectionColor.replace('a', .5)).hsl};
      /* Text colors */
      --h1-color: ${(new Couleur(`oklch(82.5% ${okc} ${okh})`)).hsl};
      --h3-color: ${(new Couleur(`oklch(73.7% ${1.7 * okc} ${okh})`)).hsl};
      --text-color: ${(new Couleur(`oklch(91.3% ${.2 * okc} ${okh})`)).hsl};
      --link-color: ${(new Couleur(`oklch(82.3% ${1.7 * okc} ${okh})`)).hsl};
      --link-underline-color: ${(new Couleur(`oklch(81.9% ${2 * 1.7 * okc} ${okh} / .5)`)).hsl};
      /* Input colors */
      --input-bg-color: ${(new Couleur(`oklch(39.3% ${1.5 * okc} ${okh})`)).hsl};
      --input-active-bg-color: ${(new Couleur(`oklch(22.3% ${.6 * okc} ${okh})`)).hsl};
      --input-placeholder-color: ${(new Couleur(`oklch(91.3% ${.5 * okc} ${okh} / .5)`)).hsl};
      /* Syntax coloring colors */
      --token-number: ${(new Couleur(`oklch(80.6% 0.1941 ${okh - 90})`)).hsl};
      --token-string: ${(new Couleur(`oklch(80.6% 0.1941 ${okh + 45})`)).hsl};
      --token-operator: ${(new Couleur(`oklch(80.6% 0.1941 ${okh - 45})`)).hsl};
      --token-keyword: ${(new Couleur(`oklch(80.6% 0.1941 ${okh + 135})`)).hsl};
      /* Button colors */
      --button-bg-color: ${(new Couleur(`oklch(35.2% ${.75 * okc} ${okh})`)).hsl};
      --button-active-bg-color: ${(new Couleur(`oklch(43.6% ${1.5 * okc} ${okh})`)).hsl};
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
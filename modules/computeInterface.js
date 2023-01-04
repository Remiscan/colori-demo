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
    const sectionColor = new Couleur(`oklch(86.2% ${.6 * okc} ${okh})`);
    const frameColor = new Couleur(`oklch(92.7% ${.3 * okc} ${okh})`);
    const h1Color = new Couleur(`oklch(38.8% ${.6 * okc} ${okh})`);
    const h3Color = new Couleur(`oklch(51.2% ${okc} ${okh})`);
    const textColor = new Couleur(`black`);
    const linkColor = new Couleur(`oklch(38.8% ${okc} ${okh})`);
    const linkMutedColor = linkColor.scale('okc', .5).replace('a', .6);
    const inputColor = new Couleur(`oklch(95.3% ${.3 * okc} ${okh})`);
    const inputActiveColor = new Couleur(`oklch(99% ${.1 * okc} ${okh})`);
    const buttonColor = new Couleur(`oklch(90.5% ${.6 * okc} ${okh})`);
    const buttonActiveColor = new Couleur(`oklch(97.8% ${.3 * okc} ${okh})`);

    metaLight = bodyColor.rgb;
    cssLight = `
      /* Background colors */
      --body-color: ${bodyColor.hsl};
      --section-color: ${sectionColor.hsl};
      --frame-color: ${frameColor.hsl};
      /* Text colors */
      --h1-color: ${h1Color.hsl};
      --h3-color: ${h3Color.hsl};
      --text-color: ${textColor.hsl};
      --link-color: ${linkColor.hsl};
      --link-muted-color: ${linkMutedColor.hsl};
      /* Input colors */
      --input-bg-color: ${inputColor.hsl};
      --input-active-bg-color: ${inputActiveColor.hsl};
      /* Button colors */
      --button-bg-color: ${buttonColor.hsl};
      --button-active-bg-color: ${buttonActiveColor.hsl};
    `;
  }

  // Calculate colors for dark theme
  dark: {
    const okc = Math.min(userColor.okc, 0.023);

    const bodyColor = new Couleur(`oklch(20.5% ${.6 * okc} ${okh})`);
    const sectionColor = new Couleur(`oklch(30.7% ${okc} ${okh})`);
    const frameColor = bodyColor;
    const h1Color = new Couleur(`oklch(82.5% ${okc} ${okh})`);
    const h3Color = new Couleur(`oklch(73.7% ${1.7 * okc} ${okh})`);
    const textColor = new Couleur(`oklch(91.3% ${.2 * okc} ${okh})`);
    const linkColor = new Couleur(`oklch(82.5% ${1.7 * okc} ${okh})`);
    const linkMutedColor = linkColor.scale('okc', .5).replace('a', .6);
    const inputColor = new Couleur(`oklch(39.3% ${1.5 * okc} ${okh})`);
    const inputActiveColor = new Couleur(`oklch(22.3% ${.6 * okc} ${okh})`);
    const buttonColor = new Couleur(`oklch(35.2% ${.75 * okc} ${okh})`);
    const buttonActiveColor = new Couleur(`oklch(43.6% ${1.5 * okc} ${okh})`);

    metaDark = bodyColor.rgb;
    cssDark = `
      /* Background colors */
      --body-color: ${bodyColor.hsl};
      --section-color: ${sectionColor.hsl};
      --frame-color: ${frameColor.hsl};
      /* Text colors */
      --h1-color: ${h1Color.hsl};
      --h3-color: ${h3Color.hsl};
      --text-color: ${textColor.hsl};
      --link-color: ${linkColor.hsl};
      --link-muted-color: ${linkMutedColor.hsl};
      /* Input colors */
      --input-bg-color: ${inputColor.hsl};
      --input-active-bg-color: ${inputActiveColor.hsl};
      /* Button colors */
      --button-bg-color: ${buttonColor.hsl};
      --button-active-bg-color: ${buttonActiveColor.hsl};
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
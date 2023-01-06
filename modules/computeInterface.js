import Couleur from 'colori';
import { resolveInput } from 'resolveInput';



let lastInterfaceColorExpr;



/** Compute all color data from the new user input color. */
export function computeInterface({ colorString, placeholder }) {
  let result;
  try {
    result = resolveInput(colorString || placeholder);
    if (!result) throw 'Invalid input';
  } catch (e) {
    try {
      result = resolveInput(placeholder);
      if (!result) throw 'Invalid placeholder input';
    } catch(e) {
      return {};
    }
  }

  let colors = [];
  let responseType = '';
  let value = '';

  if (result instanceof Couleur) {
    responseType = 'Couleur';
    colors = [result];
  }

  else if (Array.isArray(result) && result.length > 0 && result.every(r => r instanceof Couleur)) {
    responseType = 'multiple';
    colors = [...result];
  }

  else if (['number', 'string', 'boolean'].includes(typeof result)) {
    responseType = 'value';
    value = String(result);
  }

  else return {};

  const interfaceColor = colors[0];
  const interfaceColorExpr = interfaceColor?.toString('color-srgb', { precision: 4 });
  const response = {
    type: responseType,
    colors: colors.map(c => c.exactName ?? c.toString('color-srgb', { precision: 4 })),
    colorsClamped: colors.map(c => c.toGamut('srgb').rgb),
    value: value,
  };

  // Don't recalculate styles
  if (lastInterfaceColorExpr && lastInterfaceColorExpr === interfaceColorExpr) {
    return {};
  }
  lastInterfaceColorExpr = interfaceColorExpr;

  if (interfaceColor instanceof Couleur) {
    response.interfaceColorExpr = interfaceColorExpr;
    response.interfaceColorName = interfaceColor.name || '';
    response.interfaceColorHex = interfaceColor.hex;
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
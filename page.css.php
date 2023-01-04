* {
  /* Empêche le bleu moche quand on clique sur un truc sous chrome Android */
  -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
  /* Empêche le font scaling auto moche sous chrome Android */
  max-height: 1000000px;
}
/*remiscan-logo::part(link):focus,
*:focus { outline: 2px solid var(--link-color); }
remiscan-logo::part(link):focus:not(:focus-visible),
*:focus:not(:focus-visible) { outline-style: none; }
::-moz-focus-inner { border: 0; }*/

:root { --tap-safe-size: 44px; }
[data-tappable] { position: relative; z-index: 1; }
[data-tappable]:not([data-tappable="after"])::before,
[data-tappable][data-tappable="after"]::after {
  content: '';
  display: block;
  width: 100%;
  min-width: 44px;
  height: 100%;
  min-height: 44px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: -1;
}






/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! TYPOGRAPHIE !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

/* lato-regular - latin */
@font-face {
  font-family: 'Lato';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: local(''),
       url('/colori/demo/ext/lato-v17-latin-regular.woff2') format('woff2');
}

/* fira-code-regular */
@font-face {
  font-family: 'Fira Code';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: local(''),
       url('/colori/demo/ext/FiraCode-Regular.woff2') format('woff2');
}

html {
  font-family: system-ui, 'Roboto', Helvetica, Arial, sans-serif;
  --min-font: 1.0; /* rem */
  --max-font: 1.125; /* rem */
  font-size: calc(1rem * var(--min-font));
  --mod: 1.250;
}
@media screen and (min-width: 1100px) {
  html {
    font-size: calc(1rem * var(--max-font));
    --mod: 1.333;
  }
}
h1, h2, h3, h4, h5, h6 {
  display: inline;
  margin: 0;
  font-weight: 400;
}
h1, .h1 {
  font-size: calc(var(--mod) * var(--mod) * var(--mod) * var(--mod) * 1rem);
}
h2 {
  font-size: calc(var(--mod) * var(--mod) * var(--mod) * 1rem);
}
h3, .h2 {
  font-size: calc(var(--mod) * var(--mod) * 1rem);
}
h4, .h3, h5, .h4 {
  font-size: calc(var(--mod) * 1rem);
}
/*h5, .h5 {
  font-size: 1rem;
  line-height: 1.6em;
}*/
h6, .h6 {
  font-size: calc(1rem / var(--mod));
}





/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! ÉLÉMENTS !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

/*
 *Boutons
 */

button {
  -webkit-appearance: none;
  appearance: none;
  font-family: inherit;
  font-weight: 600;
  color: var(--h1-color);
  line-height: inherit;
  margin: 0;
  padding: .4em 1.2em;
  border: 1px solid var(--button-border-color);
  border-radius: var(--border-radius);
  background: var(--button-bg-color);
  white-space: nowrap;
  text-decoration: none;
}

button:hover,
button:focus,
button:active {
  border-color: var(--button-hover-border-color);
  background: var(--button-hover-bg-color);
}

button:active {
  background: var(--button-active-bg-color);
}



/*
 *Input radio
 */

input[type="radio"] {
  height: 0;
  width: 0;
  opacity: 0;
  margin: 0;
  pointer-events: none;
  position: absolute;
}

input[type="radio"] + label {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: max(var(--section-padding), 1rem);
  padding: var(--section-padding) max(var(--section-padding), 1rem);
  min-width: var(--tap-safe-size);
  min-height: var(--tap-safe-size);
  box-sizing: border-box;
}

input[type="radio"] + label:hover,
input[type="radio"]:checked + label {
  background: var(--button-hover-bg-color);
}

input[type="radio"]:active + label {
  background: var(--button-active-bg-color);
}

input[type="radio"] + label::before {
  content: '';
  display: block;
  --size: 1rem;
  width: var(--size);
  height: var(--size);
  border-radius: 50%;
  box-shadow: inset 0 0 0 2px var(--h3-color);
  place-self: center;
  grid-row: 1;
  grid-column: 1;
  /* Rotation forces sub-pixel rendering to make perfect circle,
     and 3D forces anti-aliasing */
  transform: rotate3D(0, 0, 1, 360deg);
}

input[type="radio"]:checked + label::before {
  background-color: var(--h1-color);
  box-shadow: inset 0 0 0 .1rem var(--h1-color),
              inset 0 0 0 .2rem var(--input-bg-color);
}

input[type="radio"] + label>span {
  margin: auto 0;
}

input[type="radio"]:focus + label {
  outline: 5px auto Highlight;
  outline: 5px auto -webkit-focus-ring-color;
}



/*
 *Tabs
 */

 [role="tablist"] {
  display: flex;
  gap: 3px;
  margin: 0 0 0 auto;
  padding: 3px;
  width: fit-content;
  height: fit-content;
  border: none;
  box-shadow: none;
  --height: 1.8rem;
  background-color: var(--body-color);
  border-radius: var(--border-radius);
}

[is="tab-list"]:not([role="tablist"]) {
  display: none;
}

[role="tab"] {
  -webkit-appearance: none;
  appearance: none;
  background: none;
  border: none;
  font: inherit;
  margin: 0;

  display: grid;
  grid-template: unset;
  place-items: center;
  box-sizing: border-box;
  height: var(--height);
  font-size: .8rem;
  font-weight: 400;
  color: var(--h1-color);
  padding: 0 .6em;
  border-radius: calc(.85 * var(--border-radius));
  cursor: default;
  min-width: var(--tap-safe-size);
}

[role="tab"]::before {
  background: none;
  box-shadow: none;
  width: 100%;
  height: 100%;
  grid-area: unset;
}

[role="tab"]:hover {
  background-color: var(--button-hover-bg-color);
}

[role="tab"]:active {
  background-color: var(--button-active-bg-color);
}

[role="tab"][aria-selected="true"] {
  background-color: var(--button-bg-color);
  font-weight: 600;
  color: var(--h1-color);
}



/*
 * Titres
 */

/* H1 - Titres de sections */

h1, h2 {
  display: block;
  color: var(--h1-color);
  font-family: 'Lato';
  position: absolute;
  z-index: -1;
  top: 0;
  left: 0;
  transform: translate(var(--section-padding), calc(-100% + 17.71%));
  --shadow-color: var(--body-color);
  text-shadow: 0 0 0 var(--shadow-color), 1px 1px 0 var(--shadow-color), 2px 2px 0 var(--shadow-color), 3px 3px 0 var(--shadow-color), 4px 4px 0 var(--shadow-color), 5px 5px 0 var(--shadow-color), 6px 6px 0 var(--shadow-color), 7px 7px 0 var(--shadow-color), 8px 8px 0 var(--shadow-color), 9px 9px 0 var(--shadow-color), 10px 10px 0 var(--shadow-color), 11px 11px 0 var(--shadow-color), 12px 12px 0 var(--shadow-color), 13px 13px 0 var(--shadow-color), 14px 14px 0 var(--shadow-color), 15px 15px 0 var(--shadow-color), 16px 16px 0 var(--shadow-color), 17px 17px 0 var(--shadow-color), 18px 18px 0 var(--shadow-color), 19px 19px 0 var(--shadow-color), 20px 20px 0 var(--shadow-color), 21px 21px 0 var(--shadow-color), 22px 22px 0 var(--shadow-color), 23px 23px 0 var(--shadow-color), 24px 24px 0 var(--shadow-color), 25px 25px 0 var(--shadow-color), 26px 26px 0 var(--shadow-color), 27px 27px 0 var(--shadow-color), 28px 28px 0 var(--shadow-color), 29px 29px 0 var(--shadow-color), 30px 30px 0 var(--shadow-color), 31px 31px 0 var(--shadow-color), 32px 32px 0 var(--shadow-color), 33px 33px 0 var(--shadow-color), 34px 34px 0 var(--shadow-color), 35px 35px 0 var(--shadow-color), 36px 36px 0 var(--shadow-color), 37px 37px 0 var(--shadow-color), 38px 38px 0 var(--shadow-color), 39px 39px 0 var(--shadow-color), 40px 40px 0 var(--shadow-color);
  clip-path: polygon(0 0, 150% 0, 150% 100%, 0 100%);
}

section h2 {
  --shadow-color: var(--section-color);
}


/* H2 - Titres des sous-parties de sections */

h3 {
  color: var(--h3-color);
  font-family: 'Lato';
  display: block;
  position: relative;
}



/*
 * Liens
 */

a:link,
a:visited {
  color: var(--link-color);
  font-weight: 600;
  padding: 0 .1em;
  text-decoration: underline;
  text-decoration-color: var(--link-underline-color);
}

a:hover,
a:focus,
a:active {
  text-decoration-color: var(--link-color);
  border-radius: calc(.33 * var(--border-radius));
}

a:hover,
a:focus {
  background-color: var(--button-hover-bg-color);
}

a:active {
  background-color: var(--button-active-bg-color);
}



/*
 * Autres
 */

strong, em, th {
  color: var(--text-strong-color);
  font-weight: 600;
}





/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! STRUCTURE DE LA PAGE !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

/*
 * Layout
 */

html {
  width: 100vw;
  height: 100%;
  height: 100lvh;
  overflow-x: hidden;
  overflow-y: auto;
  background-color: var(--body-color);
  --easing-standard: cubic-bezier(0.4, 0.0, 0.2, 1);
  --easing-decelerate: cubic-bezier(0.0, 0.0, 0.2, 1);
  --easing-accelerate: cubic-bezier(0.4, 0.0, 1, 1);
  --h-diff: -1;
  --text-strong-color: var(--h3-color);
  --section-padding: .9rem;
  --border-radius: .6rem;

  --echiquier-transparence: linear-gradient(45deg, rgba(0, 0, 0, .1) 25%, transparent 25%, transparent 75%, rgba(0, 0, 0, .1) 75%),
  linear-gradient(45deg, rgba(0, 0, 0, .1) 25%, transparent 25%, transparent 75%, rgba(0, 0, 0, .1) 75%),
  linear-gradient(to right, #ddd 0% 100%);

  --bg-color: var(--section-color);
  --link-underline-color: var(--link-muted-color);
  --input-placeholder-color: var(--link-muted-color);
  --button-border-color: var(--h3-color);
  --button-hover-border-color: var(--h1-color);
  --button-hover-bg-color: var(--input-bg-color);
}

/*<?php themeSheetStart(); ?>*/
html[data-theme="light"] {
  color-scheme: light;
  --error-color: darkred;

  --logo-gradient: repeating-linear-gradient(to right,
  hsl(262.38, 49.25%, 65.39%) 0%,
  hsl(277.6, 42.76%, 62.44%) 2.8571428571429%,
  hsl(295.08, 35.4%, 59.13%) 5.7142857142857%,
  hsl(313.7, 39.14%, 59.23%) 8.5714285714286%,
  hsl(327.89, 45.46%, 60.32%) 11.428571428571%,
  hsl(339.84, 50.19%, 61.22%) 14.285714285714%,
  hsl(339.84, 50.19%, 61.22%) 17.142857142857%,
  hsl(351.07, 53.01%, 61.96%) 20%,
  hsl(2.62, 54.75%, 61.63%) 22.857142857143%,
  hsl(12.71, 57.82%, 57.77%) 25.714285714286%,
  hsl(21.31, 58.71%, 53.42%) 28.571428571429%,
  hsl(29.14, 61.28%, 48.59%) 31.428571428571%,
  hsl(29.14, 61.28%, 48.59%) 34.285714285714%,
  hsl(36.53, 72.92%, 43.38%) 37.142857142857%,
  hsl(43.51, 84.14%, 38.4%) 40%,
  hsl(49.97, 81.84%, 36%) 42.857142857143%,
  hsl(58.51, 64.88%, 35.98%) 45.714285714286%,
  hsl(72.16, 50.34%, 40.23%) 48.571428571429%,
  hsl(72.16, 50.34%, 40.23%) 51.428571428571%,
  hsl(91.32, 38.81%, 45%) 54.285714285714%,
  hsl(120.3, 29.35%, 49.52%) 57.142857142857%,
  hsl(147.44, 46.63%, 44.45%) 60%,
  hsl(164.73, 88.18%, 34.97%) 62.857142857143%,
  hsl(172.15, 100%, 32.97%) 65.714285714286%,
  hsl(172.15, 100%, 32.97%) 68.571428571429%,
  hsl(178.67, 100%, 32.78%) 71.428571428571%,
  hsl(184.82, 100%, 35.17%) 74.285714285714%,
  hsl(189.85, 100%, 37.91%) 77.142857142857%,
  hsl(193.97, 100%, 40.19%) 80%,
  hsl(202.43, 66.72%, 51.51%) 82.857142857143%,
  hsl(202.43, 66.72%, 51.51%) 85.714285714286%,
  hsl(211.13, 66.63%, 58.51%) 88.571428571429%,
  hsl(220.77, 64.73%, 63.6%) 91.428571428571%,
  hsl(232.49, 59.69%, 67.39%) 94.285714285714%,
  hsl(247.56, 54.1%, 67.88%) 97.142857142857%,
  hsl(262.38, 49.25%, 65.39%) 100%
  );
}

html[data-theme="dark"] {
  color-scheme: dark;
  --error-color: lightpink;

  --logo-gradient: repeating-linear-gradient(to right,
  hsl(294.66, 100%, 92.87%) 0%,
  hsl(300, 100%, 91.8%) 2.8571428571429%,
  hsl(300, 100%, 90.89%) 5.7142857142857%,
  hsl(300, 100%, 90.17%) 8.5714285714286%,
  hsl(301.24, 100%, 89.68%) 11.428571428571%,
  hsl(322.52, 100%, 89.46%) 14.285714285714%,
  hsl(322.52, 100%, 89.46%) 17.142857142857%,
  hsl(344.66, 100%, 89.53%) 20%,
  hsl(7.74, 100%, 88.4%) 22.857142857143%,
  hsl(22.81, 100%, 84.77%) 25.714285714286%,
  hsl(32.43, 100%, 81.48%) 28.571428571429%,
  hsl(39.24, 100%, 78.72%) 31.428571428571%,
  hsl(39.24, 100%, 78.72%) 34.285714285714%,
  hsl(44.39, 100%, 76.71%) 37.142857142857%,
  hsl(48.48, 100%, 75.7%) 40%,
  hsl(51.93, 100%, 75.82%) 42.857142857143%,
  hsl(59.25, 86.76%, 75.46%) 45.714285714286%,
  hsl(74.45, 93.98%, 78.68%) 48.571428571429%,
  hsl(74.45, 93.98%, 78.68%) 51.428571428571%,
  hsl(92.53, 100%, 82.3%) 54.285714285714%,
  hsl(118.68, 100%, 85.78%) 57.142857142857%,
  hsl(145.66, 100%, 81.78%) 60%,
  hsl(162.49, 100%, 77.72%) 62.857142857143%,
  hsl(173.93, 100%, 74.32%) 65.714285714286%,
  hsl(173.93, 100%, 74.32%) 68.571428571429%,
  hsl(180, 100%, 72.16%) 71.428571428571%,
  hsl(180, 100%, 71.8%) 74.285714285714%,
  hsl(180, 100%, 73.29%) 77.142857142857%,
  hsl(180, 100%, 76.2%) 80%,
  hsl(182.79, 100%, 79.9%) 82.857142857143%,
  hsl(182.79, 100%, 79.9%) 85.714285714286%,
  hsl(187.96, 100%, 83.94%) 88.571428571429%,
  hsl(197.06, 100%, 88.05%) 91.428571428571%,
  hsl(215.39, 100%, 92.06%) 94.285714285714%,
  hsl(258.14, 100%, 94.06%) 97.142857142857%,
  hsl(294.66, 100%, 92.87%) 100% 
  );
}
/*<?php themeSheetEnd(closeComment: true); ?>*/

body { /* Desktop-like */
  display: grid;
  box-sizing: border-box;
  grid-template-columns: 1fr var(--section-padding) min(100% - 2 * var(--section-padding), 42rem) var(--section-padding) 1fr;
  grid-template-rows: repeat(3, auto) 1fr auto;
  row-gap: calc(2 * var(--section-padding));
  width: 100%;
  min-height: 100%;
  margin: 0;
  padding: var(--section-padding) 0;
  margin-right: env(safe-area-inset-right, 0);
  margin-left: env(safe-area-inset-left, 0);
  background-color: var(--body-color);
  color: var(--text-color);
}

@media (max-width: 672px) {
  html {
    --section-padding: 8px;
  }
}



/*
 * Header
 */

header {
  grid-row: 1 / 2;
  grid-column: 2 / 5;
  display: grid;
  grid-template-columns: [icon-start] auto [icon-end logo-start] auto [logo-end] 1fr [options-start] auto [options-end];
  gap: var(--section-padding);
  justify-content: center;
  align-items: center;
  position: relative;
  background: var(--section-color);
  padding: 0 var(--section-padding);
  padding-left: 0;
  margin-top: env(safe-area-inset-top, 0);
  min-height: var(--tap-safe-size);
  font-size: .9rem;
}

header>h1 {
  grid-column: logo-start / logo-end;
  grid-row: 1 / 2;
  transform: none;
  position: relative;
  /*transform: translateY(17.7%);*/
  z-index: 0;
  font-size: calc(var(--mod) * var(--mod) * 1rem);
  /*color: var(--h3-color);*/
  --shadow-color: var(--body-color);
  /*text-shadow: none;*/
  color: var(--h1-color);
  height: calc(100% + 2px); /* fixes shadow leaving half a pixel */
  margin: -1px 0;           /* not reached at the bottom of the header */
  display: flex;
  align-items: center;
}


/* Icon */

.app-icon {
  width: 2.4rem;
  height: 2rem;
  margin: 0;
  /*clip-path: circle(48%);*/
  /*filter: hue-rotate(calc(var(--icon-hue-difference) * 1deg)) saturate(calc(var(--icon-saturation-ratio) * 100%));*/
  display: grid;
  place-items: center;
  overflow: hidden;
  box-sizing: border-box;
  border: 2px solid var(--body-color);
  border-radius: var(--border-radius);
  background-color: var(--icon-shadow-color);
  --icon-bg-color: var(--section-color);
  --icon-shadow-color: var(--body-color);
  --icon-hash-color: var(--h3-color);
}

header .app-icon {
  display: none;
}

.app-icon > svg {
  width: calc(100% + 2px);
  height: calc(100% + 2px);
  margin: -1px;
  overflow: visible;
}

.app-icon > svg .bg {
  fill: var(--icon-bg-color);
}

.app-icon > svg .ombres > rect {
  fill: var(--icon-shadow-color);
}

.app-icon > svg .croisillon > rect {
  fill: var(--icon-hash-color);
}


/* Theme selector */

theme-selector {
  --size: 1.8em;
  width: var(--size);
  height: var(--size);
  display: grid;
  --margin-right: 0;
  margin-right: var(--margin-right);
  --primary-color: var(--h1-color);
  --secondary-color: var(--h1-color);
}

color-picker::part(selector),
theme-selector>.selector {
  min-width: 11rem;
  right: calc(-1 * var(--section-padding));
  background-color: var(--section-color);
  box-shadow: 0 1px .2rem 1px var(--body-color);
  border: none;
  border-radius: var(--border-radius);
  overflow: hidden;
  z-index: 100;
  transform: translateY(-.2rem);
  transition: opacity .2s ease,
              transform .2s ease;
}

theme-selector > .selector {
  top: calc(1.8rem + .5 * (var(--tap-safe-size) - 1.8rem) + 2px); /* theme-selector + .5 * (header - theme-selector) + space */
}

color-picker[open]::part(selector),
theme-selector[open="true"]>.selector {
  transform: translateY(0);
}

color-picker::part(select-label),
theme-selector .selector-title {
  color: var(--h3-color);
  padding: var(--section-padding);
  place-self: center;
}

theme-selector .selector-title-text {
  border: none;
}

theme-selector input[type="radio"]:focus + label {
  outline: 5px auto Highlight;
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -5px;
}

theme-selector input[type="radio"]:focus:not(:focus-visible) + label {
  outline: none;
}


/* Boutons de langue */

.groupe-langages {
  grid-column: options-start / options-end;
  grid-row: 1;
  justify-self: end;
  align-self: end;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: var(--section-padding);
  height: 100%;
}

.bouton-langage {
  width: fit-content;
  position: relative;
}

.bouton-langage[disabled] {
  background-color: transparent;
  text-decoration: none;
  opacity: .5;
  cursor: auto;
  pointer-events: none;
  display: none;
}


/* Lien vers GitHub */

.lien-github {
  grid-column: github-start / github-end;
  grid-row: 1;
  justify-self: start;
  align-self: center;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: inherit;
}

.lien-github svg {
  fill: var(--h1-color);
}

span.github-string {
  width: auto;
  text-align: right;
}

.github-cat {
  width: 1.1rem;
  height: 1.1rem;
  margin-right: .3em;
}


@media (max-width: 30rem) {
  span.github-string {
    display: none;
  }
}



/*
 * Sections (démo & présentation)
 */

header,
footer {
  grid-column: 3;
  z-index: 2;
}

header,
footer {
  background: var(--bg-color);
}

header,
section,
footer {
  border-radius: var(--border-radius);
  grid-column: 3;
}

section {
  background-color: var(--section-color);
  padding: var(--section-padding);
  padding-top: max(1.5 * var(--section-padding), .8rem);
  margin-top: calc(0.71625 * var(--mod) * var(--mod) * var(--mod) * 1rem);
  position: relative;
  z-index: 1;
}

section.no-title {
  margin-top: 0;
  padding-top: var(--section-padding);
}

.subsection {
  display: grid;
  gap: var(--section-padding);
}

.subsection + .subsection {
  margin-top: max(1.5 * var(--section-padding), 1rem);
}

p.warning-js {
  display: none;
}



/*
 * Footer
 */

footer {
  grid-row: -2;
  position: relative;
  padding: 0 var(--section-padding);
  margin-bottom: env(safe-area-inset-bottom, 0);
  margin-top: calc(-2.5 * var(--section-padding));
  padding: min(8px, var(--section-padding));
  display: grid;
  place-items: center;
  --icon-size: 2.4rem;
  grid-template-columns: [icon-start] var(--icon-size) [icon-end] 1fr [link-start] auto [link-end] 1fr var(--icon-size);
  gap: var(--section-padding);
}

footer .app-icon {
  width: 100%;
  height: auto;
  aspect-ratio: 1 / 1;
}

footer .made-by {
  grid-column: link-start / link-end;
  display: flex;
  align-items: center;
}

remiscan-logo {
  --width: 4.5em;
  width: var(--width);
  height: calc(0.5 * var(--width));
}

remiscan-logo::part(link) {
  --text-color: var(--link-color);
  --text-gradient: var(--logo-gradient);
  border-radius: calc(.33 * var(--border-radius));
}

remiscan-logo::part(link):hover,
remiscan-logo::part(link):focus {
  background-color: var(--button-hover-bg-color);
}

remiscan-logo::part(link):active {
  background-color: var(--button-active-bg-color);
}





/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! SECTION DÉMO !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

/*
 * Zone de saisie manuelle
 */

#saisie {
  grid-template-columns: auto 1fr;
}

label[for=entree] {
  grid-row: 1;
  grid-column: 1;
  width: fit-content;
}

color-picker::part(select),
color-picker::part(input-number),
input[type="text"] {
  grid-row: auto;
  grid-column: 1 / 3;
  height: 100%;
  min-height: var(--tap-safe-size);
  border: none;
  box-sizing: border-box;
  padding: .4em .6em;
  font-family: 'Fira Code';
  color: var(--text-color);
  background-color: var(--input-bg-color);
  border-radius: var(--border-radius);
  box-shadow: 0 0 0 1px var(--body-color);
  outline-offset: 2px;
}

input[type="text"] {
  grid-row: 2;
  grid-column: 1 / 3;
  width: 100%;
  height: 100%;
  min-height: calc(2.4rem + 8px);
  padding-right: calc(.6em + 2.5rem);
}

color-picker::part(select):hover,
color-picker::part(input-number):hover,
input[type="text"]:hover {
  box-shadow: 0 0 0 2px var(--h3-color);
}

color-picker::part(select):active,
color-picker::part(select):focus,
color-picker::part(input-number):active,
color-picker::part(input-number):focus,
input[type="text"]:active,
input[type="text"]:focus {
  outline: none;
  background-color: var(--input-active-bg-color);
  box-shadow: 0 0 0 2px var(--h3-color);
}

::placeholder {
  color: var(--input-placeholder-color);
}

color-picker {
  grid-row: 2;
  grid-column: 2;
  justify-self: end;
  right: 0;
  --size: 2.4rem;
  --range-height: 15rem;
  --range-border-width: 2px;
  --range-border-radius: var(--border-radius);
}

@media (max-width: 672px) {
  color-picker {
    grid-column: 1;
  }
}

color-picker::part(selector) {
  margin: auto;
  z-index: unset;
  transform: unset;
  transition: unset;
  max-width: 100vw;
  overflow: auto;
  min-width: unset;
  right: 0;
}

color-picker::part(property-name) {
  font-size: .9rem;
  font-weight: 600;
  color: var(--h3-color);
}

color-picker::part(select),
color-picker::part(input-number) {
  font-size: .9rem;
  min-width: 5ch;
  color: var(--text-color);
}

color-picker::part(button) {
  -webkit-appearance: none;
  appearance: none;
  margin: 0;
  padding: 4px;
  background-color: var(--button-bg-color);
  border: none;
  box-shadow: 0 0 0 1px var(--button-border-color);
  border-radius: var(--border-radius);
  white-space: nowrap;
  text-decoration: none;
  overflow: hidden;
}

color-picker::part(button):is(:hover, :focus) {
  background-color: var(--button-hover-bg-color);
  --button-border-color: var(--button-hover-border-color);
}

color-picker::part(button):is(:active) {
  background-color: var(--button-active-bg-color);
  --button-border-color: var(--button-hover-border-color);
}

color-picker::part(color-preview) {
  border-radius: calc(.83 * var(--border-radius));
}



/*
 * Boutons d'exemples
 */

.exemples-saisie {
  display: flex;
  flex-direction: row;
  flex-wrap: nowrap;
  gap: calc(.5 * var(--section-padding));
  align-items: flex-end;
  overflow-x: auto;
  overflow-y: visible;
  scrollbar-width: thin;
}

.exemples-saisie>span {
  opacity: .7;
  font-size: .9rem;
  padding: .2em 0;
  border: 1px solid transparent;
  white-space: nowrap;
}

.exemples-valeurs {
  grid-row: 1;
  grid-column: 2;
}

.exemples-valeurs,
.exemples-fonctions {
  padding: 2px 2px 2px 0; /* pour éviter de couper le bord et l'outline de focus */
}

.instructions-exemples-fonctions,
.exemples-fonctions {
  grid-column: 1 / 3;
  margin: 0;
}

.instructions-exemples-fonctions {
  font-size: .9em;
}

.exemple {
  font-family: 'Fira Code';
  font-size: .8rem;
  font-weight: normal;
  color: inherit;
  padding: .4em .6em;
  border-radius: calc(.5 * var(--border-radius));
}

.exemple kbd {
  font-family: inherit;
}

[data-action="more-examples"] {
  padding-inline: 1.5ch;
}

[data-action="more-examples"]::before {
  content: '+';
}

[data-action="more-examples"].open::before {
  content: '-';
}



/*
 * Sélecteurs de couleurs
 */

#resultats {
  --preview-border-width: 4px;
}



/*
 * Résultats
 */

#resultats {
  grid-template-columns: auto 1fr;
}

#resultats [role="tablist"] {
  grid-column: 2;
  margin: auto;
  margin-left: 0;
}

#resultats h3 {
  grid-column: 1;
}

.donnees {
  grid-column: 1 / -1;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: stretch;
  flex-wrap: wrap;
  gap: calc(.5 * var(--section-padding));
}

@media (max-width: 672px) {
  .donnees:is(#results-named-formats, #results-color-spaces) {
    flex-direction: column;
    align-items: start;
  }
}

.donnees .format,
color-swatch {
  background-color: var(--frame-color);
  gap: calc(2 * var(--preview-border-width));
  padding: var(--preview-border-width);
  border-radius: var(--border-radius);
  --color-preview-width: 2em;
  --warning-color: var(--error-color);
}

color-swatch > .color-swatch-preview {
  border-radius: calc(.66 * var(--border-radius));
  box-shadow: inset 0 0 0 2px currentColor;
}

color-swatch > button {
  padding: var(--preview-border-width);
  border: none;
  border-radius: calc(.66 * var(--border-radius));
}

color-swatch > button:not(:hover, :focus, :active) {
  background: none;
}

.format {
  align-self: center;
  max-width: calc(100% - .6rem);
}

.format>pre[class*="language-"] {
  margin: 0;
}

.format.gradient,
.format.couleur {
  display: flex;
  flex-basis: 100%;
  height: 3rem;
  --border-size: var(--preview-border-width);
  align-self: stretch;
  background-color: var(--frame-color);
  border-radius: var(--border-radius);
  position: relative;
  font-size: 0;
}

.format.couleur {
  flex-basis: 3rem;
  height: auto;
}

.format.couleur::before,
.format.gradient::before {
  content: '';
  display: block;
  width: calc(100% - 2 * var(--border-size));
  height: calc(100% - 2 * var(--border-size));
  top: var(--border-size);
  left: var(--border-size);
  background-image: var(--echiquier-transparence);
  background-size: 16px 16px;
  background-position: 0 0, 8px 8px;
  border-radius: calc(.82 * var(--border-radius));
  position: absolute;
}

.format.couleur::after,
.format.gradient::after {
  content: '';
  display: block;
  width: calc(100% - 2 * var(--border-size));
  height: calc(100% - 2 * var(--border-size));
  top: var(--border-size);
  left: var(--border-size);
  background-color: var(--user-color);
  border-radius: calc(.66 * var(--border-radius));
  position: absolute;
}

.format.gradient::after {
  background-color: transparent;
  background-image: var(--gradient);
}

#resultats:not([data-type*="value"]):not([data-type*="gradient"]) .donnees#results-values,
#resultats:not([data-type*="value"]) .format.valeur,
#resultats:not([data-type*="gradient"]) .format.gradient,
#resultats[data-type*="value"] [role="tablist"],
#resultats[data-type*="value"] .donnees#results-named-formats,
#resultats[data-type*="value"] .donnees#results-color-spaces {
  display: none;
}

.format.valeur code {
  white-space: normal;
}

[data-type*="value"][data-type*="gradient"] .format.valeur code {
  white-space: pre-wrap;
}
[data-type*="value"][data-type*="whatToBlend"] .format.gradient {
  height: 4rem;
}
[data-type*="value"][data-type*="whatToBlend"] .format.gradient::after {
  background-image: var(--gradient),
                    linear-gradient(to bottom, transparent 0 50%, var(--bg) 50% 100%);
  background-position: top center, bottom center;
  background-size: 100% 100%;
  background-repeat-y: no-repeat;
  animation: moveBlend var(--easing-standard) 3s infinite alternate;
}

@keyframes moveBlend {
  0%   { background-position: top center, bottom center; }
  10%  { background-position: top center, bottom center; }
  90%  { background-position: center calc(-4rem + var(--border-size)), bottom center; }
  100% { background-position: center calc(-4rem + var(--border-size)), bottom center; }
}

@media (prefers-reduced-motion: reduce) {
  [data-type*="value"][data-type*="whatToBlend"]>.format.gradient::after {
    animation: none;
  }
}



@media (max-width: 672px) {
  #saisie {
    grid-template-columns: 1fr;
    column-gap: 0;
  }

  #entree {
    grid-row: 2;
  }

  label[for=entree] {
    grid-column: 1;
  }

  .exemples-valeurs {
    grid-row: auto;
    grid-column: 1;
  }

  .only-pc {
    display: none;
  }
}

@media (min-width: 673px) {
  .only-mobile {
    display: none;
  }
}





/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! SECTION DOCUMENTATION !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

/*
 * Espacement des paragraphes
 */

p {
  margin: 0 0 1em 0;
}

p:last-child,
#intro > p {
  margin-bottom: 0;
}

p, li {
  line-height: 1.6em;
}



/*
 * Blocs de code
 */

/* Code blocks */
pre[class*="language-"] {
	padding: .4em .5em;
	margin: 1em 0;
	overflow: auto;
	border: none;
	border-radius: calc(.83 * var(--border-radius));
	box-shadow: none;
}

/* Inline code */
:not(pre) > code[class*="language-"] {
	padding: .05em .2em;
	border-radius: calc(.5 * var(--border-radius));
	border: none;
	box-shadow: none;
	white-space: nowrap;
	margin: 0 1px;
}

:not(pre) > code[class*="language-"] {
  white-space: normal;
}


/* Sections not to display based on language and prog language */
/* (double attribute to increase specificity) */
html[lang="fr"] :not([data-lang])[lang="en"][lang="en"],
html[lang="en"] :not([data-lang])[lang="fr"][lang="fr"],
html[data-prog-language="js"] [data-prog-language="php"],
html[data-prog-language="php"] [data-prog-language="js"] {
  display: none;
}


/* Éléments cachés */
.off { display: none; }
[hidden] { display: none !important; }

tab-label:not(:defined),
color-swatch:not(:defined) {
  display: none;
}


/* If animations disabled */
@media (prefers-reduced-motion: reduce) {
  * {
    transition: none !important;
    animation: none !important;
  }
}
* {
  /* Empêche le bleu moche quand on clique sur un truc sous chrome Android */
  -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
  /* Empêche le font scaling auto moche sous chrome Android */
  max-height: 1000000px;
}
remiscan-logo::part(link):focus,
*:focus { outline: 2px solid var(--link-color); }
remiscan-logo::part(link):focus:not(:focus-visible),
*:focus:not(:focus-visible) { outline-style: none; }
::-moz-focus-inner { border: 0; }

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
       url('/_common/fonts/lato/lato-v17-latin-regular.woff2') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
       url('/_common/fonts/lato/lato-v17-latin-regular.woff') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
}

/* fira-code-regular */
@font-face {
  font-family: 'Fira Code';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: local(''),
       url('/_common/fonts/fira-code/FiraCode-Regular.woff2') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
       url('/_common/fonts/fira-code/FiraCode-Regular.woff') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
}

html {
  font-family: system-ui, 'Roboto', Helvetica, Arial, sans-serif;
  --min-font: 1.0; /* rem */
  --max-font: 1.1; /* rem */
  --min-screen: 60; /* 960px si 1rem = 16px */
  --max-screen: 80; /* 1280px si 1rem = 320px */
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
  border-radius: .6rem;
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

button:focus-visible {
  border-radius: 0;
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
  gap: .6rem;
  padding: .6rem .6rem;
  min-width: var(--tap-safe-size);
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
  outline: 2px solid var(--link-color);
}
input[type="radio"]:focus:not(:focus-visible) + label {
  outline: none;
}



/*
 *Tabs
 */

[role="tablist"] {
  display: flex;
  align-items: flex-end;
  gap: 2px;
  margin: 0;
  padding: 0;
  border: none;
  box-shadow: none;
  position: absolute;
  --height: 1.8rem;
  top: calc(-1 * var(--height));
  right: var(--section-padding);
}

.choix-format input[type="radio"][name="choix-format"] + label,
input[type="radio"][role="tab"] + label {
  border: none;
  display: grid;
  grid-template: unset;
  place-items: center;
  box-sizing: border-box;
  --decalage: 0rem;
  height: var(--height);
  font-size: .8rem;
  font-weight: 600;
  color: var(--h1-color);
  padding: 0 .6rem;
  border-radius: .6rem .6rem 0 0;
  border: 1px solid var(--tab-hover-color);
  border-bottom: 0;
  cursor: default;
  min-width: var(--tap-safe-size);
  position: relative;
  bottom: calc(-1 * var(--decalage));
}

.choix-format input[type="radio"][name="choix-format"] + label::before,
input[type="radio"][role="tab"] + label::before {
  background: none;
  box-shadow: none;
  width: 100%;
  height: 100%;
  border-radius: 0;
  grid-area: unset;
}

.choix-format input[type="radio"][name="choix-format"] + label:hover,
input[type="radio"][role="tab"] + label:hover {
  background-color: var(--tab-hover-color);
}

.choix-format input[type="radio"][name="choix-format"]:focus + label,
input[type="radio"][role="tab"]:focus + label {
  outline: 2px solid var(--link-color);
  border-radius: 0;
}

.choix-format input[type="radio"][name="choix-format"]:focus:not(:focus-visible) + label,
input[type="radio"][role="tab"]:focus:not(:focus-visible) + label {
  outline-style: none;
  border-radius: .6rem .6rem 0 0;
}

.choix-format input[type="radio"][name="choix-format"]:active + label,
input[type="radio"][role="tab"]:active + label {
  background-color: var(--tab-hover-color);
  box-shadow: -1px -.05rem 0 0 var(--body-color), 1px -.05rem 0 0 var(--body-color);
  --decalage: .05rem;
}

.choix-format input[type="radio"][name="choix-format"]:checked + label,
input[type="radio"][role="tab"]:checked + label {
  background-color: var(--section-color);
  color: var(--h1-color);
  box-shadow: -1px 0 0 0 var(--body-color), 1px 0 0 0 var(--body-color);
  --decalage: 0rem;
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
  user-select: none;
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
 * Séparateur
 */

hr {
  border: none;
  border-radius: 6px;
  background-color: var(--h3-color);
  height: 4px;
  margin: 3.6rem 0;
  opacity: .15;
}



/*
 * Liens
 */

a:link,
a:visited {
  color: var(--link-color);
  font-weight: 600;
  text-decoration: none;
  padding: 0 .1em;
  box-shadow: 0 0.1em 0 0 var(--link-underline-color);
}

a:hover,
a:focus,
a:active {
  text-decoration: none;
  border-radius: .2em;
}

a:hover,
a:focus {
  background-color: var(--button-hover-bg-color);
  box-shadow: 0 0.1em 0 0 var(--button-hover-bg-color);
}

a:active {
  background-color: var(--button-active-bg-color);
  box-shadow: 0 0.1em 0 0 var(--button-active-bg-color);
}

a:focus-visible {
  box-shadow: none;
}



/*
 * Autres
 */

strong, em, th {
  color: var(--text-strong-color);
  font-weight: 600;
}

abbr[title] {
  text-decoration: none;
}

@media (any-pointer: fine) {
  abbr[title] {
    border-bottom: 2px dotted var(--text-strong-color);
    position: relative;
  }
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
  overflow-x: hidden;
  overflow-y: auto;
  background-color: var(--body-color);
  --easing-standard: cubic-bezier(0.4, 0.0, 0.2, 1);
  --easing-decelerate: cubic-bezier(0.0, 0.0, 0.2, 1);
  --easing-accelerate: cubic-bezier(0.4, 0.0, 1, 1);
  --h-diff: -1;
  --text-strong-color: var(--h3-color);
  --section-padding: .9rem;
  --section-gap: 1.2rem;

  --echiquier-transparence: linear-gradient(45deg, rgba(0, 0, 0, .1) 25%, transparent 25%, transparent 75%, rgba(0, 0, 0, .1) 75%),
  linear-gradient(45deg, rgba(0, 0, 0, .1) 25%, transparent 25%, transparent 75%, rgba(0, 0, 0, .1) 75%),
  linear-gradient(to right, #ddd 0% 100%);

  --bg-color: var(--section-color);
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
  row-gap: var(--section-padding);
  width: 100%;
  min-height: 100%;
  margin: 0;
  padding: var(--section-padding);
  margin-right: env(safe-area-inset-right, 0);
  margin-left: env(safe-area-inset-left, 0);
  background-color: var(--body-color);
  color: var(--text-color);
}

html[data-show-documentation="true"] body {
  grid-template-rows: repeat(4, auto) auto;
}

@media (max-width: 42rem) {
  html {
    --section-padding: .6rem;
    --section-gap: .6rem;
  }
}



/*
 * Header
 */

header {
  grid-row: 1 / 2;
  grid-column: 2 / 5;
  display: grid;
  grid-template-columns: [logo-start] 1fr [logo-end github-start] auto [github-end] 1.2rem [options-start] auto [options-end];
  justify-content: center;
  align-items: start;
  position: relative;
  background: var(--section-color);
  padding: 0 var(--section-padding);
  margin-top: env(safe-area-inset-top, 0);
  min-height: var(--tap-safe-size);
  font-size: .9rem;
}

header>h1 {
  grid-column: logo-start / logo-end;
  grid-row: 1 / 2;
  justify-self: start;
  align-self: end;
  transform: none;
  position: relative;
  text-align: center;
  transform: translateY(17.7%);
  z-index: 0;
  font-size: calc(var(--mod) * var(--mod) * var(--mod) * 1rem);
  --shadow-color: var(--body-color);
}


/* Theme selector */

theme-selector {
  width: 1.8em;
  height: 1.8em;
  --margin-right: 0;
  margin-right: var(--margin-right);
  --primary-color: var(--h1-color);
  --secondary-color: var(--h1-color);
}

theme-selector>.selector {
  min-width: 9rem;
  right: calc(-1 * var(--section-padding));
  background-color: var(--section-color);
  box-shadow: 0 1px .2rem 1px var(--body-color);
  margin-top: .9rem;
  border-radius: .6rem;
  overflow: hidden;
  z-index: 100;
  transform: translateY(-.2rem);
  transition: opacity .2s ease,
              transform .2s ease;
}

theme-selector[open="true"]>.selector {
  transform: translateY(0);
}

theme-selector .selector-title {
  color: var(--h3-color);
  padding: .6rem .6rem;
  place-self: center;
}

theme-selector>.selector>label {
  min-height: var(--tap-safe-size);
  box-sizing: border-box;
}

theme-selector>.selector>label[for="theme-light"],
theme-selector>.selector>label[for="theme-dark"] {
  grid-template-columns: auto 1fr auto;
}

theme-selector>.selector>label>.theme-cookie-star {
  grid-column: 3;
  color: var(--secondary-color);
}

theme-selector .selector-cookie-notice {
  color: var(--secondary-color);
  padding: .6rem;
  hyphens: auto;
}

theme-selector input[type="radio"]:focus + label {
  box-shadow: inset 0 0 0 2px var(--link-color);
  outline: none;
}
theme-selector input[type="radio"]:focus:not(:focus-visible) + label {
  box-shadow: none;
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
  gap: .9rem;
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
  padding: .2rem;
  font-size: inherit;
}

/*.lien-github:hover,
.lien-github:focus,
.lien-github:active {
  color: var(--section-color);
}*/

.lien-github svg {
  fill: var(--h1-color);
}

span[data-string=github] {
  width: auto;
  text-align: right;
}

.github-cat {
  width: 1.1rem;
  height: 1.1rem;
  margin: -.6rem .3rem -.6rem 0;
}


@media (max-width: 30rem) {
  span[data-string=github] {
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

main {
  grid-row: 2 / -2;
  grid-column: 2 / -2;
  z-index: 1;
  padding: var(--section-padding);
  
  display: grid;
  grid-template-columns: 1fr min(100%, 42rem) 1fr;
  row-gap: var(--section-gap);
  align-content: start;
}

main > * {
  grid-column: 2;
}

header,
main,
footer {
  background: var(--bg-color);
}

header,
main,
section,
footer {
  border-radius: .6rem;
}

section {
  background-color: var(--section-color);
  padding: var(--section-padding);
  padding-top: 1.2rem;
  margin-top: calc(0.71625 * var(--mod) * var(--mod) * var(--mod) * 1rem);
  position: relative;
  z-index: 1;
}

section.no-title {
  margin-top: 0;
}

#intro {
  padding-top: var(--section-padding);
}

p[data-string="documentation-warning-js"] {
  display: none;
}



/*
 * Footer
 */

footer {
  grid-row: -2;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  padding: 0 var(--section-padding);
  margin-bottom: env(safe-area-inset-bottom, 0);
  /*margin-top: calc(-1 * var(--section-gap));*/
}

remiscan-logo {
  margin: .25rem 0;
  --width: 4.5rem;
}

remiscan-logo::part(link) {
  --text-color: var(--link-color);
  --text-gradient: var(--logo-gradient);
  border-radius: .2em;
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

#saisie,
#ranges {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: .6rem;
}

label[for=entree] {
  grid-row: 1;
  grid-column: 1;
  width: fit-content;
}

input[type="text"] {
  grid-row: auto;
  grid-column: 1 / 3;
  width: 100%;
  height: 100%;
  min-height: var(--tap-safe-size);
  border: none;
  box-sizing: border-box;
  padding: .4em .6em;
  font-family: 'Fira Code';
  color: var(--text-color);
  background-color: var(--input-bg-color);
  border-radius: .6rem;
  box-shadow: 0 0 0 1px var(--body-color);
}

input[type="text"]:hover {
  box-shadow: 0 0 0 2px var(--h3-color);
}

input[type="text"]:active,
input[type="text"]:focus {
  outline: none;
  background-color: var(--input-active-bg-color);
  box-shadow: 0 0 0 2px var(--h3-color);
}

::placeholder {
  color: var(--input-placeholder-color);
}



/*
 * Boutons d'exemples
 */

.exemples-saisie {
  display: flex;
  flex-direction: row;
  flex-wrap: nowrap;
  gap: .3rem;
  align-items: flex-end;
  overflow-x: auto;
  overflow-y: hidden;
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
  padding: 2px; /* pour éviter de couper le bord et l'outline de focus */
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
  border-radius: 4px;
}



/*
 * Sélecteurs de couleurs
 */

#ranges {
  grid-template-columns: 100%;
}

#ranges,
#resultats {
  --preview-border-width: 4px;
}

.choix-format {
  display: grid;
  grid-template-columns: auto 1fr;
  align-items: center;
  gap: .3rem .6rem;
}

.liste-formats {
  display: flex;
  align-items: center;
  gap: .3rem;
  flex-wrap: wrap;
  min-height: var(--tap-safe-size);
}

.choix-format input[type="radio"][name="choix-format"] + label {
  border-radius: .6rem;
  grid-template-columns: 1fr;
  gap: 0;
  place-items: center;
  height: 1.8rem;
  font-size: .9rem
  
}

input[type="radio"][name="choix-format"] + label::before {
  display: none;
}

#ranges label[data-format] {
  display: none;
  grid-template-columns: auto auto 1fr;
  grid-template-rows: auto auto;
  gap: .3rem;
  position: relative;
  --cursor-width: 14px;
}

#ranges[data-format="rgb"] label[data-format~="rgb"],
#ranges[data-format="hsl"] label[data-format~="hsl"],
#ranges[data-format="hwb"] label[data-format~="hwb"],
#ranges[data-format="lab"] label[data-format~="lab"],
#ranges[data-format="lch"] label[data-format~="lch"],
#ranges[data-format="oklab"] label[data-format~="oklab"],
#ranges[data-format="oklch"] label[data-format~="oklch"] {
  display: grid;
}

.choix-format>span:first-of-type,
label[data-format]>span:first-of-type {
  color: var(--h3-color);
  font-weight: 600;
}

label[data-format]>input[type="range"] {
  grid-row: 2;
  grid-column: 1 / -1;
}

input[type="range"] {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
	display: block;
  height: var(--tap-safe-size);
  border: var(--preview-border-width) solid var(--frame-color);
  border-radius: .6rem;
  --couleurs: white 0%, black 100%;
	background: linear-gradient(to right, var(--couleurs)),
              var(--echiquier-transparence);
  background-size: 100% 100%, 16px 16px, 16px 16px;
  background-position: 0 0, 0 0, 8px 8px;
  background-repeat: no-repeat, repeat, repeat;
}

input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  width: var(--cursor-width);
  height: var(--tap-safe-size);
  background: transparent;
  border: none;
  border-radius: .6rem;
  box-shadow: inset 0 0 0 2px var(--input-active-bg-color),
              0 0 0 2px var(--text-color);
}

input[type="range"]::-moz-range-thumb {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  width: var(--cursor-width);
  height: var(--tap-safe-size);
  background: transparent;
	border: none;
  border-radius: .6rem;
  box-shadow: inset 0 0 0 2px var(--input-active-bg-color),
              0 0 0 2px var(--text-color);
}

input[type="range"]::-moz-range-track {
	background: none;
}

input[type=number][data-property]::-webkit-inner-spin-button, 
input[type=number][data-property]::-webkit-outer-spin-button {  
  opacity: 1;
}

input[type="number"][data-property] {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  --width: 6ch;
	width: var(--width);
  height: 1.8rem;
  --padding-side: .3rem;
  padding: .15rem var(--padding-side);
  font-size: 1rem;
  color: var(--text-color);
  border: none;
  border-radius: .6rem;
	background: var(--body-color);
  --border-width: 1px;
  border: var(--border-width) solid var(--section-color);
  text-align: center;

  position: absolute;
  --compensation: calc(var(--cursor-width) + 2 * var(--preview-border-width) + 2 * var(--border-width));
  bottom: calc(var(--tap-safe-size) + 10px);
  left: clamp(
    0px,
    var(--pos, 0) * (100% - var(--compensation)) - 0.5 * var(--width) + var(--padding-side),
    100% - 24px - var(--width) + 2 * var(--padding-side)
  ); 
  margin: 0 -6ch 0 0;

  opacity: 0;
  pointer-events: none;
}

input[type="range"][data-property]:hover + input[type="number"][data-property],
input[type="range"][data-property]:focus + input[type="number"][data-property],
input[type="range"][data-property]:active + input[type="number"][data-property],
input[type="number"][data-property]:hover,
input[type="number"][data-property]:focus,
input[type="number"][data-property]:active {
  opacity: 1;
  pointer-events: auto;
}



/*
 * Résultats
 */

#resultats {
  margin-top: 1.8rem;
  position: relative;
  display: grid;
  grid-template-columns: auto 1fr;
  gap: .6rem;
}

#resultats [role="tablist"] {
  grid-column: 2;
  position: relative;
  top: unset;
  right: unset;
  gap: .3rem;
  align-items: center;
}

.choix-format input[type="radio"][name="choix-format"] + label,
#resultats input[type="radio"][role="tab"] + label {
  box-shadow: none;
  border-radius: .6rem;
  border: 1px solid var(--input-bg-color);
}

.choix-format input[type="radio"][name="choix-format"] + label:hover,
#resultats input[type="radio"][role="tab"] + label:hover {
  background-color: var(--button-hover-bg-color);
}

.choix-format input[type="radio"][name="choix-format"]:focus + label,
#resultats input[type="radio"][role="tab"]:focus + label {
  border-radius: 0;
}

.choix-format input[type="radio"][name="choix-format"]:focus:not(:focus-visible) + label,
#resultats input[type="radio"][role="tab"]:focus:not(:focus-visible) + label {
  border-radius: .6rem;
}

.choix-format input[type="radio"][name="choix-format"]:active + label,
#resultats input[type="radio"][role="tab"]:active + label {
  background-color: var(--button-active-bg-color);
  box-shadow: none;
  --decalage: 0rem;
}

.choix-format input[type="radio"][name="choix-format"]:checked + label,
#resultats input[type="radio"][role="tab"]:checked + label {
  background-color: var(--button-hover-bg-color);
  box-shadow: none;
  --decalage: 0rem;
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
  gap: .3rem;
}

table.donnees {
  margin-left: 0;
}

table.donnees th {
  text-align: left;
  padding: .4rem 0;
}

.donnees color-swatch {
  background-color: var(--frame-color);
  gap: calc(2 * var(--preview-border-width));
  padding: var(--preview-border-width);
  border-radius: .6rem;
}

.donnees color-swatch > .color-swatch-preview {
  border-radius: .4em;
}

.donnees color-swatch > button {
  padding: var(--preview-border-width);
  border: none;
  border-radius: .4em;
}

.donnees color-swatch > button:not(:hover, :focus, :active) {
  background: none;
}

.donnees color-swatch {
  --warning-color: var(--error-color);
}

.color-function-row-title {
  vertical-align: top;
}

.format {
  align-self: center;
  max-width: calc(100% - .6rem);
}

.format>pre[class*="language-"] {
  margin: 0;
}

.format-donnee {
  padding-left: 2rem;
}

.format.gradient,
.format.couleur {
  display: flex;
  flex-basis: 100%;
  height: 3rem;
  --border-size: var(--preview-border-width);
  align-self: stretch;
  background-color: var(--frame-color);
  border-radius: .6rem;
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
  border-radius: .49rem;
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
  border-radius: .4rem;
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



@media (max-width: 42rem) {
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
}





/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! SECTION DOCUMENTATION !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

/*
 * Espacement des paragraphes
 */

h2[data-string=titre-section-documentation] {
  max-width: calc(100vw - 2 * .6rem - 1.2rem - .7rem - 4rem - 1.2rem);
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
  padding-right: .5em;
}

p {
  margin: 0 0 1em 0;
}

p:last-child,
#intro>p {
  margin-bottom: 0;
}

p, li {
  line-height: 1.6em;
}

.documentation>article>*:not(.nav-rapide):not(a) {
  content-visibility: auto;
  contain-intrinsic-size: 1px 100px;
}

.documentation p:last-child,
.documentation div:last-child {
  margin: 0;
}

.documentation h3 + p,
.titre-partie-docu + p,
div + p {
  margin-top: 1.2em;
}

li + li {
  margin-top: .6em;
}

input + p {
  margin-bottom: 0;
}

div + p.h4,
ul + p.h4,
p + p.h4 {
  margin-top: 2.4rem;
}

a.anchor-dest + p.h4 {
  margin-top: 2em;
}

h3 + a.anchor-dest + p.h4 {
  margin-top: 1em;
}

.documentation h5,
.documentation h4 {
  display: flex;
  gap: .3rem;
  justify-content: baseline;
  position: relative;
  margin: 2.4em 0 1.2em;
  color: var(--h3-color);
  font-weight: 400;
}

.documentation h4::before,
.documentation h4::after {
  content: '';
  display: flex;
  height: 0;
  border-top: 2px solid var(--h3-color);
  opacity: .3;
  align-self: center;
}

.documentation h4::before {
  width: .9rem;
}

.documentation h4::before {
  width: .9rem;
}

.documentation h5::after,
.documentation h4::after {
  flex-grow: 1;
  border-top-style: dashed;
}

.documentation h5::before,
.documentation h5::after {
  opacity: 0;
}

.documentation h5 {
  margin: 1.2em 0 .9em;
  gap: 0;
}

.documentation h3 + h5,
.documentation h3 + a:not([href]) + h5,
.documentation h3 + a:not([href]) + h4,
.documentation h3 + h4 {
  margin-top: 1.2em;
}

.documentation h3 + a:not([href]) + h4::after,
.documentation h3 + h4::after {
  opacity: 0;
}

h3 + a.anchor-dest + .documenation h4::after {
  border-color: transparent;
}



/*
 * Menu de navigation rapide
 */

a#documentation {
  grid-row: 4;
  grid-column: 3;
  width: 1px;
  height: 1px;
  margin: 0 0 -1px -1px;
}

aside.nav-documentation {
  grid-row: 4;
  grid-column: 1;
  align-self: start;
  justify-self: end;
  min-width: 14em;
  max-width: 20rem;
  min-height: 2em;
  max-height: calc(100vh - .9rem - var(--margin-top));
  --margin-top: calc(0.71625 * var(--mod) * var(--mod) * var(--mod) * var(--mod) * 1rem + .6rem);
  margin-top: var(--margin-top);
  margin-right: .6rem;
  margin-left: .6rem;
  line-height: 1em;
  position: sticky;
  top: 1.8rem;
  background-color: var(--section-color);
  border-radius: .6rem;
  padding: .6rem;
  display: grid;
  grid-template-rows: 1fr;
  grid-template-columns: 1fr;
}

aside.nav-documentation>div {
  overflow-y: auto;
  grid-row: 1;
  grid-column: 1;
}

.documentation .nav-rapide {
  display: none;
  padding-bottom: .6rem;
  background-color: var(--section-color);
  position: relative;
  z-index: 2;
}

.documentation .nav-rapide + .exemple {
  display: none;
  position: sticky;
  top: .3rem;
  margin: 0 auto;
  background: var(--section-color) linear-gradient(to right, var(--button-bg-color) 0% 100%);
  z-index: 1;
  margin-top: var(--button-height, 0);
  width: fit-content;
}

.titre-nav-rapide {
  font-size: calc(var(--mod) * 1.1rem);
  line-height: normal;
}

.documentation ul {
  padding-left: 1.2rem;
  margin: 0 0 1em 0;
}

.documentation ul:last-child {
  margin-bottom: 0;
}

.nav-rapide li + li {
  margin-top: 0;
}

.nav-rapide ul {
  padding-left: 1.2em;
  margin: 0;
  font-size: .9em;
}

@media (max-width: calc(.6rem + 20rem + .6rem + 42rem + .6rem + 20rem + .6rem + 2.4rem)) {
  aside.nav-documentation {
    display: none;
    grid-row: 3;
    grid-column: 2;
    align-self: end;
    justify-self: start;
    padding: .3rem;
    width: 24px;
    height: 24px;
    z-index: 4;
    top: unset;
    bottom: .45rem;
    background-color: var(--body-color);
    margin-left: .45rem;
    margin-bottom: .45rem;
  }

  aside.nav-documentation.on {
    width: auto;
    height: auto;
  }

  .documentation .nav-rapide/*,
  .documentation .nav-rapide + .exemple*/ {
    display: block;
  }

  .nav-documentation ul {
    display: none;
  }
  .nav-documentation.on ul {
    display: block;
  }
}

.documentation p:first-of-type {
  margin-top: 0;
}



/*
 * Exemple de code avec résultat juxtaposé
 */

/* Code blocks */
pre[class*="language-"] {
	padding: .4em .5em;
	margin: 1em 0;
	overflow: auto;
	border: none;
	border-radius: .5em;
	box-shadow: none;
}

/* Inline code */
:not(pre) > code[class*="language-"] {
	padding: .05em .2em;
	border-radius: .3em;
	border: none;
	box-shadow: none;
	white-space: nowrap;
	margin: 0 1px;
}

.example-code {
  display: grid;
  grid-template-columns: 1fr;
  grid-template-rows: auto auto;
  grid-gap: .6em;
  margin-bottom: 1em;
}

:not(pre) > code[class*="language-"] {
  white-space: normal;
}

.example-code > pre[class*="language-"] {
  margin: 0;
}

.input {
  grid-column: 1 / 2;
  grid-row: 1 / 2;
}

.output {
  grid-row: 2 / 3;
  grid-column: 1 / 2;
}

code[class*="language-"],
pre[class*="language-"],
:not(pre) > code[class*="language-"] {
  background-color: var(--code-color);
  color: var(--text-color);
  text-shadow: none;
  white-space: pre-wrap;
  font-family: 'Fira Code';
  font-variant-ligatures: none;
}

@media (max-width: 42rem) {
  pre[class*="language-"]>code[class*="language-"] {
    white-space: pre;
  }
}

.documentation pre>code[class*="language-"] {
  font-size: .9em;
}

.note {
  border-right: none;
  border-bottom: none;
  border-radius: .6rem;
  background-color: var(--note-color);
  padding: .6em;
  margin: 1em 0;
  overflow: auto;
  box-shadow: none;
}

.note>pre:last-child,
article>pre:last-child {
  margin-bottom: 0;
}

.nav-rapide li,
.nav-rapide code[class*="language-"] {
  all: revert;
  font-family: inherit;
  color: inherit;
  line-height: 1.6em;
}

.nav-rapide li>ul {
  margin: .3em 0;
}


/*
 * Cacher la documentation
 */

.show-documentation {
  grid-column: 2;
  font-family: inherit;
  font-size: 1rem;
  font-weight: 600;
  margin-top: .6rem;
  padding: .4em 1.2em;
  border-radius: 10px;
  min-height: var(--tap-safe-size);
}

html:not([data-show-documentation="true"]) .nav-documentation,
html:not([data-show-documentation="true"]) .documentation {
  display: none;
}

html[data-show-documentation="true"] .show-documentation {
  display: none;
}


/*
 * Personnalisation de la coloration syntaxique
 */

.token.property,
.token.tag,
.token.boolean,
.token.number,
.token.constant,
.token.symbol {
  color: var(--token-number);
}

.token.selector,
.token.attr-name,
.token.string,
.token.char,
.token.builtin,
.token.inserted {
  color: var(--token-string);
}

.token.operator,
.token.entity,
.token.url,
.language-css .token.string,
.style .token.string,
.token.variable {
  color: var(--token-operator);
}

.token.atrule,
.token.attr-value,
.token.keyword {
  color: var(--token-keyword);
}

.token.regex,
.token.important {
  color: #e90;
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

theme-selector:not(:defined),
remiscan-logo:not(:defined),
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
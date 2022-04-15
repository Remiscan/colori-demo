<?php
require_once '../dist/colori.php';

$commonDir = dirname(__DIR__, 2).'/_common';
require_once $commonDir.'/php/httpLanguage.php';
require_once $commonDir.'/php/version.php';
require_once $commonDir.'/php/getStrings.php';

$urlLang = isset($_GET['lang']) ? substr(htmlspecialchars($_GET['lang']), 0, 2) : null;
$cookieLang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : null;
$lang = $urlLang ?: $cookieLang ?: httpLanguage() ?: 'en';
$Textes = new Textes('colori/demo', $lang);

$progLanguage = isset($_COOKIE['prog-language']) ? $_COOKIE['prog-language'] : 'js';
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'auto';
$resolvedTheme = isset($_COOKIE['resolvedTheme']) ? $_COOKIE['resolvedTheme'] : 'light';

require_once './ext/Parsedown.php';
$Parsedown = new Parsedown();

$namedColors = array_keys(Couleur::NAMED_COLORS);
$r = mt_rand(0, count($namedColors) - 1);
$startColor = new Couleur($namedColors[$r]);
?>
<!doctype html>
<html lang="<?=$lang?>"
      data-prog-language="<?=$progLanguage?>"
      data-theme="<?=$theme?>"
      data-resolved-theme="<?=$resolvedTheme?>"
      data-start-color="<?=$startColor->name()?>"
      style="--user-color: <?=$startColor->name()?>;">
  <head>
    <meta charset="utf-8">
    <title>Colori</title>

    <meta name="description" content="<?=$Textes->getString('description-site')?>">
    <meta property="og:title" content="Colori">
    <meta property="og:description" content="<?=$Textes->getString('description-site')?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://remiscan.fr/colori/">
    <meta property="og:image" content="https://remiscan.fr/mon-portfolio/projets/colori/og-preview.png">
    <link rel="canonical" href="https://remiscan.fr/colori/">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0">
    <meta name="color-scheme" content="light dark">

    <!-- ▼ Fichiers cache-busted grâce à PHP -->
    <!--<?php ob_start();?>-->

    <link rel="icon" type="image/png" href="/colori/demo/icons/icon-192.png">
    <link rel="apple-touch-icon" href="/colori/demo/icons/apple-touch-icon.png">
    <link rel="manifest" href="/colori/demo/manifest.json">

    <!-- Import map -->
    <script defer src="/_common/polyfills/adoptedStyleSheets.min.js"></script>
    <script>window.esmsInitOptions = { polyfillEnable: ['css-modules', 'json-modules'] }</script>
    <script defer src="/_common/polyfills/es-module-shims.js"></script>
    <script type="importmap"><?php include 'import-map.json'; ?></script>

    <script defer src="/colori/demo/ext/prism.js" data-manual></script>
    <script src="/colori/demo/script.js" type="module"></script>

    <link rel="preload" as="fetch" href="/colori/demo/strings.json" crossorigin
          id="strings" data-version="<?=version(__DIR__, 'strings.json')?>">

    <link rel="stylesheet" href="/colori/demo/ext/prism.css">
    <link rel="stylesheet" href="/colori/demo/page.css.php">

    <!--<?php $imports = ob_get_clean();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
    echo versionizeFiles($imports, __DIR__); ?>-->

    <style id="theme-variables">
      <?php ob_start();
      $colorPreview = Couleur::blend('white', $startColor);
      $cieh = round($startColor->cieh());

      /* Définition des couleurs du thème clair */
      $ciec = min($startColor->ciec(), 75/sqrt(3));
      $bodyColor = new Couleur("lch(75% $ciec $cieh)");
      $sectionColor = new Couleur("lch(85% ". (0.6 * $ciec) ." $cieh)");
      $codeColor = new Couleur("lch(92% ". (0.3 * $ciec) ." $cieh)");
      ?>
      :root[data-theme="light"] {
        /* Background colors */
        --body-color: <?=$bodyColor->hsl()?>;
        --section-color: <?=$sectionColor->hsl()?>;
        --frame-color: <?=$codeColor->improveContrast($colorPreview, 2.5)->hsl()?>;
        --code-color: <?=$codeColor->hsl()?>;
        --tab-hover-color: <?=$sectionColor->replace('a', .7)->hsl()?>;
        --note-color: <?=Couleur::blend($sectionColor, $bodyColor->replace('a', .3))->hsl()?>;
        /* Text colors */
        --h1-color: <?= (new Couleur("lch(30% ". (0.6 * $ciec) ." $cieh)"))->hsl() ?>;
        --h3-color: <?= (new Couleur("lch(45% $ciec $cieh)"))->hsl() ?>;
        --text-color: black;
        --link-color: <?= (new Couleur("lch(30% $ciec $cieh)"))->hsl() ?>;
        --link-underline-color: <?= (new Couleur("lch(30% ". (2 * $ciec) ." $cieh / .5)"))->hsl() ?>;
        /* Input colors */
        --input-bg-color: <?= (new Couleur("lch(95% ". (0.3 * $ciec) ." $cieh)"))->hsl() ?>;
        --input-active-bg-color: <?= (new Couleur("lch(99% ". (0.1 * $ciec) ." $cieh)"))->hsl() ?>;
        --input-placeholder-color: <?= (new Couleur("lch(25% ". (0.5 * $ciec) ." $cieh / .5)"))->hsl() ?>;
        /* Syntax coloring colors */
        --token-number: <?= (new Couleur("lch(50% 70 ". ($cieh - 90) .")"))->hsl() ?>;
        --token-string: <?= (new Couleur("lch(50% 70 ". ($cieh + 45) .")"))->hsl() ?>;
        --token-operator: <?= (new Couleur("lch(50% 70 ". ($cieh - 45) .")"))->hsl() ?>;
        --token-keyword: <?= (new Couleur("lch(50% 70 ". ($cieh + 135) .")"))->hsl() ?>;
        /* Button colors */
        --button-bg-color: <?= (new Couleur("lch(90% ". (0.6 * $ciec) ." $cieh)"))->hsl() ?>;
        --button-active-bg-color: <?= (new Couleur("lch(98% ". (0.3 * $ciec) ." $cieh)"))->hsl() ?>;
      }

      <?php
      /* Définition des couleurs du thème sombre */
      $ciec = min($startColor->ciec(), 8/sqrt(1.040816));
      $bodyColorDark = new Couleur("lch(8% ".(.6 * $ciec)." $cieh)");
      $sectionColor = new Couleur("lch(20% $ciec $cieh)");
      $codeColor = $bodyColorDark;
      ?>
      :root[data-theme="dark"] {
        /* Background colors */
        --body-color: <?=$bodyColorDark->hsl()?>;
        --section-color: <?=$sectionColor->hsl()?>;
        --frame-color: <?=$codeColor->improveContrast($colorPreview, 2.5)->hsl()?>;
        --code-color: <?=$codeColor->hsl()?>;
        --tab-hover-color: <?=$sectionColor->replace('a', .7)->hsl()?>;
        --note-color: <?=Couleur::blend($sectionColor, $bodyColorDark->replace('a', .5))->hsl()?>;
        /* Text colors */
        --h1-color: <?= (new Couleur("lch(80% $ciec $cieh)"))->hsl() ?>;
        --h3-color: <?= (new Couleur("lch(70% ". (1.7 * $ciec) ." $cieh)"))->hsl() ?>;
        --text-color: <?= (new Couleur("lch(90% ". (0.2 * $ciec) ." $cieh)"))->hsl() ?>;
        --link-color: <?= (new Couleur("lch(80% ". (1.7 * $ciec) ." $cieh)"))->hsl() ?>;
        --link-underline-color: <?= (new Couleur("lch(80% ". (2 * 1.7 * $ciec) ." $cieh / .5)"))->hsl() ?>;
        /* Input colors */
        --input-bg-color: <?= (new Couleur("lch(30% ". (1.5 * $ciec) ." $cieh)"))->hsl() ?>;
        --input-active-bg-color: <?= (new Couleur("lch(10% ". (0.6 * $ciec) ." $cieh)"))->hsl() ?>;
        --input-placeholder-color: <?= (new Couleur("lch(90% ". (0.5 * $ciec) ." $cieh / .5)"))->hsl() ?>;
        /* Syntax coloring colors */
        --token-number: <?= (new Couleur("lch(80% 70 ". ($cieh - 90) .")"))->hsl() ?>;
        --token-string: <?= (new Couleur("lch(80% 70 ". ($cieh + 45) .")"))->hsl() ?>;
        --token-operator: <?= (new Couleur("lch(80% 70 ". ($cieh - 45) .")"))->hsl() ?>;
        --token-keyword: <?= (new Couleur("lch(80% 70 ". ($cieh + 135) .")"))->hsl() ?>;
        /* Button colors */
        --button-bg-color: <?= (new Couleur("lch(25% ". (.75 * $ciec) ." $cieh)"))->hsl() ?>;
        --button-active-bg-color: <?= (new Couleur("lch(35% ". (1.5 * $ciec) ." $cieh)"))->hsl() ?>;
      }
      <?php $body = ob_get_clean();
      require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/components/theme-selector/build-css.php';
      echo buildThemesStylesheet($body, closeComment: false); ?>
    </style>

    <meta name="theme-color" content="<?=($resolvedTheme == 'dark' ? $bodyColorDark->hsl() : $bodyColor->hsl())?>" data-light="<?=$bodyColor->hsl()?>" data-dark="<?=$bodyColorDark->hsl()?>">

    <noscript>
      <link rel="stylesheet" href="/colori/demo/style-noscript.css">
    </noscript>
  </head>

  <body>

    <svg version="1.1" style="display: none">
      <defs>
        <g id="github-cat">
          <path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path>
        </g>
      </defs>
    </svg>

    <header>
      <h1>colori</h1>

      <div class="groupe-langages">
        <a href="https://github.com/Remiscan/colori" class="lien-github" data-tappable
          data-label="github" aria-label="<?=$Textes->getString('github')?>">
          <svg viewBox="0 0 16 16" class="github-cat"><use href="#github-cat" /></svg>
          <span data-string="github"><?=$Textes->getString('github')?></span>
          <span>GitHub</span>
        </a>
        <!--<a href="https://github.com/Remiscan/colori/wiki/Documentation-pour-JavaScript-%28Fran%C3%A7ais%29" data-tappable lang="fr" data-prog-language="js"><?=$Textes->getString('titre-section-documentation')?></a>
        <a href="https://github.com/Remiscan/colori/wiki/Documentation-pour-PHP-%28Fran%C3%A7ais%29" data-tappable lang="fr" data-prog-language="php"><?=$Textes->getString('titre-section-documentation')?></a>
        <a href="https://github.com/Remiscan/colori/wiki/Documentation-for-JavaScript-%28English%29" data-tappable lang="en" data-prog-language="js"><?=$Textes->getString('titre-section-documentation')?></a>
        <a href="https://github.com/Remiscan/colori/wiki/Documentation-for-PHP-%28English%29" data-tappable lang="en" data-prog-language="php"><?=$Textes->getString('titre-section-documentation')?></a>-->
        <a href="?lang=fr" class="bouton-langage" data-tappable lang="fr" data-lang="fr" <?=($lang == 'fr' ? 'disabled' : '')?>>Français</a>
        <a href="?lang=en" class="bouton-langage" data-tappable lang="en" data-lang="en" <?=($lang == 'en' ? 'disabled' : '')?>>English</a>
        <theme-selector position="bottom"></theme-selector>
      </div>
    </header>

    <section id="intro">
      <p data-string="documentation-intro-p1"><?=$Textes->getString('documentation-intro-p1')?></p>
      <p data-string="documentation-warning-js"><?=$Textes->getString('documentation-warning-js')?></p>
    </section>

    <section id="demo">
      <h2 data-string="titre-section-demo"><?=$Textes->getString('titre-section-demo')?></h2>

      <fieldset role="tablist" data-group="tabs-input-method">
        <legend data-string="tabs-input-method-label"></legend>

        <tab-label controls="saisie" data-label-id="tab-label-manuel" label="<?=$Textes->getString('tab-label-manuel')?>" active="true"></tab-label>
        <tab-label controls="ranges" data-label-id="tab-label-selecteurs" label="<?=$Textes->getString('tab-label-selecteurs')?>"></tab-label>
      </fieldset>
      
      <div id="saisie">
        <h3 class="no-separator">
          <label for="entree" data-string="demo-input-label"><?=$Textes->getString('demo-input-label')?></label>
        </h3>

        <div class="exemples-saisie exemples-valeurs">
          <span data-string="exemple-abbr"><?=$Textes->getString('exemple-abbr')?></span>
          <button type="button" class="exemple">pink</button>
          <button type="button" class="exemple">#4169E1</button>
          <button type="button" class="exemple">rgb(255, 127, 80)</button>
          <button type="button" class="exemple" data-label="more-examples" aria-label="<?=$Textes->getString('more-examples')?>">&nbsp;+&nbsp;</button>
        </div>

        <p class="instructions-exemples-fonctions off" data-hidden="true" data-string="instructions-demo"><?=$Textes->getString('instructions-demo')?></p>

        <div class="exemples-saisie exemples-fonctions off" data-hidden="true">
          <span data-string="exemple-abbr"><?=$Textes->getString('exemple-abbr')?></span>
          <button type="button" class="exemple">pink.invert()</button>
          <button type="button" class="exemple">#4169E1.scale(l, .5)</button>
          <button type="button" class="exemple">black.contrast(white)</button>
          <button type="button" class="exemple">orchid.interpolate(palegreen, 4, oklch)</button>
          <button type="button" class="exemple">rgb(255, 127, 80).scale(s, .5).blend(red.replace(a, .2))</button>
        </div>

        <input id="entree" class="h4" type="text" data-abbr="<?=$Textes->getString('exemple-abbr')?>"
                autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                placeholder="<?=$startColor->name()?>">
      </div>

      <div id="ranges" data-format="rgb" hidden>
        <h3 class="no-separator" data-string="demo-selectors-title"><?=$Textes->getString('demo-selectors-title')?></h3>

        <div class="choix-format">
          <span data-string="choix-format-titre"><?=$Textes->getString('choix-format-titre')?></span>

          <div class="liste-formats">
            <input type="radio" id="choix-format-rgb" name="choix-format" value="rgb" checked>
            <label for="choix-format-rgb" data-tappable="after">RGB</label>

            <input type="radio" id="choix-format-hsl" name="choix-format" value="hsl">
            <label for="choix-format-hsl" data-tappable="after">HSL</label>

            <input type="radio" id="choix-format-hwb" name="choix-format" value="hwb">
            <label for="choix-format-hwb" data-tappable="after">HWB</label>

            <input type="radio" id="choix-format-lab" name="choix-format" value="lab">
            <label for="choix-format-lab" data-tappable="after">LAB</label>

            <input type="radio" id="choix-format-lch" name="choix-format" value="lch">
            <label for="choix-format-lch" data-tappable="after">LCH</label>

            <input type="radio" id="choix-format-oklab" name="choix-format" value="oklab">
            <label for="choix-format-oklab" data-tappable="after">OKLAB</label>

            <input type="radio" id="choix-format-oklch" name="choix-format" value="oklch">
            <label for="choix-format-oklch" data-tappable="after">OKLCH</label>
          </div>
        </div>

        <label for="range-red" data-format="rgb">
          <span data-string="prop-r-nom"><?=$Textes->getString('prop-r-nom')?></span>
          <span>[0 ; 255]</span>
          <input type="range" id="range-red" data-property="r" min="0" max="255" step="1" value="<?=round(255 * $startColor->r)?>">
          <input type="number" data-property="r" min="0" max="255" step="1" value="<?=round(255 * $startColor->r)?>">
        </label>

        <label for="range-green" data-format="rgb">
          <span data-string="prop-g-nom"><?=$Textes->getString('prop-g-nom')?></span>
          <span>[0 ; 255]</span>
          <input type="range" id="range-green" data-property="g" min="0" max="255" step="1" value="<?=round(255 * $startColor->g)?>">
          <input type="number" data-property="g" min="0" max="255" step="1" value="<?=round(255 * $startColor->g)?>">
        </label>

        <label for="range-blue" data-format="rgb">
          <span data-string="prop-b-nom"><?=$Textes->getString('prop-b-nom')?></span>
          <span>[0 ; 255]</span>
          <input type="range" id="range-blue" data-property="b" min="0" max="255" step="1" value="<?=round(255 * $startColor->b)?>">
          <input type="number" data-property="b" min="0" max="255" step="1" value="<?=round(255 * $startColor->b)?>">
        </label>

        <label for="range-hue" data-format="hsl hwb">
          <span data-string="prop-h-nom"><?=$Textes->getString('prop-h-nom')?></span>
          <span>[0 ; 360]</span>
          <input type="range" id="range-hue" data-property="h" min="0" max="360" step="1" value="<?=round($startColor->h())?>">
          <input type="number" data-property="h" min="0" max="360" step="1" value="<?=round($startColor->h())?>">
        </label>

        <label for="range-saturation" data-format="hsl">
          <span data-string="prop-s-nom"><?=$Textes->getString('prop-s-nom')?></span>
          <span>[0 ; 100]</span>
          <input type="range" id="range-saturation" data-property="s" min="0" max="100" step="1" value="<?=round(100 * $startColor->s())?>">
          <input type="number" data-property="s" min="0" max="100" step="1" value="<?=round(100 * $startColor->s())?>">
        </label>

        <label for="range-luminosity" data-format="hsl">
          <span data-string="prop-l-nom"><?=$Textes->getString('prop-l-nom')?></span>
          <span>[0 ; 100]</span>
          <input type="range" id="range-luminosity" data-property="l" min="0" max="100" step="1" value="<?=round(100 * $startColor->l())?>">
          <input type="number" data-property="l" min="0" max="100" step="1" value="<?=round(100 * $startColor->l())?>">
        </label>

        <label for="range-whiteness" data-format="hwb">
          <span data-string="prop-w-nom"><?=$Textes->getString('prop-w-nom')?></span>
          <span>[0 ; 100]</span>
          <input type="range" id="range-whiteness" data-property="w" min="0" max="100" step="1" value="<?=round(100 * $startColor->w())?>">
          <input type="number" data-property="w" min="0" max="100" step="1" value="<?=round(100 * $startColor->w())?>">
        </label>

        <label for="range-blackness" data-format="hwb">
          <span data-string="prop-bk-nom"><?=$Textes->getString('prop-bk-nom')?></span>
          <span>[0 ; 100]</span>
          <input type="range" id="range-blackness" data-property="bk" min="0" max="100" step="1" value="<?=round(100 * $startColor->bk())?>">
          <input type="number" data-property="bk" min="0" max="100" step="1" value="<?=round(100 * $startColor->bk())?>">
        </label>

        <label for="range-cie-lightness" data-format="lab lch">
          <span data-string="prop-ciel-nom"><?=$Textes->getString('prop-ciel-nom')?></span>
          <span>[0 ; 100]</span>
          <input type="range" id="range-cie-lightness" data-property="ciel" min="0" max="100" step="1" value="<?=round(100 * $startColor->ciel())?>">
          <input type="number" data-property="ciel" min="0" max="100" step="1" value="<?=round(100 * $startColor->ciel())?>">
        </label>

        <label for="range-cie-a-axis" data-format="lab">
          <span data-string="prop-ciea-nom"><?=$Textes->getString('prop-ciea-nom')?></span>
          <span>[-80 ; 94]</span>
          <input type="range" id="range-cie-a-axis" data-property="ciea" min="-80" max="94" step="1" value="<?=round($startColor->ciea())?>">
          <input type="number" data-property="ciea" min="-80" max="94" step="1" value="<?=round($startColor->ciea())?>">
        </label>

        <label for="range-cie-b-axis" data-format="lab">
          <span data-string="prop-cieb-nom"><?=$Textes->getString('prop-cieb-nom')?></span>
          <span>[-112 ; 94]</span>
          <input type="range" id="range-cie-b-axis" data-property="cieb" min="-112" max="94" step="1" value="<?=round($startColor->cieb())?>">
          <input type="number" data-property="cieb" min="-112" max="94" step="1" value="<?=round($startColor->cieb())?>">
        </label>

        <label for="range-cie-chroma" data-format="lch">
          <span data-string="prop-ciec-nom"><?=$Textes->getString('prop-ciec-nom')?></span>
          <span>[0 ; 132]</span>
          <input type="range" id="range-cie-chroma" data-property="ciec" min="0" max="132" step="1" value="<?=round($startColor->ciec())?>">
          <input type="number" data-property="ciec" min="0" max="132" step="1" value="<?=round($startColor->ciec())?>">
        </label>

        <label for="range-cie-hue" data-format="lch">
          <span data-string="prop-cieh-nom"><?=$Textes->getString('prop-cieh-nom')?></span>
          <span>[0 ; 360]</span>
          <input type="range" id="range-cie-hue" data-property="cieh" min="0" max="360" step="1" value="<?=round($startColor->cieh())?>">
          <input type="number" data-property="cieh" min="0" max="360" step="1" value="<?=round($startColor->cieh())?>">
        </label>

        <label for="range-ok-lightness" data-format="oklab oklch">
          <span data-string="prop-okl-nom"><?=$Textes->getString('prop-okl-nom')?></span>
          <span>[0 ; 100]</span>
          <input type="range" id="range-ok-lightness" data-property="okl" min="0" max="100" step="1" value="<?=round(100 * $startColor->okl())?>">
          <input type="number" data-property="okl" min="0" max="100" step="1" value="<?=round(100 * $startColor->okl())?>">
        </label>

        <label for="range-ok-a-axis" data-format="oklab">
          <span data-string="prop-oka-nom"><?=$Textes->getString('prop-oka-nom')?></span>
          <span>[-0.24 ; 0.28]</span>
          <input type="range" id="range-ok-a-axis" data-property="oka" min="-0.24" max="0.28" step="0.001" value="<?=round(10**3 * $startColor->oka()) / 10**3?>">
          <input type="number" data-property="oka" min="-0.24" max="0.28" step="0.001" value="<?=round(10**3 * $startColor->oka()) / 10**3?>">
        </label>

        <label for="range-ok-b-axis" data-format="oklab">
          <span data-string="prop-okb-nom"><?=$Textes->getString('prop-okb-nom')?></span>
          <span>[-0.32 ; 0.20]</span>
          <input type="range" id="range-ok-b-axis" data-property="okb" min="-0.32" max="0.20" step="0.001" value="<?=round(10**3 * $startColor->okb()) / 10**3?>">
          <input type="number" data-property="okb" min="-0.32" max="0.20" step="0.001" value="<?=round(10**3 * $startColor->okb()) / 10**3?>">
        </label>

        <label for="range-ok-chroma" data-format="oklch">
          <span data-string="prop-okc-nom"><?=$Textes->getString('prop-okc-nom')?></span>
          <span>[0 ; 0.32]</span>
          <input type="range" id="range-ok-chroma" data-property="okc" min="0" max="0.32" step="0.001" value="<?=round(10**3 * $startColor->okc()) / 10**3?>">
          <input type="number" data-property="okc" min="0" max="0.32" step="0.001" value="<?=round(10**3 * $startColor->okc()) / 10**3?>">
        </label>

        <label for="range-ok-hue" data-format="oklch">
          <span data-string="prop-okh-nom"><?=$Textes->getString('prop-okh-nom')?></span>
          <span>[0 ; 360]</span>
          <input type="range" id="range-ok-hue" data-property="okh" min="0" max="360" step="1" value="<?=round($startColor->okh())?>">
          <input type="number" data-property="okh" min="0" max="360" step="1" value="<?=round($startColor->okh())?>">
        </label>

        <label for="range-opacity" data-format="rgb hsl hwb lab lch oklab oklch">
          <span data-string="prop-a-nom"><?=$Textes->getString('prop-a-nom')?></span>
          <span>[0 ; 100]</span>
          <input type="range" id="range-opacity" data-property="a" min="0" max="100" step="1" value="<?=round(100 * $startColor->a)?>">
          <input type="number" data-property="a" min="0" max="100" step="1" value="<?=round(100 * $startColor->a)?>">
        </label>
      </div>

      <div id="resultats">
        <h3 class="no-separator" data-string="demo-resultats-titre"><?=$Textes->getString('demo-resultats-titre')?></h3>

        <fieldset role="tablist" data-group="tabs-results">
          <legend data-string="tabs-results-label"></legend>

          <tab-label controls="results-named-formats" data-label-id="tab-label-named-formats" label="<?=$Textes->getString('tab-label-named-formats')?>" active="true"></tab-label>
          <tab-label controls="results-color-spaces" data-label-id="tab-label-color-spaces" label="<?=$Textes->getString('tab-label-color-spaces')?>"></tab-label>
        </fieldset>

        <div class="donnees" id="results-values">
          <div class="format gradient" data-string="apercu-gradient"><?=$Textes->getString('apercu-gradient')?></div>

          <div class="format valeur">
            <pre><code class="language-css"></code></pre>
          </div>
        </div>

        <div class="donnees" id="results-named-formats">
          <color-swatch format="name" color="<?=$startColor->name()?>"></color-swatch>
          <color-swatch format="hex" color="<?=$startColor->name()?>"></color-swatch>
          <color-swatch format="rgb" color="<?=$startColor->name()?>"></color-swatch>
          <color-swatch format="hsl" color="<?=$startColor->name()?>"></color-swatch>
          <color-swatch format="hwb" color="<?=$startColor->name()?>"></color-swatch>
          <color-swatch format="lab" color="<?=$startColor->name()?>"></color-swatch>
          <color-swatch format="lch" color="<?=$startColor->name()?>"></color-swatch>
          <color-swatch format="oklab" color="<?=$startColor->name()?>"></color-swatch>
          <color-swatch format="oklch" color="<?=$startColor->name()?>"></color-swatch>
        </div>

        <div class="donnees" id="results-color-spaces" hidden>
          <color-swatch format="color-srgb" color="<?=$startColor->name()?>"></color-swatch>
          <color-swatch format="color-srgb-linear" color="<?=$startColor->name()?>"></color-swatch>
          <color-swatch format="color-display-p3" color="<?=$startColor->name()?>"></color-swatch>
          <color-swatch format="color-a98-rgb" color="<?=$startColor->name()?>"></color-swatch>
          <color-swatch format="color-prophoto-rgb" color="<?=$startColor->name()?>"></color-swatch>
          <color-swatch format="color-rec2020" color="<?=$startColor->name()?>"></color-swatch>
          <color-swatch format="color-xyz-d50" color="<?=$startColor->name()?>"></color-swatch>
          <color-swatch format="color-xyz-d65" color="<?=$startColor->name()?>"></color-swatch>
        </div>

      </div>
    </section>

    <?php
    // Préparons la DOCUMENTATION

    function anchorLink($matches) {
      $title = $matches[2];
      $h = $matches[1];
      $link = preg_replace(['/\<code(?:.+?)?\>/', '/\<\/code\>/'], '', $title);
      $link = strtolower($link);
      $link = str_replace([' ', '.', '\'', '/'], ['-', '', '', ''], $link);
      return '<a id="'. $link .'"></a><h'. $h .'>'. $title .'</h'. $h .'>';
    }

    function prepareDocumentation($docu, $lang, $progLang) {
      $docu = str_replace(['h3', 'h2', 'h1', '<code>', '<pre>'], ['h5', 'h4', 'h3', '<code class="language-javascript">', '<pre class="language-javascript">'], $docu);
      $docu = preg_replace('/\<ul\>/', '<div class="nav-rapide"><ul>', $docu, 1);
      $docu = preg_replace('/\<\/ul\>\n\<p\>/', '</ul></div><p>', $docu, 1);
      $docu = preg_replace_callback('/\<h(3|4)\>(.+)?\<\/h(?:3|4)\>/', 'anchorLink', $docu);
      $docu = preg_replace('/\<a id=\"(.+?)\">/', "<a id=\"$1-$lang-$progLang\">", $docu);
      $docu = preg_replace('/\<a href=\"(.+?)\">/', "<a href=\"$1-$lang-$progLang\">", $docu);
      $docu = preg_replace('/\<blockquote\>/', '<div class="note">', $docu);
      $docu = preg_replace('/\<\/blockquote\>/', '</div>', $docu);
      $docu = preg_replace('/\<h3\>/', '<hr><h3>', $docu);
      return $docu;
    }

    function prepareNav($docu) {
      $menu = preg_match('/\<div class=\"nav-rapide\"\>((?:.|\n|\r)+?)\<\/div\>/', $docu, $matches);
      $menu = $matches[1];
      return $menu;
    }

    $docuJsFr = file_get_contents('../wiki/Documentation-pour-JavaScript-(Français).md');
    $docuJsFr = prepareDocumentation($Parsedown->text($docuJsFr), 'fr', 'js');
    $quicknavJsFr = prepareNav($docuJsFr);

    $docuJsEn = file_get_contents('../wiki/Documentation-for-JavaScript-(English).md');
    $docuJsEn = prepareDocumentation($Parsedown->text($docuJsEn), 'en', 'js');
    $quicknavJsEn = prepareNav($docuJsEn);

    $docuPhpFr = file_get_contents('../wiki/Documentation-pour-PHP-(Français).md');
    $docuPhpFr = prepareDocumentation($Parsedown->text($docuPhpFr), 'fr', 'php');
    $quicknavPhpFr = prepareNav($docuPhpFr);

    $docuPhpEn = file_get_contents('../wiki/Documentation-for-PHP-(English).md');
    $docuPhpEn = prepareDocumentation($Parsedown->text($docuPhpEn), 'en', 'php');
    $quicknavPhpEn = prepareNav($docuPhpEn);
    ?>

    <button type="button" class="show-documentation" data-string="button-show-documentation"><?=$Textes->getString('button-show-documentation')?></button>

    <aside class="nav-documentation nav-rapide" data-label="nav-documentation">
      <h2 class="titre-nav-rapide" data-string="nav-documentation"><?=$Textes->getString('nav-documentation')?></h2>
      <div lang="fr" data-prog-language="js"><?=$quicknavJsFr?></div>
      <div lang="en" data-prog-language="js"><?=$quicknavJsEn?></div>
      <div lang="fr" data-prog-language="php"><?=$quicknavPhpFr?></div>
      <div lang="en" data-prog-language="php"><?=$quicknavPhpEn?></div>
    </aside>

    <a id="documentation" aria-hidden="true"></a>
    <section class="documentation">
      <h1 data-string="titre-section-documentation"><?=$Textes->getString('titre-section-documentation')?></h1>

      <fieldset role="tablist" data-group="tabs-prog-language">
        <legend data-string="tabs-group-language-label"></legend>

        <tab-label controls="docu-js-fr" label=".js" lang="fr" <?=($lang == 'fr' ? 'active="true"' : '')?>></tab-label>
        <tab-label controls="docu-php-fr" label=".php" lang="fr"></tab-label>
        <tab-label controls="docu-js-en" label=".js" lang="en" <?=($lang == 'en' ? 'active="true"' : '')?>></tab-label>
        <tab-label controls="docu-php-en" label=".php" lang="en"></tab-label>
      </fieldset>

      <!--<a class="exemple" href="#documentation">▲ Navigation rapide</a>-->

      <!-- DOCUMENTATION JavaScript -->
      <article data-prog-language="js" lang="fr" id="docu-js-fr"><?=$docuJsFr?></article>
      <article data-prog-language="js" lang="en" id="docu-js-en"><?=$docuJsEn?></article>
      <!-- DOCUMENTATION PHP -->
      <article data-prog-language="php" lang="fr" id="docu-php-fr"><?=$docuPhpFr?></article>
      <article data-prog-language="php" lang="en" id="docu-php-en"><?=$docuPhpEn?></article>
    </section>

    <footer>
      <span>
        <span data-string="syntax-highlighting-source"><?=$Textes->getString('syntax-highlighting-source')?></span> <a href="https://parsedown.org/">Parsedown</a> & <a href="https://prismjs.com/">Prism.js</a>
      </span>
    </footer>

  </body>
</html>
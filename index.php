<?php
require_once __DIR__.'/../lib/dist/colori.php';
use colori\Couleur;

$commonDir = dirname(__DIR__, 2).'/_common';
require_once $commonDir.'/php/Translation.php';

$translation = new Translation(__DIR__.'/strings.json');
$httpLanguage = $translation->getLanguage();

$urlLang = isset($_GET['lang']) ? htmlspecialchars($_GET['lang']) : null;
$cookieLang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : null;
$lang = $urlLang ?: $cookieLang ?: $httpLanguage ?: 'en';
$translation->setLanguage($lang);

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

    <meta name="description" content="<?=$translation->get('description-site')?>">
    <meta property="og:title" content="Colori">
    <meta property="og:description" content="<?=$translation->get('description-site')?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://remiscan.fr/colori/">
    <meta property="og:image" content="https://remiscan.fr/mon-portfolio/projets/colori/og-preview.png">
    <link rel="canonical" href="https://remiscan.fr/colori/">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0">
    <meta name="color-scheme" content="light dark">

    <!-- ▼ Cache-busted files -->
    <!--<?php versionizeStart(); ?>-->

    <link rel="icon" type="image/png" href="/colori/demo/icons/icon-192.png">
    <link rel="apple-touch-icon" href="/colori/demo/icons/apple-touch-icon.png">
    <link rel="manifest" href="/colori/demo/manifest.json">

    <!-- Import map -->
    <script defer src="/_common/polyfills/adoptedStyleSheets.min.js"></script>
    <script>window.esmsInitOptions = { polyfillEnable: ['css-modules', 'json-modules'], shimMode: true }</script>
    <script defer src="/_common/polyfills/es-module-shims.js"></script>
    <script type="importmap-shim"><?php include 'import-map.json'; ?></script>

    <script src="/colori/demo/modules/main.js" type="module-shim"></script>

    <link rel="stylesheet" href="/colori/demo/page.css.php">

    <!--<?php versionizeEnd(__DIR__); ?>-->

    <style id="theme-variables">
      <?php themeSheetStart();

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
        --frame-color: <?=$codeColor->improveContrast($colorPreview, 2.5, as: 'background')->hsl()?>;
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
        --frame-color: <?=$codeColor->improveContrast($colorPreview, 2.5, as: 'background')->hsl()?>;
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

      <?php themeSheetEnd(closeComment: false); ?>
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
          data-label="github" aria-label="<?=$translation->get('github')?>">
          <svg viewBox="0 0 16 16" class="github-cat"><use href="#github-cat" /></svg>
          <span data-string="github"><?=$translation->get('github')?></span>
          <span>GitHub</span>
        </a>
        <!--<a href="https://github.com/Remiscan/colori/wiki/Documentation-pour-JavaScript-%28Fran%C3%A7ais%29" data-tappable lang="fr" data-prog-language="js"><?=$translation->get('titre-section-documentation')?></a>
        <a href="https://github.com/Remiscan/colori/wiki/Documentation-pour-PHP-%28Fran%C3%A7ais%29" data-tappable lang="fr" data-prog-language="php"><?=$translation->get('titre-section-documentation')?></a>
        <a href="https://github.com/Remiscan/colori/wiki/Documentation-for-JavaScript-%28English%29" data-tappable lang="en" data-prog-language="js"><?=$translation->get('titre-section-documentation')?></a>
        <a href="https://github.com/Remiscan/colori/wiki/Documentation-for-PHP-%28English%29" data-tappable lang="en" data-prog-language="php"><?=$translation->get('titre-section-documentation')?></a>-->
        <a href="?lang=fr" class="bouton-langage" data-tappable lang="fr" data-lang="fr" <?=($lang == 'fr' ? 'disabled' : '')?>>Français</a>
        <a href="?lang=en" class="bouton-langage" data-tappable lang="en" data-lang="en" <?=($lang == 'en' ? 'disabled' : '')?>>English</a>
        <theme-selector position="bottom"></theme-selector>
      </div>
    </header>

    <main>

      <section id="intro" class="no-title">
        <p data-string="documentation-intro-p1"><?=$translation->get('documentation-intro-p1')?></p>
        <p data-string="documentation-warning-js"><?=$translation->get('documentation-warning-js')?></p>
      </section>

      <section id="demo">
        <h2 data-string="titre-section-demo"><?=$translation->get('titre-section-demo')?></h2>
        
        <div id="saisie">
          <h3 class="no-separator">
            <label for="entree" data-string="demo-input-label"><?=$translation->get('demo-input-label')?></label>
          </h3>

          <div class="exemples-saisie exemples-valeurs">
            <span data-string="exemple-abbr"><?=$translation->get('exemple-abbr')?></span>
            <button type="button" class="exemple">pink</button>
            <button type="button" class="exemple">#4169E1</button>
            <button type="button" class="exemple">rgb(255, 127, 80)</button>
            <button type="button" class="exemple" data-label="more-examples" aria-label="<?=$translation->get('more-examples')?>">&nbsp;+&nbsp;</button>
          </div>

          <p class="instructions-exemples-fonctions off" data-hidden="true" data-string="instructions-demo"><?=$translation->get('instructions-demo')?></p>

          <div class="exemples-saisie exemples-fonctions off" data-hidden="true">
            <span data-string="exemple-abbr"><?=$translation->get('exemple-abbr')?></span>
            <button type="button" class="exemple">pink.invert()</button>
            <button type="button" class="exemple">#4169E1.scale(l, .5)</button>
            <button type="button" class="exemple">black.contrast(white)</button>
            <button type="button" class="exemple">orchid.interpolate(palegreen, 4, oklch)</button>
            <button type="button" class="exemple">rgb(255, 127, 80).scale(s, .5).blend(red.replace(a, .2))</button>
          </div>

          <input id="entree" class="h4" type="text" data-abbr="<?=$translation->get('exemple-abbr')?>"
                  autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                  placeholder="<?=$startColor->name()?>">

          <color-picker format="okhsl" color="<?=$startColor->name()?>"></color-picker>
        </div>

        <div id="resultats">
          <h3 class="no-separator" data-string="demo-resultats-titre"><?=$translation->get('demo-resultats-titre')?></h3>

          <div is="tab-list" group="tabs-results" aria-label="<?=$translation->get('tabs-results-label')?>" style="display: none">
            <button role="tab" aria-controls="results-named-formats"><?=$translation->get('tab-label-named-formats')?></button>
            <button role="tab" aria-controls="results-color-spaces"><?=$translation->get('tab-label-color-spaces')?></button>
          </div>

          <div class="donnees" id="results-values">
            <div class="format gradient" data-string="apercu-gradient"><?=$translation->get('apercu-gradient')?></div>

            <div class="format valeur">
              <pre class="language-css"><code class="language-css"></code></pre>
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
    </main>

    <?php /*
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

    <button type="button" class="show-documentation" data-string="button-show-documentation"><?=$translation->get('button-show-documentation')?></button>

    <aside class="nav-documentation nav-rapide" data-label="nav-documentation">
      <h2 class="titre-nav-rapide" data-string="nav-documentation"><?=$translation->get('nav-documentation')?></h2>
      <div lang="fr" data-prog-language="js"><?=$quicknavJsFr?></div>
      <div lang="en" data-prog-language="js"><?=$quicknavJsEn?></div>
      <div lang="fr" data-prog-language="php"><?=$quicknavPhpFr?></div>
      <div lang="en" data-prog-language="php"><?=$quicknavPhpEn?></div>
    </aside>

    <a id="documentation" aria-hidden="true"></a>
    <section class="documentation">
      <h1 data-string="titre-section-documentation"><?=$translation->get('titre-section-documentation')?></h1>

      <div is="tab-list" group="tabs-prog-language" aria-label="<?=$translation->get('tabs-group-language-label')?>">
        <button role="tab" aria-controls="doc-js">JavaScript</button>
        <button role="tab" aria-controls="doc-php">PHP</button>
      </div>

      <!--<a class="exemple" href="#documentation">▲ Navigation rapide</a>-->

      <!-- DOCUMENTATION JavaScript -->
      <article data-prog-language="js" lang="fr" id="docu-js-fr"><?=$docuJsFr?></article>
      <article data-prog-language="js" lang="en" id="docu-js-en"><?=$docuJsEn?></article>
      <!-- DOCUMENTATION PHP -->
      <article data-prog-language="php" lang="fr" id="docu-php-fr"><?=$docuPhpFr?></article>
      <article data-prog-language="php" lang="en" id="docu-php-en"><?=$docuPhpEn?></article>
    </section>
    */ ?>

    <footer>
      <span data-string="made-by"><?=$translation->get('made-by')?></span>&nbsp;<remiscan-logo animate></remiscan-logo>
    </footer>

  </body>
</html>
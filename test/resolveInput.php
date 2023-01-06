<!doctype html>

<!-- ▼ Cache-busted files -->
<!--<?php versionizeStart(); ?>-->

<!-- Import map -->
<script defer src="/_common/polyfills/es-module-shims.js"></script>
<script type="importmap"><?php include __DIR__.'/../import-map.json'; ?></script>

<link rel="stylesheet" href="../../lib/test/styles.css">

<!--<?php versionizeEnd(__DIR__); ?>-->

<script type="module">
  import Couleur from 'colori';
  import { BracketPairList, BracketFragments } from 'bracket-pairs';
  import { parseColorsInString, resolveInput, ArgumentList } from 'resolveInput';

  const argTests = [
    '',
    's, .5',
    'black, white',
    'palegreen, 4, oklch',
    'red.invert(), indigo',
    '4, [ word, 2 ]',
    '4, { precision: 2 }',
  ];

  for (const test of argTests) {
    const list = new ArgumentList(test);
    console.log(test, list.arguments, list.toString());
  }

  //console.log(bracketPairs('()'));
  //console.log(bracketPairs('red.scale(s, .5).blend(blue.replace(a, .2))'));
  //console.log(parseStringWithBrackets('dfdsf(fragment1)dsqdsqd(frag(fragment3)ment2)fdfs)'));
  //console.log(parseStringWithBrackets('red.scale(s, .5).blend(blue.replace(a, .2))'));
  //console.log(parseArgumentsList('blue, .5, rgb(26, 25, 24), "l"'));
  //console.log(parseArgumentsList(`(new Couleur('blue')).replace("a", .2)`));
  //console.log(parseColorsInString('red.blend(blue, .3)'));
  //console.log(parseColorsInString('red.scale(s, .5).blend(blue.replace(a, .2))'));

  const tests = [
    {
      string: 'pink',
      expected: new Couleur('pink')
    },
    {
      string: 'rgb(255, 99, 71).hex',
      expected: '#ff6347'
    },
    {
      string: 'pink.invert()',
      expected: (new Couleur('pink')).invert()
    },
    {
      string: '#4169E1.scale(l, .5)',
      expected: (new Couleur('#4169E1')).scale('l', .5)
    },
    {
      string: 'Couleur.contrast(black, white)',
      expected: Couleur.contrast('black', 'white')
    },
    {
      string: 'red.blend(blue, .3).hex',
      expected: '#b3004d'
    },
    {
      string: 'orchid.interpolate(palegreen, 4, oklch)',
      expected: [
        new Couleur('orchid'),
        new Couleur('rgb(255, 118.86, 159.41)'),
        new Couleur('rgb(255, 146.94, 108.1)'),
        new Couleur('rgb(255, 179.26, 52.45)'),
        new Couleur('rgb(219.17, 218.06, 65.7)'),
        new Couleur('palegreen')
      ]
    },
    {
      string: 'red.scale(s, .5).blend(blue.replace(a, .2))',
      expected: (new Couleur('red')).scale('s', .5).blend((new Couleur('blue')).replace('a', .2))
    },
    {
      string: 'Couleur.blend(red.invert(), indigo)',
      expected: (new Couleur('indigo'))
    },
    {
      string: 'cadetblue.replace(a, .5).toString(color-srgb, { precision: 4 })',
      expected: 'color(srgb 0.3725 0.6196 0.6275 / 0.5)'
    }
  ];

  for (const test of tests) {
    const result = resolveInput(test.string);
    const expected = test.expected;
    let success;
    if (expected instanceof Couleur) {
      success = Couleur.same(expected, result);
    } else if (Array.isArray(expected)) {
      success = expected.every((c, k) => Couleur.same(c, result[k]));
    } else {
      success = expected === result;
    }

    console.log(test.string, ' — got: ', result, ' — expected: ', expected, ' — success: ', success);
  }
</script>
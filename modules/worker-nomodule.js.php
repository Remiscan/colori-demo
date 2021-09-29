/*<?php
echo "*"."/";

function unModule(string $content): string {
  return preg_replace([
    '/export(.+?);/',
    '/export ?/',
    '/import(.+?);/'
  ], '', $content);
}

ob_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/colori/dist/colori.min.js';
$colori = ob_get_clean();

echo unModule($colori);

// Let's find colori's exports and name them properly.

preg_match('/export ?{(.*?)};?/', $colori, $exportMatches);
$exportString = $exportMatches[1];

$couples = explode(',', $exportString);
foreach ($couples as $couple) {
  [$short, $long] = explode(' as ', $couple);
  if ($long === 'default') $long = 'Couleur';
  echo "const $long = $short;";
}

ob_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/colori/demo/modules/colorResolution.js';
require_once $_SERVER['DOCUMENT_ROOT'] . '/colori/demo/modules/computeInterface.js';
require_once 'worker.js';
$imports = ob_get_clean();

echo unModule($imports);

echo "/"."*";
?>*/
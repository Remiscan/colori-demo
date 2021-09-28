/*<?php
echo "*"."/";
ob_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/colori/colori.min.js';
require_once $_SERVER['DOCUMENT_ROOT'] . '/colori/demo/modules/colorResolution.js';
require_once $_SERVER['DOCUMENT_ROOT'] . '/colori/demo/modules/computeInterface.js';
require_once 'worker.js';

$imports = ob_get_clean();
echo preg_replace([
  '/export(.+?);/',
  '/export ?/',
  '/import(.+?);/'
], '', $imports);

echo "/"."*";
?>*/
/*<?php
echo "*"."/";
ob_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/colori/colori.min.js';
require_once $_SERVER['DOCUMENT_ROOT'] . '/colori/demo/modules/colorResolution.js.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/colori/demo/modules/computeInterface.js.php';

$imports = ob_get_clean();
echo preg_replace([
  '/export default /',
  '/export /',
  '/import (.+?);/' ,
  '/\{ Utils, Graph \}/'
], '', $imports);

require_once 'worker.js.php';

echo "/"."*";
?>*/
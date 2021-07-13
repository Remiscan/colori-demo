/*<?php $commonDir = dirname(__DIR__, 3).'/_common';
require_once $commonDir.'/php/version.php'; ?>*/

let worker;
let supports = false;
const tester = { get type() { supports = true; }};
try { const w = new Worker('blob://', tester); } catch (e) {}

// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

if (supports) {
  /*<?php echo '*'.'/';
  echo "worker = new Worker('/colori/demo/modules/worker.js.php', { type: 'module' });";
  echo '/'.'*'; ?>*/
} else {
  /*<?php echo '*'.'/';
  echo "worker = new Worker('/colori/demo/modules/worker-nomodule.js.php');";
  echo '/'.'*'; ?>*/
}

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/

export async function messageWorker(instruction, data) {
  const chan = new MessageChannel();
  worker.postMessage(
    JSON.stringify({ instruction, data }),
    [chan.port2]
  );
  return new Promise(resolve => {
    chan.port1.onmessage = event => resolve(JSON.parse(event.data));
  });
}
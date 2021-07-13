/*<?php $commonDir = dirname(__DIR__, 3).'/_common';
require_once $commonDir.'/php/version.php'; ?>*/

let worker;
let supports = false;
const tester = { get type() { supports = true; }};
try { const w = new Worker('blob://', tester); } catch (e) {}

if (supports) {
  /*<?php echo '*'.'/';
  $version = version(dirname(__DIR__, 1), 'worker.js.php');
  echo "worker = new Worker('/colori/demo/modules/worker--$version.js.php', { type: 'module' });";
  echo '/'.'*'; ?>*/
} else {
  /*<?php echo '*'.'/';
  $version = version(dirname(__DIR__, 1), 'worker-nomodule.js.php');
  echo "worker = new Worker('/colori/demo/modules/worker-nomodule--$version.js.php');";
  echo '/'.'*'; ?>*/
}

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
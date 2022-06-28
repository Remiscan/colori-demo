let worker;
let supports = false; // module workers don't work with import maps yet :(
/*const tester = { get type() { supports = true; }};
try { const w = new Worker('blob://', tester); } catch (e) {}*/

// ▼ ES modules cache-busted grâce à PHP
/*<?php versionizeStart(); ?>*/

if (supports) {
  worker = new Worker('/colori/demo/modules/worker.js', { type: 'module' });
} else {
  worker = new Worker('/colori/demo/modules/worker-nomodule.js.php');
}

/*<?php versionizeEnd(__DIR__); ?>*/

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
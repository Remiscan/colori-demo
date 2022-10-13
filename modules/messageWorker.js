import ModuleWorkerShim from 'module-worker-shim';



let supports = false; // module workers don't work with import maps yet :(
const tester = { get type() { supports = true; }};
try { const w = new Worker('blob://', tester); } catch (e) {}

let worker;
if (supports) {
  console.log('Using module worker');
  worker = new ModuleWorkerShim(import.meta.resolve('interface-worker'));
} else {
  console.log('Using classic worker');
  worker = new Worker(import.meta.resolve('interface-worker-nomodule'));
}


let workerReady = false;
async function isWorkerReady() {
  if (workerReady) return true;
  
  return new Promise(async resolve => {
    let tries = 1000;
    while (!workerReady && tries > 0) {
      const chan = new MessageChannel();
      chan.port1.onmessage = event => {
        if (JSON.parse(event.data)?.ready) {
          resolve(workerReady = true);
        }
      };
      worker.postMessage(
        JSON.stringify({ instruction: 'is-ready' }),
        [chan.port2]
      );
      tries--;
      await new Promise(res => setTimeout(res, 50));
    }
  });
}


export async function messageWorker(instruction, data) {
  await isWorkerReady();
  const chan = new MessageChannel();
  worker.postMessage(
    JSON.stringify({ instruction, data }),
    [chan.port2]
  );
  return new Promise(resolve => {
    chan.port1.onmessage = event => resolve(JSON.parse(event.data));
  });
}
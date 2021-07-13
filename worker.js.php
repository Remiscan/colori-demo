// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { computeInterface, computeSliders } from './modules/computeInterface.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



let lastMessageTime = 0;

onmessage = event => {
  const time = Date.now();
  lastMessageTime = time;

  const port = event.ports[0];
  const { instruction, data } = JSON.parse(event.data);
  let returnData;

  switch (instruction) {
    case 'compute-interface': returnData = computeInterface(data); break;
    case 'compute-sliders': returnData = computeSliders(data); break;
  }

  if (time === lastMessageTime) port.postMessage(JSON.stringify(returnData));
}
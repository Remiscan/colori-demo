import { computeInterface, computeSliders } from 'computeInterface';



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
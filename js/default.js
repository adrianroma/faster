console.log("Start .......");
var connection = new WebSocket('wss://Admin:Admin@hrm-demo.pleedtech.com:5010/3/100');

connection.onmessage = function (evt) {
                  var received_msg = evt.data;
                  console.log(received_msg);
               };

connection.send('luis 1');


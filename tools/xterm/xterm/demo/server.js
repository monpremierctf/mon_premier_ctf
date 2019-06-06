var express = require('express');
var expressWs = require('express-ws');
var os = require('os');
var pty = require('node-pty');

// Replace URLPREFIX by /xtermjs_xxxx in __dirname + '/dist/client-bundle.js'
function replacePrefix(prefix, filein, fileout) {
  var fs = require('fs');
  if (fs.existsSync(fileout)) { return; }

  fs.readFile(filein, 'utf8', function (err,data) {
    if (err) {
      return console.log(err);
    }
    var result = data.replace(/URLPREFIX/g, prefix);

    fs.writeFile(fileout, result, 'utf8', function (err) {
      if (err) return console.log(err);
    });
  });
}

function startServer() {
  var prefix="/xtermjs_22";
  if (process.env.URLPREFIX != null) { prefix = process.env.URLPREFIX; }
  
  var app = express();
  expressWs(app);

  

  var terminals = {},
      logs = {};

  app.use(prefix+'/src', express.static(__dirname + '/../src'));

  app.get(prefix+'/', function(req, res){
    replacePrefix(prefix, __dirname + '/index.html', __dirname + '/index_ok.html');    
    res.sendFile(__dirname + '/index_ok.html');
  });

  app.get(prefix+'/style.css', function(req, res){
    res.sendFile(__dirname + '/style.css');
  });

  app.get(prefix+'/dist/client-bundle.js', function(req, res) {
    replacePrefix(prefix, __dirname + '/dist/client-bundle.js', __dirname + '/dist/client-bundle_ok.js');
    res.sendFile(__dirname + '/dist/client-bundle_ok.js');
  });

  app.post(prefix+'/terminals', function (req, res) {
    var cols = parseInt(req.query.cols),
        rows = parseInt(req.query.rows),
        term = pty.spawn(process.platform === 'win32' ? 'cmd.exe' : 'bash', [], {
          name: 'xterm-color',
          cols: cols || 80,
          rows: rows || 24,
          cwd: process.env.PWD,
          env: process.env
        });

    console.log('Created terminal with PID: ' + term.pid);
    terminals[term.pid] = term;
    logs[term.pid] = '';
    term.on('data', function(data) {
      logs[term.pid] += data;
    });
    res.send(term.pid.toString());
    res.end();
  });

  app.post(prefix+'/terminals/:pid/size', function (req, res) {
    var pid = parseInt(req.params.pid),
        cols = parseInt(req.query.cols),
        rows = parseInt(req.query.rows),
        term = terminals[pid];

    term.resize(cols, rows);
    console.log('Resized terminal ' + pid + ' to ' + cols + ' cols and ' + rows + ' rows.');
    res.end();
  });

  app.ws(prefix+'/terminals/:pid', function (ws, req) {
    var term = terminals[parseInt(req.params.pid)];
    console.log('Connected to terminal ' + term.pid);
    ws.send(logs[term.pid]);

    function buffer(socket, timeout) {
      let s = '';
      let sender = null;
      return (data) => {
        s += data;
        if (!sender) {
          sender = setTimeout(() => {
            socket.send(s);
            s = '';
            sender = null;
          }, timeout);
        }
      };
    }
    const send = buffer(ws, 5);

    term.on('data', function(data) {
      try {
        send(data);
      } catch (ex) {
        // The WebSocket is not open, ignore
      }
    });
    ws.on('message', function(msg) {
      term.write(msg);
    });
    ws.on('close', function () {
      term.kill();
      console.log('Closed terminal ' + term.pid);
      // Clean things up
      delete terminals[term.pid];
      delete logs[term.pid];
    });
  });

  var port = process.env.PORT || 3000,
      host = os.platform() === 'win32' ? '127.0.0.1' : '0.0.0.0';

  console.log('App listening to http://127.0.0.1:' + port+prefix);
  app.listen(port, host);
}

module.exports = startServer;

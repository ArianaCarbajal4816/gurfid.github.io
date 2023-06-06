const WebSocket = require('ws');

// Crea un servidor de WebSocket en el puerto 8080
const wss = new WebSocket.Server({ port: 8080 });

// Escucha eventos de conexión establecida
wss.on('connection', function connection(ws) {
  console.log('Nueva conexión establecida');

  // Escucha eventos de mensaje recibido
  ws.on('message', function incoming(message) {
    console.log('Mensaje recibido:', message);

    // Aquí puedes realizar la lógica necesaria para procesar los datos recibidos
    // y enviar actualizaciones a todos los clientes conectados cuando haya una
    // actualización de datos en el servidor

    // Ejemplo: Envía un mensaje de vuelta al cliente
    ws.send('Mensaje recibido por el servidor');
  });
});
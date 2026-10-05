const fs = require('fs');
const path = require('path');
const lightning = require('lightning');
const { authenticatedLndGrpc } = lightning;

let cert = process.env.LND_CERT_BASE64;
let macaroon = process.env.LND_MACAROON_BASE64;

const certPath = path.resolve(__dirname, '../tls.cert');
const macaroonPath = path.resolve(__dirname, '../admin.macaroon');

// Cargar el cert y macaroon desde el sistema de archivos si no están en variables de entorno
if (!cert && fs.existsSync(certPath)) {
  cert = fs.readFileSync(certPath).toString('base64');
}
if (!macaroon && fs.existsSync(macaroonPath)) {
  macaroon = fs.readFileSync(macaroonPath).toString('base64');
}

const socket = process.env.LND_GRPC_HOST;
if (!socket) {
  throw new Error('Debes proporcionar la variable de entorno LND_GRPC_HOST');
}

// Conectar al nodo LND
const { lnd } = authenticatedLndGrpc({
  cert,
  macaroon,
  socket,
});

module.exports = lnd;

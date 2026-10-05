const { createHash, randomBytes } = require('crypto');
const lightning = require('lightning');
const lnd = require('connect.js');

const createHoldInvoice = async ({ description, amount }) => {
  try {
    const randomSecret = () => randomBytes(32);
    const sha256 = buffer => createHash('sha256').update(buffer).digest('hex');
    
    // Crear el secreto y hash necesarios
    const secret = randomSecret();
    const hash = sha256(secret);

    // Establecer tiempo de expiración (1 hora)
    const expiresAt = new Date();
    expiresAt.setSeconds(expiresAt.getSeconds() + 3600);
    
    // Crear factura hold
    const { request, id } = await lightning.createHodlInvoice({
      lnd,
      id: hash, 
      description,
      tokens: amount, // en satoshis
      expires_at: expiresAt.toISOString(),
    });

    // Retornar la solicitud de pago y el secreto para su liquidación más adelante
    return { request, hash: id, secret: secret.toString('hex') };
  } catch (error) {
    console.error('Error al crear hold invoice:', error);
    throw error;
  }
};

// Liquidar la hold invoice una vez que el comprador confirme
const settleHoldInvoice = async ({ secret }) => {
  try {
    await lightning.settleHodlInvoice({ lnd, secret });
  } catch (error) {
    console.error('Error al liquidar hold invoice:', error);
    throw error;
  }
};

// Cancelar la hold invoice si es necesario
const cancelHoldInvoice = async ({ hash }) => {
  try {
    await lightning.cancelHodlInvoice({ lnd, id: hash });
  } catch (error) {
    console.error('Error al cancelar hold invoice:', error);
    throw error;
  }
};

module.exports = {
  createHoldInvoice,
  settleHoldInvoice,
  cancelHoldInvoice,
};

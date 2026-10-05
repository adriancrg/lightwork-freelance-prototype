const { createHoldInvoice } = require('./hold_invoice');

const lnInvoice = process.argv[2];
const amount = parseInt(process.argv[3]);

createHoldInvoice({ description: lnInvoice, amount }).then(response => {
    console.log(JSON.stringify(response));
}).catch(error => {
    console.error(error);
});

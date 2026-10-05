const { settleHoldInvoice } = require('./hold_invoice');

const secret = process.argv[2];

settleHoldInvoice({ secret }).then(() => {
    console.log('Hold Invoice settled');
}).catch(error => {
    console.error(error);
});

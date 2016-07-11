var config = require('./../config.js');
window = {opts: {'gethUrl': config.gethNode}};
var EToken = require('etoken-lib');
var account = EToken.Ambisafe.generateAccount('ELCOIN', process.argv[2]);
account.data.address = EToken.privateToAddress(account.data.private_key);
account.data.password = process.argv[2];
console.log(JSON.stringify(account.data));
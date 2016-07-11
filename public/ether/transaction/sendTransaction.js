var config = require('./../config.js');
window = {opts: {'gethUrl': config.gethNode}};
var EToken = require('etoken-lib');

var privateKey = process.argv[2][1] === 'x' ? process.argv[2].slice(2) : process.argv[2];
EToken.setPrivateKey(privateKey);
var sender = EToken.privateToAddress(privateKey);
var elcoin = EToken.web3.eth.contract([{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"name":"","type":"uint256"}],"type":"function"},{"constant":true,"inputs":[],"name":"txGasPriceLimit","outputs":[{"name":"","type":"uint256"}],"type":"function"},{"constant":true,"inputs":[],"name":"feeAddr","outputs":[{"name":"","type":"address"}],"type":"function"},{"constant":true,"inputs":[],"name":"absMaxFee","outputs":[{"name":"","type":"uint256"}],"type":"function"},{"constant":true,"inputs":[{"name":"_account","type":"address"}],"name":"balanceOf","outputs":[{"name":"","type":"uint256"}],"type":"function"},{"constant":true,"inputs":[],"name":"feePercent","outputs":[{"name":"","type":"uint256"}],"type":"function"},{"constant":false,"inputs":[{"name":"_to","type":"address[]"},{"name":"_value","type":"uint256[]"}],"name":"batchTransfer","outputs":[{"name":"","type":"bool"}],"type":"function"},{"constant":true,"inputs":[{"name":"_amount","type":"uint256"}],"name":"calculateFee","outputs":[{"name":"","type":"uint256"}],"type":"function"},{"constant":false,"inputs":[{"name":"_to","type":"address"},{"name":"_value","type":"uint256"}],"name":"transfer","outputs":[{"name":"","type":"bool"}],"type":"function"},{"constant":true,"inputs":[],"name":"absMinFee","outputs":[{"name":"","type":"uint256"}],"type":"function"},{"anonymous":false,"inputs":[{"indexed":true,"name":"_from","type":"address"},{"indexed":true,"name":"_to","type":"address"},{"indexed":false,"name":"_value","type":"uint256"}],"name":"Transfer","type":"event"}]).at('0x57d90b64a1a57749b0f932f1a3395792e12e7055');
var destination = process.argv[3][1] === 'x' ? process.argv[3] : '0x' + process.argv[3];
var amount = EToken.web3.toBigNumber(process.argv[4]).mul(Math.pow(10, config.baseUnit));

if (amount.decimalPlaces() !== 0)
{
    throw "Provide " + amount.decimalPlaces() + " less fractional digits in the amount: " + amount.valueOf() + " . Only " + config.baseUnit + " fractional digits allowed.";
}

var handler = function(err, tx)
{
    if(err)
    {
        throw err;
        process.exit(1);
    }
    console.log(tx);
    process.exit();
};

elcoin.transfer(destination, amount, {from: sender, gas: 900000, gasPrice: EToken.web3.toWei(20, 'gwei')}, handler);
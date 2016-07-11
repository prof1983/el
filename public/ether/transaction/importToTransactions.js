var Web3 = require('web3');
var config = require('./../config.js');

var web3 = new Web3();
web3.setProvider(new web3.providers.HttpProvider(config.gethNode));

var elcoin = web3.eth.contract([{"constant":false,"inputs":[{"name":"addr","type":"address"},{"name":"amount","type":"uint256"},{"name":"hash","type":"bytes32"},{"name":"time","type":"uint256"}],"name":"withdraw","outputs":[{"name":"res","type":"bool"}],"type":"function"},{"constant":false,"inputs":[{"name":"pOwner","type":"address"}],"name":"setOwner","outputs":[{"name":"_success","type":"bool"}],"type":"function"},{"constant":true,"inputs":[{"name":"","type":"address"}],"name":"balances","outputs":[{"name":"","type":"uint256"}],"type":"function"},{"constant":true,"inputs":[],"name":"getOwner","outputs":[{"name":"rv","type":"address"}],"type":"function"},{"constant":true,"inputs":[],"name":"getCaller","outputs":[{"name":"rv","type":"address"}],"type":"function"},{"constant":false,"inputs":[{"name":"pCaller","type":"address"}],"name":"setCaller","outputs":[{"name":"_success","type":"bool"}],"type":"function"},{"constant":false,"inputs":[{"name":"addr","type":"address"},{"name":"amount","type":"uint256"},{"name":"hash","type":"bytes32"},{"name":"time","type":"uint256"}],"name":"deposit","outputs":[{"name":"res","type":"bool"}],"type":"function"},{"constant":true,"inputs":[{"name":"addr","type":"address"}],"name":"getBalance","outputs":[{"name":"balance","type":"uint256"}],"type":"function"},{"inputs":[{"name":"pCaller","type":"address"}],"type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"name":"hash","type":"bytes32"},{"indexed":true,"name":"from","type":"address"},{"indexed":true,"name":"to","type":"address"},{"indexed":false,"name":"time","type":"uint256"},{"indexed":false,"name":"amount","type":"uint256"}],"name":"Transaction","type":"event"}]).at('0x276c5c6ca8507ed7bac085fc9b9521f4f54b58d3');

var event = elcoin.Transaction({to: process.argv[2]}, {fromBlock: process.argv[3], toBlock: process.argv[4]});
event.get(function(err, transactions)
{
    console.log(JSON.stringify(transactions, null, 4));
    event.stopWatching();
});


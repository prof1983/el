var Web3 = require('web3');
var config = require('./../config.js');

var web3 = new Web3();
web3.setProvider(new web3.providers.HttpProvider(config.gethNode));

var block = web3.eth.getBlock('latest');
console.log(block.number);
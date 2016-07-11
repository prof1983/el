<?php

namespace App\Console\Commands;

use App\Address;
use App\Transaction;
use Illuminate\Console\Command;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $latestBlock = `node public/ether/block/getLatestBlock.js`;
        if($latestBlock)
        {
            $addresses = Address::all();
            if(count($addresses))
            {
                $toBlock = (integer) trim($latestBlock);
                $fromBlock = $latestBlock - config('api.blockOffset');

                foreach($addresses as $address)
                {
                    $dataTo = `node public/ether/transaction/importToTransactions.js $address->address $fromBlock $toBlock\n`;
                    $toTransactions = json_decode($dataTo, true);
                    if(count($toTransactions))
                    {
                        foreach ($toTransactions as $toTransaction)
                        {
                            if(!Transaction::where('transaction_hash', $toTransaction['transactionHash'])->count())
                            {
                                $transaction = new Transaction();
                                $transaction->address_id = $address->id;
                                $transaction->transaction_hash = $toTransaction['transactionHash'];
                                $transaction->block_number = $toTransaction['blockNumber'];
                                $transaction->to = $toTransaction['args']['to'];
                                $transaction->received_amount = $toTransaction['args']['amount'] / 1000000;

                                $block = $toTransaction['blockNumber'];
                                $dataFrom = `node public/ether/transaction/importFromTransactions.js $block\n`;
                                $fromTransactions = json_decode($dataFrom, true);
                                if(count($fromTransactions))
                                {
                                    foreach ($fromTransactions as $fromTransaction)
                                    {
                                        if($fromTransaction['transactionHash'] == $toTransaction['transactionHash'] && $fromTransaction['args']['from'] != '0x0000000000000000000000000000000000000000')
                                        {
                                            $transaction->from = $fromTransaction['args']['from'];
                                            $transaction->send_amount = $fromTransaction['args']['amount'] / 1000000;
                                            $transaction->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

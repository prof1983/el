<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Str;

class APIController extends Controller
{
    public function test()
    {

    }

    public function getBalance($address)
    {
        $balance = `node ether/balance.js $address\n`;
        if($balance)
        {
            return \Response::json(['success' => true, 'error' => false, 'data' => rtrim($balance)]);
        }
        return \Response::json(['success' => false, 'error' => true, 'data' => 'Incorrect address format']);
    }


    public function getAddresses($apiKey)
    {
        if(config('api.apiKey') != $apiKey)
        {
            return \Response::json(['success' => false, 'error' => true, 'data' => 'Incorrect api key']);
        }

        $addresses = Address::select('address')->get();
        if(count($addresses))
        {
            foreach($addresses as $address)
            {
                $data[] = $address->address;
            }
            return \Response::json(['success' => true, 'error' => false, 'data' => $data]);
        }
        return \Response::json(['success' => true, 'error' => false, 'data' => false]);
    }

    public function createAddress($apiKey)
    {
        if(config('api.apiKey') != $apiKey)
        {
            return \Response::json(['success' => false, 'error' => true, 'data' => 'Incorrect api key']);
        }

        $password = Str::random(2);
        $data = `node ether/address/createAddress.js $password\n`;
        $account = json_decode($data, true);
        Address::create($account);

        return \Response::json(['success' => true, 'error' => false, 'data' => $account['address']]);
    }


    public function getTransactions($public, $apiKey)
    {
        if(config('api.apiKey') != $apiKey)
        {
            return \Response::json(['success' => false, 'error' => true, 'data' => 'Incorrect api key']);
        }

        $address = Address::where('address', $public)->first();
        if($address)
        {
            $transactions = $address->transactions()->select('transaction_hash', 'block_number', 'from', 'send_amount', 'to', 'received_amount')->get();
            if(count($transactions))
            {
                return \Response::json(['success' => true, 'error' => false, 'data' => $transactions]);
            }
            return \Response::json(['success' => true, 'error' => false, 'data' => false]);
        }
        return \Response::json(['success' => false, 'error' => true, 'data' => 'Address not found']);
    }

    public function sendTransaction(Request $request)
    {
        if(config('api.apiKey') != $request->apikey)
        {
            return \Response::json(['success' => false, 'error' => true, 'data' => 'Incorrect api key']);
        }

        $from = Address::where('address', $request->from)->first();
        if(!$from)
        {
            return \Response::json(['success' => false, 'error' => true, 'data' => 'Address not found']);
        }

        $data = `node ether/transaction/sendTransaction.js $from->private_key $request->to $request->amount\n`;

        if($data)
        {
            return \Response::json(['success' => true, 'error' => false, 'data' => rtrim($data)]);
        }

        return \Response::json(['success' => false, 'error' => true, 'data' => 'Send transaction fail']);
    }
}

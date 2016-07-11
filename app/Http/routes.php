<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['prefix' => 'api/v1', 'middleware' => 'api'], function()
{
    Route::get('balance/{address}', 'APIController@getBalance');

    Route::get('address/list/{apikey}', 'APIController@getAddresses');
    Route::get('address/{apikey}', 'APIController@createAddress');

    Route::get('transactions/{address}/{apikey}', 'APIController@getTransactions');
    Route::post('transactions', 'APIController@sendTransaction');
});

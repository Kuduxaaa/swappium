<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'App\Http\Controllers\Api', 'middleware' => 'json.response'], function () {     
    Route::group(['prefix' => 'auth', 'middleware' => 'throttle:10,3'], function () {
        Route::post('/login', 'LoginController@login')->name('api.login');
        Route::post('/register', 'RegisterController@register')->name('api.register');
    });
    
    Route::group(['prefix' => 'whitebit'], function () {
        Route::get('/assets', 'WhitebitController@assets')->name('api.whitebit.assets');
        Route::get('/assets/keys', 'WhitebitController@assetKeys')->name('api.whitebit.assets.keys');
        Route::get('/tickers', 'WhitebitController@tickers')->name('a');
        Route::get('/ticker/{ticker}', 'WhitebitController@getTicker')->name('api.whitebit.ticker');
        Route::get('/orderbook/{market}', 'WhitebitController@orderBook')->name('api.whitebit.orderbook');
        Route::get('/trades/recent/{market}', 'WhitebitController@recentTrades')->name('api.whitebit.recentTrades');
        Route::get('/fee', 'WhitebitController@getFees')->name('api.whitebit.fee');
        Route::get('/server/time', 'WhitebitController@getServerTime')->name('api.whitebit.fee');
        Route::get('/server/status', 'WhitebitController@getServerStatus')->name('api.whitebit.status');
        Route::get('/markets', 'WhitebitController@getMarkets')->name('api.whitebit.markets');
        Route::get('/markets/collateral', 'WhitebitController@getCollateralMarkets')->name('api.whitebit.collateral.markets');
        Route::get('/markets/futures', 'WhitebitController@getFutureMarkets')->name('api.whitebit.future.markets');
    });

    Route::get('/crypto/prices', 'CoingeckoController@getPrices')->name('api.crypto.prices');
});

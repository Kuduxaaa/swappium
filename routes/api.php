<?php

use App\Http\Controllers\MerchantController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'App\Http\Controllers\Api', 'middleware' => 'json.response'], function () {
    Route::group(['prefix' => 'auth', 'middleware' => 'throttle:10,3'], function () {
        Route::post('/login', 'AuthController@login')->name('api.login');
        Route::post('/register', 'AuthController@register')->name('api.register');
    });

    Route::group(['prefix' => 'whitebit'], function () {
        Route::get('/assets', 'WhitebitController@assets')->name('api.whitebit.assets');
        Route::get('/assets/keys', 'WhitebitController@assetKeys')->name('api.whitebit.assets.keys');
        Route::get('/tickers', 'WhitebitController@tickers')->name('api.whitebit.tickers');
        Route::get('/tickers/sort', 'WhitebitController@sortedTickers')->name('api.whitebit.sortedTickers');
        Route::get('/ticker/{ticker}', 'WhitebitController@getTicker')->name('api.whitebit.ticker');
        Route::get('/orderbook/{market}', 'WhitebitController@orderBook')->name('api.whitebit.orderbook');
        Route::get('/trades/recent/{market}', 'WhitebitController@recentTrades')->name('api.whitebit.recentTrades');
        Route::get('/fee', 'WhitebitController@getFees')->name('api.whitebit.fee');
        Route::get('/server/time', 'WhitebitController@getServerTime')->name('api.whitebit.fee');
        Route::get('/server/status', 'WhitebitController@getServerStatus')->name('api.whitebit.status');
        Route::get('/markets', 'WhitebitController@getMarkets')->name('api.whitebit.markets');
        Route::get('/markets/collateral', 'WhitebitController@getCollateralMarkets')->name('api.whitebit.collateral.markets');
        Route::get('/markets/futures', 'WhitebitController@getFutureMarkets')->name('api.whitebit.future.markets');
        Route::get('/markets/sorted', 'WhitebitController@getSortedMarkets')->name('api.whitebit.sorted.markets');
        Route::get('/klines', 'WhitebitController@getKlines')->name('api.whitebit.klines');
    });

    Route::get('/crypto/prices', 'CoingeckoController@getPrices')->name('api.crypto.prices');

    
    Route::post('/merchant/create', [MerchantController::class, 'createUserMerchant'])->name('merchant.create')->middleware('swappium.api');
    Route::get('/merchant/options', [MerchantController::class, 'getOptions'])->name('merchant.options')->middleware('swappium.api');
    Route::post('/merchant/generate', [MerchantController::class, 'generateLink'])->name('merchant.generate')->middleware('swappium.api');
    Route::get('/merchant/transaction/status', [MerchantController::class, 'getTransactionStatus'])->name('merchant.transaction.status')->middleware('swappium.api');

    Route::group(['middleware' => 'auth:api'], function() {   
        Route::get('/user/merchants', [MerchantController::class, 'getUserMerchants'])->name('user.merchants');
        Route::post('/user/merchant/delete', [MerchantController::class, 'deleteUserMerchant'])->name('user.merchant.delete');

        Route::get('/user/balance', 'BalanceController@getFiatBalance')->name('api.user.balance');
        Route::get('/user/wallets/{type}', 'WalletsController@myWallets')->name('api.user.wallets');
        Route::post('/user/wallet/balance', 'WalletsController@getBalance')->name('api.user.wallet.balance');
        Route::post('/user/wallet/add', 'WalletsController@addWallet')->name('api.user.wallet.add');

        Route::post('/user/balance/deposit', 'BalanceController@deposit')->name('api.user.balance.deposit');
        Route::post('/user/balance/withdraw', 'BalanceController@withdraw')->name('api.user.balance.withdraw');
        Route::post('/user/balance/withdraw/crypto', 'BalanceController@withdrawCrypto')->name('api.user.balance.withdraw.crypto');
        Route::get('/user/balance/history', 'BalanceController@history')->name('api.user.balance.history');
        Route::post('/user/balance/exchange', 'BalanceController@exchange')->name('api.user.balance.exchange');

        Route::post('/user/exchange/quick', 'BalanceController@quickExchange')->name('api.user.exchange.quick');
        
        Route::post('/user/logout', 'AuthController@logout')->name('api.user.logout');

        Route::post('/user/password/change', 'UserController@changePassword')->name('api.user.password.change');
        Route::get('/user/api/keys', 'UserController@getAPIs')->name('api.user.api.keys');
        Route::post('/user/api/key/generate', 'UserController@generateApiKey')->name('api.user.api.generate')->middleware('throttle:10,3');
        Route::post('/user/api/key/delete', 'UserController@deleteApiKey')->name('api.user.api.delete');
    });
});

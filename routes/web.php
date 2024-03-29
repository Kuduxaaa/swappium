<?php

use App\Http\Controllers\MerchantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/{any?}', function () {
    return view('app');
})->where('any', '^(?!api)(?!pay)(?!storage).*$');

Route::get('/pay/{transactionId}', [MerchantController::class, 'index'])->name('merchant');
Route::post('/pay/{transactionId}', [MerchantController::class, 'process'])->name('merchant.process');

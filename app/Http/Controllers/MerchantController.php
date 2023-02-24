<?php

namespace App\Http\Controllers;

use App\Classes\WhitebitPrivate;
use App\Models\ApiKeys;
use App\Models\Merchants;
use App\Models\MerchantSettings;
use App\Models\MerchantTransaction;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function generateLink(Request $request)
    {
        $request->validate([
            'address' => 'required',
            'return_link' => 'required|url',
            'amount' => 'required',
            'network' => 'required'
        ]);

        $key = $request->header('X-Swappium-Key');
        $api = ApiKeys::where('key', $key)->first();

        $address = $request->get('address');
        $return_link = $request->get('return_link');
        $amount = $request->get('amount');
        $network = $request->get('network');
        $unique_slug = rand(111111, 9999999) . time();

        $destination = Merchants::select('id', 'ticker', 'address')
                                   ->where('address', $address)
                                   ->where('api_key_id', $api->id)
                                   ->first();

        if (!$destination) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found'
            ]);
        }

        $ticker = $destination['ticker'];
        $address = $destination['address'];

        $generated = MerchantSettings::create([
            'merchant_id' => $destination->id,
            'return_link' => $return_link,
            'address' => $address,
            'ticker' => $ticker,
            'unique_slug' => $unique_slug,
            'amount' => $amount,
            'network' => $network,
        ]);

        return response()->json([
            'success' => true,
            'link' => env('APP_URL') . 'pay/' . $generated->unique_slug,
            'transaction_id' => $generated->unique_slug
        ]);
    }

    public function getOptions(Request $request) 
    {
        $key = $request->header('X-Swappium-Key');
        $api = ApiKeys::where('key', $key)->first();

        $options = Merchants::select('id', 'ticker', 'address')->where('api_key_id', $api->id)->get();

        if (!$options)
        {
            return response()->json([
                'success' => false,
                'message' => 'You don\'t have any address added yet.'
            ]);
        }

        return response()->json([
            'success' => true,
            'options' => $options
        ]);
    }

    public function index($transactionId) {
        $merchantSettings = MerchantSettings::where('unique_slug', $transactionId)->first();

        if (!$merchantSettings) {
            return redirect('/');
        }

        return view('merchant', [
            'data' => $merchantSettings
        ]);
    }

    public function process($transactionId, Request $request) 
    {
        $merchantSettings = MerchantSettings::where('unique_slug', $transactionId)->first();

        $ticker = $merchantSettings->ticker;
        $address = $merchantSettings->address;
        $amount = $merchantSettings->amount;
        $network = $merchantSettings->network;
        $id = $merchantSettings->unique_slug;
        $user = $request->user();

        MerchantTransaction::create([
            'merchant_id' => $merchantSettings->merchant_id,
            'status' => 'pending',
            'amount' => $amount,
            'ticker' => $ticker,
            'user_id' => $user->id
        ]);

        $data = WhitebitPrivate::withdrawCrypto($ticker, $amount, $address, $network, $user->email, $id);
        
        if (array_key_exists('errors', $data))
        {
            return response()->json([
               'success' => false,
               'data' => $data
            ]);
        }

        return redirect($merchantSettings['return_link']);
    }

    public function getTransactionStatus(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required'
        ]);

        $transactionId = $request->input('transaction_id');
        $merchantSettings = MerchantSettings::where('unique_slug', $transactionId)->first();

        if (!$merchantSettings)
        {
            return response()->json([
               'success' => false,
               'message' => 'Transaction doesn\'t exists'
            ]);
        }

        return WhitebitPrivate::getHistory(0, 1, $merchantSettings['unique_slug']);
    }
}

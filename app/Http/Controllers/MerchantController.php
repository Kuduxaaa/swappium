<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ApiKey;
use App\Models\Merchants;
use Illuminate\Http\Request;
use App\Classes\WhitebitPublic;
use App\Classes\WhitebitPrivate;
use App\Models\MerchantSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Redirector;
use App\Models\MerchantTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class MerchantController extends Controller
{
    public function generateLink(Request $request): JsonResponse
    {
        $request->validate([
            'address' => 'required',
            'return_link' => 'required|url',
            'amount' => 'required',
            'network' => 'required'
        ]);

        $key = $request->header('X-Swappium-Key');
        $api = ApiKey::where('key', $key)->first();

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

    public function getOptions(Request $request): JsonResponse
    {
        $key = $request->header('X-Swappium-Key');
        $api = ApiKey::where('key', $key)->first();

        if (!$api->enabled)
        {
            return response()->json([
                'success' => false,
                'message' => 'API key is not verified'
            ]);
        }

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

    public function index($transactionId): View|Factory|Redirector|RedirectResponse|Application
    {
        $merchantSettings = MerchantSettings::where('unique_slug', $transactionId)->first();

        if (!$merchantSettings) {
            return redirect('/');
        }

        return view('merchant', [
            'data' => $merchantSettings
        ]);
    }

    public function process($transactionId, Request $request): JsonResponse|Redirector|RedirectResponse|Application
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

        return [
            'success' => true,
            'details' => WhitebitPrivate::getHistory(0, 1, $merchantSettings['unique_slug'])['records'] ?? []
        ];
    }

    public function createUserMerchant(Request $request) 
    {
        $request->validate([
            'ticker' => 'required',
            'address' => 'required',
            'network' => 'required'
        ]);

        $key = $request->header('X-Swappium-Key');
        $api = ApiKey::where('key', $key)->first();
        $ticker = $request->input('ticker');
        $address = $request->input('address');
        $network = $request->input('network');

        $assets = WhitebitPublic::getAssetkeys();

        if (!in_array($ticker, $assets)) {
            return response()->json([
                'success' => false,
                'message' => 'Ticker not found'
            ]);
        }

        if (!$api) {
            return response()->json(['success' => false, 'message' => 'Provided API key is invalid'], 400);
        }

        if (!$api->enabled)
        {
            return response()->json([
                'success' => false,
                'message' => 'API key is not verified'
            ]);
        }
        
        Merchants::create([
            'api_key_id' => $api->id,
            'ticker' => $ticker,
            'network' => $network,
            'address' => $address
        ]);

        return response()->json(['success' => true, 'message' => 'Merchant successfully created']);
    }

    public function getUserMerchants(Request $request) 
    {
        $user = $request->user();

        $user_db = User::find($user->id);
        $api_keys = $user_db->ApiKey()->get();

        $merchants = [];
        foreach ($api_keys as $api_key) {
            $merchants = array_merge($merchants, $api_key->merchants()->get()->toArray());
        }

        return response()->json([
            'success' => true,
            'merchants' => $merchants
        ]);
    }

    public function deleteUserMerchant(Request $request) 
    {
        $request->validate(['merchant_id' => 'required']);
        $merchant_id = $request->input('merchant_id');
        $user = $request->user();

        $merchant = Merchants::where('id', $merchant_id)->first();

        if (!$merchant) {
            return response()->json([
                'success' => false,
                'message' => 'Merchant not found'
            ]);
        }

        $api = ApiKey::where('id', $merchant->api_key_id)->first();

        if (!$api || $api && $api->user_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Merchant not found'
            ]);
        }

        $merchant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Merchant deleted'
        ]);
    }
}

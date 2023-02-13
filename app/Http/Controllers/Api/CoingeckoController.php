<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoingeckoController extends Controller
{
    public function getPrices(Request $request) {
        $request->validate([
            'ids' => 'required',
            'vs_currencies' => 'required'
        ]);

        $ids = $request->ids;
        $vs_currencies = $request->vs_currencies;

        $params = array(
            'ids' => $ids,
            'vs_currencies' => $vs_currencies,
            'include_24hr_change' => true,
        );

        $query = http_build_query($params);

        try {
            $url = 'https://api.coingecko.com/api/v3/simple/price?' . $query;
            $content = file_get_contents($url);

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $msg = (strpos($msg, ':')) ? explode(':', $msg) : [$msg];
            $msg = $msg[3] ?? $msg[0];

            return [
                'success' => false,
                'message' => $msg
            ];
        }

        return json_decode($content, true);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Classes\WhitebitPublic;
use App\Classes\WhitebitPrivate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WhitebitController extends Controller
{
    public function assets()
    {
        return WhitebitPublic::getAssets();
    }

    public function assetKeys()
    {
        return WhitebitPublic::getAssetKeys();
    }

    public function tickers()
    {
        return WhitebitPublic::getTickers();
    }

    public function getTicker($ticker)
    {
        return WhitebitPublic::getTicker($ticker);
    }

    public function orderBook($market, Request $request) 
    {
        $level = $request->level;
        $limit = $request->limit;
        $market = strtoupper($market);

        return WhitebitPublic::getOrderBook($market, $level, $limit);
    }

    public function recentTrades($market, Request $request)
    {
        $type = $request->type;
        $market = strtoupper($market);

        return WhitebitPublic::getRecentTrades($market, $type);
    }

    public function getFees()
    {
        return WhitebitPublic::getFees();
    }

    public function getServerTime()
    {
        return WhitebitPublic::getServerTime();
    }

    public function getServerStatus() 
    {
        return WhitebitPublic::getServerStatus();
    }

    public function getCollateralMarkets()
    {
        return WhitebitPublic::getCollateralMarkets();
    }

    public function getFutureMarkets()
    {
        return WhitebitPublic::getFutureMarkets();
    }

    public function getMarkets()
    {
        return WhitebitPublic::getMarkets();
    }

    public function sortedTickers() {
        return WhitebitPublic::sortedTickers();
    }

    public function getKlines(Request $request) {
        $request->validate([
            'market' => 'required'
        ]);

        $market = $request->get('market');
        $limit = $request->get('limit') ?? 1440;
        $interval = $request->get('interval') ?? '1h';

        return WhitebitPublic::getKlines($market, $interval, $limit);
    }
}   

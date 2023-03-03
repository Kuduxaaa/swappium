<?php

namespace App\Classes;

class WhitebitPublic 
{
    /**
     * @var string
     */
    public static String $baseUrl = 'https://whitebit.com';


    /**
     * This function retrieves the assets status.
     * 
     *  @return array
     */

    public static function getAssets()
    {
        $data = self::makeRequest('/api/v4/public/assets');
        return json_decode($data, true);
    }


    /**
     * This function retrieves the assets keys.
     *
     * @return array
     */

    public static function getAssetkeys(){
        return array_keys((array) self::getAssets());
    }


    /**
     * This function retrieves a 24-hour pricing and volume summary for each market pair available on the exchange.
     * 
     * @return array
     */

    public static function getTickers()
    {
        $data = self::makeRequest('/api/v4/public/ticker');
        return json_decode($data, true);
    }


    public static function sortedTickers()
    {
        $tickers = self::getTickers();
        $output = [];

        foreach ($tickers as $key => $ticker) {
            if (!$ticker['isFrozen']) {
                $output[$key] = $ticker;
            }
        }

        uasort($output, function($a, $b) {
            return ($a['base_id'] > 0) ? $a['base_id'] - $b['base_id'] : $b['base_id'];
        });

        return $output;
    }


    public static function getKlines($market, $interval = '1h', $limit = 1440)
    {
        $data = self::makeRequest('/api/v1/public/kline?market=' . $market . '&interval=' . $interval . '&limit=' . $limit);

        return json_decode($data, true);
    }


    /**
     * This function retrieves a single market pair
     * 
     * @return array
     */

    static function getTicker($ticker)
    {
        $tickers = self::getTickers();
        $tickers_match = preg_grep('/^' . strtoupper($ticker) . '_/', array_keys($tickers));
        $output = [];

        foreach ($tickers_match as $key) 
        {
            $output[$key] = $tickers[$key];
        }

        if (count($output) <= 0)
        {
            $output = [
                'success' => false,
                'ticker' => [
                    'Ticker is not available'
                ]
            ];
        }

        return $output;
    }


    /**
     * This function returns the list of available futures markets.
     * 
     * @return array
     */

    static function getFutureMarkets()
    {
        $data = self::makeRequest('/api/v4/public/futures');
        return json_decode($data, true);
    }


    /**
     * This function retrieves the current order book as two arrays (bids / asks) with additional parameters.
     * 
     * @return array
     */

    static function getOrderBook($market, $limit='100', $level='1')
    {
        $data = self::makeRequest('/api/v4/public/orderbook/'. $market. '?limit='. $limit. '&level='. $level);
        return json_decode($data, true);
    }


    /**
     * This function retrieves the trades that have been executed recently on the requested market.
     * 
     * @param string $market
     * @param string $type (Can be buy or sell)
     * @return array
     */

    static function getRecentTrades($market, $type)
    {
        $data = self::makeRequest('/api/v4/public/trades/'. $market. '?type='. $type);
        return json_decode($data, true);
    }


    /**
     * This function retrieves all information about available spot and futures markets.
     * 
     * @return array
     */

    static function getMarkets()
    {
        $data = self::makeRequest('/api/v4/public/markets');
        return json_decode($data, true);
    }



    static function getSortedMarkets()
    {
        $data = self::getMarkets();        
        $grouped_data = [];

        foreach ($data as $item) 
        {
            if ($item['type'] === 'spot') 
            {
                $stock = $item['stock'];
                $money = $item['money'];

                if (!array_key_exists($stock, $grouped_data)) 
                {
                    $grouped_data[$stock] = [$money];
                } 
                else 
                {
                    array_push($grouped_data[$stock], $money);
                }
            }
        }

        return [
            'success' => true,
            'data' => $grouped_data
        ];
    }



    /**
     * This function retrieves the list of fees and min/max amount for deposits and withdraws
     * 
     * @return array
     */

    static function getFees()
    {
        $data = self::makeRequest('/api/v4/public/fee');
        return json_decode($data, true);
    }


    /**
     * This function retrieves the current server time.
     * 
     * @return array
     */

    static function getServerTime()
    {
        $data = self::makeRequest('/api/v4/public/time');
        return json_decode($data, true);
    }


    
    /**
     * This endpoint retrieves the current API life-state.
     * 
     * @return array
     */

    static function getServerStatus()
    {
        $data = self::makeRequest('/api/v4/public/ping');
        return json_decode($data, true);
    }


    /**
     * This endpoint returns the list of markets that available for collateral trading
     * 
     * @return array
     */
    static function getCollateralMarkets()
    {
        $data = self::makeRequest('/api/v4/public/collateral/markets');
        return json_decode($data, true);
    }


    /**
     * This method is used to make a request to the given url.
     * optionally you can pass a request method, post data array and headers array. 
     * 
     * @param mixed $endpoint
     * @param bool $private
     * @param mixed $privateData
     * @param string $method
     * @param array $headers
     * @param mixed $data
     * 
     * @return string
     */

    public static function makeRequest($endpoint, $private=false, $privateData=null, $method = 'GET', $headers = [], $data = null) 
    {
        $endpoint = ($endpoint[0] != '/') ? '/' . $endpoint : $endpoint;
        $url = self::$baseUrl . $endpoint;
        $ch = curl_init();

        if ($private)
        {
            $apiKey = env('WHITEBIT_API_KEY');
            $apiSecret = env('WHITEBIT_API_SECRET');

            if ($privateData) 
            {
                $data = json_encode($privateData, JSON_UNESCAPED_SLASHES);
                $payload = base64_encode($data);

                $signature = hash_hmac('sha512', $payload, $apiSecret);

                $headers[] = 'Content-Type: application/json';
                $headers[] = 'X-TXC-APIKEY: ' . $apiKey;
                $headers[] = 'X-TXC-PAYLOAD: ' . $payload;
                $headers[] = 'X-TXC-SIGNATURE: ' . $signature;
            }
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '0');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
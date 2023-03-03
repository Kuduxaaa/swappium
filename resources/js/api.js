class Api {
    axios;

    constructor(axios) {
        this.axios = axios;
    }

    getTickers() {
        return this.axios.get('whitebit/tickers', 'GET').then((response) => { return response.data });
    }

    getSortedTickers() {
        return this.axios.get('whitebit/tickers/sort', 'GET').then((response) => { return response.data });
    }

    getAssets() {
        return this.axios.get('whitebit/assets', 'GET').then((response) => { return response.data });
    }

    getTicker(ticker) {
        return this.axios.get(`whitebit/ticker/${ticker}`, 'GET').then((response) => { return response.data });
    }

    getKlines(market, interval='1h', limit=1440) {
        return this.axios.get(`whitebit/klines?market=${market}&interval=${interval}&limit=${limit}`, 'GET').then((response) => { return response.data });;
    }

    getHistory() {
        return this.axios.get('user/balance/history').then((response) => { return response.data });
    }

    getOrderbooks(market, level=0, limit=100) {
        return this.axios.get(`whitebit/orderbook/${market}?level=${level}&limit=${limit}`, 'GET').then((response) => { return response.data });
    }

    exchange($market, $amount, $price, $side) {
        return this.axios.post('user/balance/exchange', {
            market: $market, 
            amount: $amount,
            price: $price,
            side: $side

        }).then((response) => { 
            return response.data 
        });
    }

    quickExchange($market, $amount, $ra, $side) {
        return this.axios.post('user/exchange/quick', {
            market: $market, 
            amount: $amount,
            ra: $ra,
            side: $side

        }).then((response) => { 
            return response.data 
        });
    }

    getWallets(type) {
        return this.axios.get(`user/wallets/${type}`).then((response) => { return response.data });
    }

    getMarkets() {
        return this.axios.get('whitebit/markets', 'GET').then((response) => { return response.data });
    }

    getSortedMarkets() {
        return this.axios.get('whitebit/markets/sorted', 'GET').then((response) => { return response.data });
    }
}


export default Api;
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
}


export default Api;
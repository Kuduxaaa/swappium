class Api {
    axios;

    constructor(axios) {
        this.axios = axios;
    }

    getTickers() {
        return this.axios.get('whitebit/tickers', 'GET').then((response) => { return response.data });
    }

    getAssets() {
        return this.axios.get('whitebit/assets', 'GET').then((response) => { return response.data });
    }

    getTicker(ticker) {
        return this.axios.get(`whitebit/ticker/${ticker}`, 'GET').then((response) => { return response.data });
    }
}


export default Api;
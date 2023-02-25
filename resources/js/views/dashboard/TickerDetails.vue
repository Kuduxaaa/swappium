<template>
    <div class="dashboard">
        <aside>
            <SidebarComponent />
        </aside>

        <main>
            <h1><img :src="'/assets/img/icons/' + ticker.split('-')[0] + '_.png'" class="ci">{{ ticker.replace('-', ' / ').toUpperCase() }}</h1>
            <p class="text-secondary subtext">Here is your choosen coin statistics</p>

            <div class="wrapper">
                <div class="flex title">
                    <div class="price">
                        <p class="text-secondary null-m">Price</p>
                        <h3>${{ details['last_price'] }}</h3>
                    </div>

                    <div class="params flex">
                        <div class="param">
                            <p class="text-secondary null-m change">Change</p>
                            <h4 v-bind:class="(details['change'][0] == '-') ? 'down' : 'up'">{{ details['change'] }}</h4>
                        </div>

                        <div class="param">
                            <p class="text-secondary null-m change">Volume</p>
                            <h4>{{ formatter.format(details['quote_volume']) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="main">
                    <div id="chart"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="wrapper">
                        <div class="exchange flex">
                            <div class="buy">
                                <div class="flex field">
                                    <div class="input-group">
                                        <span>Price</span>
                                        <input type="text" autocomplete="off" class="input" :value="last_price">
                                    </div>

                                    <div class="input-group mt-3">
                                        <span>Amount</span>
                                        <input type="text" autocomplete="off" @keyup="calculateTotal" v-model="ex_amount" class="input" placeholder="Enter amount here">
                                    </div>

                                    <div class="input-group mt-3 mb-3">
                                        <span>Total</span>
                                        <input type="text" autocomplete="off" @keyup="calculateAmount" v-model="ex_total" class="input">
                                    </div>

                                    <button class="btn mt-4 w-100 buy-btn">Buy BTC</button>
                                </div>
                            </div>

                            <div class="sell">
                                <div class="flex field">
                                    <div class="input-group">
                                        <span>Price</span>
                                        <input type="text" autocomplete="off" class="input" :value="last_price">
                                    </div>

                                    <div class="input-group mt-3">
                                        <span>Amount</span>
                                        <input type="text" autocomplete="off" @keyup="calculateTotal" v-model="ex_amount" class="input" placeholder="Enter amount here">
                                    </div>

                                    <div class="input-group mt-3 mb-3">
                                        <span>Total</span>
                                        <input type="text" autocomplete="off" @keyup="calculateAmount" v-model="ex_total" class="input">
                                    </div>

                                    <button class="btn mt-4 w-100 sell-btn">Sell BTC</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="wrapper">
                        <div class="trades">
                            <div class="item flex">
                                <p class="col-lg-6 down">23,972.04</p>
                                <p class="col-lg-4">0.00218</p>
                                <p class="col-lg-2">0.00985</p>
                            </div>
                            <div class="item flex">
                                <p class="col-lg-6 down">23,972.04</p>
                                <p class="col-lg-4">0.00218</p>
                                <p class="col-lg-2">0.00985</p>
                            </div>
                            <div class="item flex">
                                <p class="col-lg-6 down">23,972.04</p>
                                <p class="col-lg-4">0.00218</p>
                                <p class="col-lg-2">0.00985</p>
                            </div>
                            <div class="item flex">
                                <p class="col-lg-6 down">23,972.04</p>
                                <p class="col-lg-4">0.00218</p>
                                <p class="col-lg-2">0.00985</p>
                            </div>
                            <div class="item flex">
                                <p class="col-lg-6 up">23,972.04</p>
                                <p class="col-lg-4">0.00218</p>
                                <p class="col-lg-2">0.00985</p>
                            </div>
                            <div class="item flex">
                                <p class="col-lg-6 up">23,972.04</p>
                                <p class="col-lg-4">0.00218</p>
                                <p class="col-lg-2">0.00985</p>
                            </div>
                            <div class="item flex">
                                <p class="col-lg-6 down">23,972.04</p>
                                <p class="col-lg-4">0.00218</p>
                                <p class="col-lg-2">0.00985</p>
                            </div>
                            <div class="item flex">
                                <p class="col-lg-6 down">23,972.04</p>
                                <p class="col-lg-4">0.00218</p>
                                <p class="col-lg-2">0.00985</p>
                            </div>
                            <div class="item flex">
                                <p class="col-lg-6 up">23,972.04</p>
                                <p class="col-lg-4">0.00218</p>
                                <p class="col-lg-2">0.00985</p>
                            </div>
                            <div class="item flex">
                                <p class="col-lg-6 down">23,972.04</p>
                                <p class="col-lg-4">0.00218</p>
                                <p class="col-lg-2">0.00985</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
</div>
</template>

<script setup>
import SidebarComponent from '../../components/dashboard/SidebarComponent.vue';
</script>

<script>
import ApexCharts from 'apexcharts';
import { ref } from 'vue';

export default {
    name: 'DashboardOrders',
    components: {
        SidebarComponent
    },

    data() {
        return {
            details: {
                'change': String(),
                'last_price': 0
            },

            last_price: 0,

            ticker: this.$route.params.ticker,
            formatter: new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
            }),

            ex_amount: ref(''),
            ex_total: ref(''),

            wss: null,
            candles: [],
            apchart: null
        }
    },

    methods: {
        getDetails() {
            let tickerKey = this.ticker.replace('-', '_').toUpperCase();

            this.$api.getTickers().then(result => {
                this.details = result[tickerKey];
            })
        },

        zoomChart (hour) {
            this.apchart.updateOptions({
                xaxis: {
                    type: 'datetime',
                    min: Date.now() - hour * 60 * 60 * 1000,
                    max: Date.now(),
                    labels: {
                        datetimeUTC: false
                    }
                }
            });
        },

        calculateAmount() {
            this.ex_amount = this.ex_total / this.last_price;
        },

        calculateTotal() {
            this.ex_total = this.ex_amount * this.last_price;
        },

        addRealtimeCandle(candle) {
            const newCandle = {
                x: candle[0] * 1000,
                y: [candle[1], candle[3], candle[4], candle[2]]
            };

            if (this.candles[this.candles.length - 1] !== newCandle) {
                if (this.candles.length > this.candles_limit) {
                    this.candles.shift();
                }

                this.candles.push(newCandle);
                this.apchart.updateSeries([{ data: this.candles }]);
            }
        },

        subscribeWhitebit() {
            this.wss = new WebSocket('wss://api.whitebit.com/ws');
            this.wss.onopen = () => {
                this.wss.send(
                    JSON.stringify({
                        id: 3,
                        method: 'candles_subscribe',
                        params: [
                            this.ticker.replace('-', '_').toUpperCase(),
                            3600
                        ]
                    })
                );
            }

            let market = this.ticker.replace('-', '').toUpperCase();
            if (this.candles.length == 0) {
                this.getKlines();
            }

            this.wss.onmessage = (event) => {
                let data = JSON.parse(event.data);

                if ('params' in data) {
                    this.details['last_price'] = data.params[0][2];
                    document.title = `${data.params[0][2]} | ${market} | Swappium`

                    if (this.last_price == 0) {
                        this.last_price = data.params[0][2];
                    }

                    this.addRealtimeCandle(data.params[0]);
                    // console.log(data.params[0]);
                }
            };
        },

        getKlines(interval = '1h') {
            this.$api.getKlines(this.ticker.replace('-', '_').toUpperCase(), interval).then(response => {
                this.candles = response.result.map(candle => ({
                    x: candle[0] * 1000,
                    y: [candle[1], candle[3], candle[4], candle[2]]
                }));

                this.apchart.updateSeries([{
                    data: this.candles
                }]);

                this.zoomChart(24)
            });
        },

        unsubscribeWhitebit() {
            this.wss.send(
                JSON.stringify({
                    id: 4,
                    method: 'candles_unsubscribe',
                    params: []
                })
            );

            this.wss.close();
        }
    },


    mounted() {
        var options = {
            series: [
                {
                    data: []
                }
            ],

            chart: {
                type: 'candlestick',
                height: 350
            },

            xaxis: {
                type: 'datetime',

                labels: {
                    style: {
                        colors: "#fff"
                    }
                },
            },


            yaxis: {
                tooltip: {
                    enabled: true
                },

                labels: {
                    style: {
                        colors: '#fff'
                    }
                }
            },

            tooltip: {
                theme: 'dark',

                style: {
                    background: '#1f2128',
                    color: '#fff'
                }
            },

            grid: {
                borderColor: '#E4E4E41C'
            }
        };

        this.apchart = new ApexCharts(document.querySelector("#chart"), options);
        this.apchart.render();

        this.getDetails();
        this.subscribeWhitebit();
    },

    destroyed() {
        this.unsubscribeWhitebit();
    },
}
</script>

<style scoped>
header {
    padding-bottom: 36px;
}

.input-group {
    display: flex;
}

.input-group span {
    width: 60px;
    line-height: 60px;
    margin-left: 28px;
}

.input-group input {
    max-width: calc(100% - 87px);
    text-align: right !important;
    padding: 18px 30px !important;
}

.sell-btn {
    font-weight: 600;
    font-size: 16px;
    background-color: rgb(246, 70, 93) !important;
    color: #fff;
    box-shadow: 0px 0px 10px rgba(246, 70, 94, 0.061);
}

.buy-btn {
    font-weight: 600;
    font-size: 16px;
    background-color: rgb(14, 203, 129) !important;
    color: #fff;
    box-shadow: 0px 0px 10px rgba(14, 203, 131, 0.206);
}

.sell-btn:hover {
    box-shadow: 0px 0px 10px rgba(246, 70, 94, 0.404);
}

.buy-btn:hover {
    box-shadow: 0px 0px 10px rgba(14, 203, 131, 0.52);
}

.trades .item p {
    font-size: 12px;
}

.trades {
    height: 100%;
    max-height: 400px;
    overflow: auto;
}

.apexcharts-menu.my-menu-class {
    background-color: #333;
}


.subtext {
    margin-top: 12px;
}

.main {
    margin-top: 1.6rem;
}

.ci {
    width: 50px;
    margin-right: 18px;
}

.wrapper {
    margin-top: 2.2rem;
}

main {
    padding: 40px 40px;
}

.param {
    margin: 0px 18px;
}

.price p {
    font-size: 18px;
    margin-bottom: 4px;
}

.param p {
    margin: 8px auto;
    font-size: 12px;
}

.flex.title {
    justify-content: space-between;
}

.buy, .sell {
    width: 50%;
}


.field {
    display: block;
    max-width: 90%;
    width: 100%;
}

.input-group {
    margin: 0px auto;
    background-color: #2c2f39;
    color: #eaecfd;
    border-radius: 24px;
    width: 100% !important;
}

.input-group input {
    background-color: transparent;
    border: none;
    border-radius: 24px;
    padding: 18px;
    text-align: left;
    width: 100%;
    color: #eaecfd;
}


@media only screen and (max-width: 600px) {
    main  {
        padding: 30px 12px;
    }
}
</style>

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
                        <h3>{{ formatter.format(details['last_price']) }}</h3>
                    </div>

                    <div class="params flex">
                        <div class="param">
                            <p class="text-secondary null-m change">Chnage</p>
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
        </main>
</div>
</template>

<script setup>
import SidebarComponent from '../../components/dashboard/SidebarComponent.vue';
</script>

<script>
import ApexCharts from 'apexcharts';

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

            ticker: this.$route.params.ticker,
            formatter: new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
            }),

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

        addRealtimeCandle(candle) {
            const newCandle = {
                x: candle[0] * 1000,
                y: [candle[1], candle[3], candle[4], candle[2]]
            };

            if (this.candles[this.candles.length - 1] !== newCandle) {
                this.candles.push(newCandle);
                this.apchart.updateSeries([{ data: this.candles }]);
                this.zoomChart(24);

                console.log(this.candles);
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

            if (this.candles.length == 0) {
                this.getKlines();
            }

            this.wss.onmessage = (event) => {
                let data = JSON.parse(event.data);

                if ('params' in data) {
                    this.details['last_price'] = data.params[0][2];

                    this.addRealtimeCandle(data.params[0]);
                    console.log(data.params[0]);
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

@media only screen and (max-width: 600px) {
    main {
        padding: 30px 12px;
    }
}
</style>
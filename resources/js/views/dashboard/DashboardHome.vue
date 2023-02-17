<template>
    <div class="dashboard">
        <aside>
            <SidebarComponent />
        </aside>

        <main>
            <div class="flex">
                <CoinInfoCard v-for="coin in coins" :coin="coin" class="coin-card" />
            </div>

            <div class="flex no-wrap wex">
                <QuickExchange class="quick-exchange" />

                <div class="flex wallets-list">
                    <BalanceCard class="balance-card" currency="USD" balance="1,487" image="icons/usdt_.png" />      
                    <BalanceCard class="balance-card" currency="BTC" balance="0.0002" image="icons/btc_.png" />      
                    <BalanceCard class="balance-card" currency="ETH" balance="0.34" image="icons/eth_.png" />      
                    <BalanceCard class="balance-card" currency="XRP" balance="0" image="icons/xrp_.png" />      
                </div> 
            </div> 
        </main>
    </div>
</template>

<script setup>
import SidebarComponent from '../../components/dashboard/SidebarComponent.vue';
import CoinInfoCard from '../../components/dashboard/CoinInfoCard.vue';
import QuickExchange from '../../components/dashboard/QuickExchange.vue';
import BalanceCard from '../../components/dashboard/BalanceCard.vue';
</script>

<script>
export default {
    components: {
        SidebarComponent,
        CoinInfoCard,
        QuickExchange,
        BalanceCard
    },

    data() {
        return {
            userData: this.$auth.getUser(),
            tickers: [],
            coins: {
                'BTC_USDT': {
                    prices: {
                        last: 'Please wait...'
                    },

                    name: 'Bitcoin',
                    shortName: 'BTC',
                    icon: '/assets/img/coins/btc.svg'
                },

                'ETH_USDT': {
                    prices: {
                        last: 'Please wait...'
                    },

                    name: 'Etherium',
                    shortName: 'ETH',
                    icon: '/assets/img/icons/eth_.png'
                },

                'LTC_USDT': {
                    prices: {
                        last: 'Please wait...'
                    },

                    name: 'Litecoin',
                    shortName: 'LTC',
                    icon: '/assets/img/coins/ltc.svg'
                }
            },

            socket: null,
        }
    },

    methods: {
        subscribe(markets) {
            this.socket = new WebSocket('wss://api.whitebit.com/ws');
            this.socket.onopen = () => {
                this.socket.send(
                    JSON.stringify({
                        'id': 6,
                        'method': 'market_subscribe',
                        'params': markets
                    }
                    ));
            };

            this.socket.onmessage = (event) => {
                var response = JSON.parse(event.data);

                if ('params' in response) {
                    this.coins[response['params'][0]]['prices'] = response['params'][1];
                }
            };
        },

        unsubscribe() {
            this.socket.send(JSON.stringify({
                'id': 7,
                'method': 'market_unsubscribe',
                'params': []
            }));

            this.socket.close();
        },
    },

    mounted() {
        this.subscribe(Object.keys(this.coins));
    }
}

</script>

<style scoped>
header {
    padding-bottom: 36px;
}

main {
    padding: 40px 40px;
}

.quick-exchange {
    width: 60%;
    margin-top: 21px;
    margin-left: 8px;
}


.balance-card {
    width: 22%;
    margin-top: 23px;
    margin-left: 18px;
    height: 400px;
}

.wallets-list {
    width: 40%;
}


@media only screen and (max-width: 600px) {
    main {
        padding: 30px 12px;
    }

    .balance-card {
        margin-left: 12px;
    }
}

@media only screen and (max-width: 963px) {
    .balance-card {
        width: calc(50% - 20px) !important;
    }
}

@media only screen and (max-width: 1271px) {
    .coin-card {
        width: 100% !important;
        margin: 12px 8px;
    }

    .quick-exchange {
        width: calc(100% - 19px);
    }

    .wex {
        display: block;
    }

    .wallets-list {
        width: 100% !important;
        flex-wrap: wrap;
    }
}


.coin-card {
    width: calc(100% / 3 - 20px);
    margin: 10px
}
</style>
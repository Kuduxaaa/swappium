<template>
    <div class="dashboard">
        <aside>
            <SidebarComponent />
        </aside>

        <main>
            <div class="flex">
                <div class="ptitle">
                    <h3>Today's Markets</h3>
                    <p class="text-secondary">Today's top cryptocurrency prices</p>
                </div>

                <div class="search wrapper">
                    <input type="text" placeholder="Search..." @keyup="searchInTickers">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Coin</th>
                            <th>Price</th>
                            <th>Change</th>
                            <th>Quote volume</th>
                            <th>Base volume</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td class="message-intable" v-if="(tickers.length <= 0)">{{ intable_msg }}</td>
                        <tr v-for="(ticker, index) in tickers">
                            <td>{{ index + 1 }}</td>
                            <td>
                                <img v-lazy="{ src: ticker.icon, loading: '/assets/img/icons/loading.svg', error: '/assets/img/icons/err_.png' }" class="coinicon" />
                                <router-link class="cname" :to="'/console/markets/' + ticker.name.replace('/', '-').toString().toLowerCase()">{{ ticker.name }}</router-link>
                            </td>
                            <td>{{ ticker.price }}</td>
                            <td>
                                <span :class="ticker.classname">{{ ticker.change }}</span>
                            </td>
                            <td>{{ ticker.quote_volume }}</td>
                            <td>{{ ticker.base_volume }}</td>
                            <td>
                                <router-link class="btn btn-primary-soft detail-btn" :to="'/console/markets/' + ticker.name.replace('/', '-').toString().toLowerCase()">Details</router-link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</template>

<script setup>
import SidebarComponent from '../../components/dashboard/SidebarComponent.vue';
</script>

<script>
export default {
    name: 'DashboardMarkets',
    components: {
        SidebarComponent
    },

    data() {
        return {
            tickers: [],
            search_results: [],
            intable_msg: 'Please wait...',
            markets: []
        }
    },

    methods: {
        searchInTickers (event) {
            var ticker = event.target.value.toUpperCase();
            this.tickers = this.search_results;

            this.tickers = this.tickers.filter(obj => obj.name.includes(ticker));

            if (this.tickers.length <= 0) {
                this.intable_msg = 'Nothing was found with this keyword'
            }
        },

        getMarkets() {
            this.$api.getMarkets().then(result => {
                for (let key in result) {
                    let data = result[key];

                    this.markets.push(data['name']);
                }
            });
        },

        loadTickers () {
            this.getMarkets();

            this.$api.getSortedTickers().then(response => {
                for (let key in response) {
                    var data = response[key];

                    if (this.markets.includes(key)) {
                        this.tickers.push({
                            name: key.replace('_', '/'),
                            icon: `/assets/img/icons/${key.split('_')[0].toLowerCase()}_.png`,
                            change: data['change'],
                            classname: (data['change'][0] == '-') ? 'down' : 'up',
                            price: data['last_price'],
                            quote_volume: data['quote_volume'],
                            base_volume: data['base_volume']
                        });
                    }
                }

                this.search_results = this.tickers;
            });
        }
    },

    mounted() {
        this.loadTickers();
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

.message-intable {
    width: 100%;
}

.search {
    width: 400px
}

.search input {
    background-color: #2c2f39;
    color: #eaecfd;
    border-radius: 24px;
    padding: 12px 20px;
    border: 1px solid #2c2f39;
    width: 100%;
    font-weight: 500;
}

.search input:focus {
    border: 1px solid var(--color-primary);
}

.detail-btn {
    margin-top: 16px !important;
}

.cname {
    text-decoration: none;
    color: var(--text-color)
}

td:nth-child(7) {
    padding-top: 0px !important;
    padding-bottom: 0px !important;
}

.table-responsive {
    margin-top: 36px;
}

.coinicon {
    width: 31px;
    height: 31px;
    margin: -5px 10px 0px 0px;
}

.flex {
    justify-content: space-between;
}

td:nth-child(2) {
    min-width: 240px;
}

.ptitle {
    padding: 14px 0px;
}

@media only screen and (max-width: 1105px) {
    .search {
        width: 100%;
    }
}

@media only screen and (max-width: 600px) {
    main {
        padding: 30px 12px;
    }
}
</style>

<template>
    <div class="wrapper">
        <h3 class="mt-1 text-bold">Crypto exchange</h3>
        <p class="text-secondary">You can quickly exchange cryptocurrency from here</p>

        <form class="ex-form" autocomplete="off">
            <div class="flex field">
                <div class="input-group">
                    <label for="send">You send</label>
                    <input type="text" autocomplete="no" class="input" @keyup="calculateB" v-model="amount" name="send">
                </div>

                <select v-model="send" @change="onAchange">
                    <option v-for="ticker in optionsA" :value="ticker"
                        :selected="(ticker == 'BTC') ? 'selected' : null">
                        {{ ticker }}</option>
                </select>
            </div>

            <div class="flex field">
                <div class="input-group">
                    <label for="get">You get</label>
                    <input type="text" autocomplete="off" disabled class="input" name="get"
                        :value="calcedVal">
                </div>

                <select v-model="get" @change="calculateB">
                    <option v-for="(ticker, key) in optionsB" :value="ticker">
                        {{ ticker }}</option>
                </select>

            </div>

            <div class="button-container">
                <button type="button" @click="handleSubmit('buy')" class="btn btn-primary mt-4 w-48 mx-2">Buy {{ this.send }} with {{ this.get }}</button>
                <button type="button" @click="handleSubmit('sell')" class="btn btn-primary mt-4 w-48 mx-2">Sell {{ this.send }} to buy {{ this.get }}</button>
            </div>
        </form>

    </div>
</template>

<script>
    import {
        ref
    } from 'vue';

    export default {
        name: 'InstantExchange',

        data() {
            return {
                send: ref('BTC'),
                get: ref('EUR'),
                amount: ref(0.1),
                calcedVal: 0,

                optionsA: [],
                optionsB: [],
                marketData: null,
                tickerDetails: null
            }
        },

        methods: {
            loadMarket() {
                this.$api.getSortedMarkets().then(result => {
                    if (result.success) {
                        let keys = Object.keys(result.data);

                        this.optionsA = keys;
                        this.optionsB = result.data['BTC'];
                        this.marketData = result.data;
                        this.calculateB();

                    } else {
                        this.$snackbar.add({
                            type: 'error',
                            text: 'Failed to fetch the market data!'
                        });
                    }
                });
            },

            onAchange(event) {
                this.optionsB = this.marketData[event.target.value];
                this.get = this.optionsB[0];
                this.calculateB();
            },

            calculateB() {
                let market = `${this.send}_${this.get}`;

                this.$api.getTicker(this.send).then(result => {
                    this.tickerDetails = result[market];
                    this.calcedVal = this.tickerDetails['last_price'] * this.amount;
                });
            },

            calculatePercentage(value, percentage) {
                const decimal = percentage / 100;
                const result = value * decimal;
                
                return result;
            },

            handleSubmit(side) {
                let market = `${this.send}_${this.get}`;
                let amount = this.amount;

                if (isNaN(amount) || amount == 0) {
                    this.$snackbar.add({
                        type: 'error',
                        text: 'Please enter correct amount!'
                    });

                    return;
                }

                amount = (side == 'buy') ? this.calcedVal : this.amount;

                this.$api.quickExchange(market, amount, this.calcedVal, side).then(result => {
                    if ('errors' in result) {
                        for (const key in result.errors) {
                            this.$snackbar.add({
                                type: 'error',
                                text: result.errors[key][0]
                            });
                        }

                        return;
                    } else if ('success' in result && !result.success) {
                        this.$snackbar.add({
                            type:'error',
                            text: (result.message) ? result.message : 'Something went wrong'
                        });

                        return;
                    }

                    this.$snackbar.add({
                        type:'success',
                        text: 'Exchange successful!'
                    });
                });
            }
        },

        mounted() {
            this.loadMarket();
        }
    }
</script>

<style scoped>
    .wrapper {
        width: 100%;
    }

    .btn {
        width: 100%;
    }

    .button-container {
        display: flex;
        justify-content: center;
        /* centers the container horizontally */
        margin: 0 -1rem;
        /* negates the margin added to the buttons */
    }


    form .field {
        margin: 18px auto;
        flex-wrap: nowrap;
        margin-top: 24px;
        border-radius: 24px;
        box-shadow: 0px 0px 10px #0000000f;
    }

    .input-group {
        display: flex;
        background-color: #2c2f39;
        color: #eaecfd;
        border-top-left-radius: 24px;
        border-bottom-left-radius: 24px;
        height: 66px;
    }

    .input-group input.input {
        text-align: right;
        color: #dadada;
        font-size: 20px;
        font-weight: 600;
        background-color: transparent;
        border: none;
        padding: 12px 20px;
        width: calc(100% - 103px);
    }


    .input-group label {
        color: #a4a4a4;
        font-size: 14px;
        min-width: 66px;
        line-height: 64px;
        margin-left: 30px;
    }

    .input-group input.input:focus {
        outline: none;
    }

    select {
        max-width: 160px;
        width: 100%;
        background-color: #2c2f39;
        color: #fff;
        font-weight: 600;
        border: none;

        margin-left: 6px;
        border-top-right-radius: 24px;
        border-bottom-right-radius: 24px;
        padding: 0px 18px;

        -moz-appearance: none;
        -webkit-appearance: none;
        appearance: none;
        cursor: pointer;
    }

    .ex-form {
        margin-top: 32px;
    }

    @media only screen and (max-width: 720px) {
        .button-container {
            display: block;
        }

        .button-container button {
            width: calc(100% - 30px);
            margin: 8px 16px !important;
        }
    }

    @media only screen and (max-width: 600px) {
        label {
            display: none;
        }

        input {
            width: 100% !important;
        }

        select {
            max-width: 105px;
        }
    }
</style>

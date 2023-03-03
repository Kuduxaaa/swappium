<template>
    <div class="wrapper">
        <h3 class="mt-1 text-bold">Crypto exchange</h3>
        <p class="text-secondary">You can quickly exchange cryptocurrency from here</p>

        <form class="ex-form" autocomplete="off">
            <div class="flex field">
                <div class="input-group">
                    <label for="send">You send</label>
                    <input type="text" autocomplete="no" class="input" name="send" value="0.1">
                </div>

                <select v-model="send">
                    <option v-for="ticker in options" :value="ticker" :selected="(ticker == 'BTC') ? 'selected' : null">
                        {{ ticker }}</option>
                </select>
            </div>

            <div class="flex field">
                <div class="input-group">
                    <label for="get">You get</label>
                    <input type="text" autocomplete="off" disabled class="input" name="get" :value="calcedVal">
                </div>

                <select v-model="get">
                    <option v-for="ticker in exoptions" :value="ticker.name">{{ ticker.name }}</option>
                </select>
            </div>

            <div class="button-container">
                <button type="button" @click="buy('sell')" class="btn btn-primary mt-4 w-48 mx-2">Buy {{ this.selected_market[1] }} with {{ this.selected_market[0] }}</button>
                <button type="button" @click="buy('buy')" class="btn btn-primary mt-4 w-48 mx-2">Buy {{ this.selected_market[0] }} with {{ this.selected_market[1] }}</button>
            </div>
        </form>

    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    name: 'QuickExchange',

    data() {
        return {
            options: [],
            exoptions: [],
            calcedVal: 0,

            send: ref('BTC'),
            get: ref('EUR'),
            sendVal: 0.1,
            getVal: 0.1,
            selected_market: ['BTC', 'EUR'],
        }
    },

    methods: {
        loadTickers() {
            this.$api.getTickers().then((tickers) => {
                for (let key in tickers) {
                    let name = key.split('_')[0];

                    if (!this.options.includes(name) && !tickers[key].isFrozen) {
                        this.options.push(name);
                    }
                }
            })
        },

        roundedNumber(num) {
            let strNum = num.toString();
            let decimalIndex = strNum.indexOf('.');
            let decimalPart = strNum.slice(decimalIndex + 1);
            let trailingZeros = decimalPart.match(/0{3,}$/);

            if (trailingZeros) {
                return num.toFixed(0);
            } else {
                return num.toFixed(3);
            }
        },

        calculate(amount) {
            let market = `${this.send}_${this.get}`;

            this.$api.getTicker(this.send).then(response => {
                let ticker = response[market.toUpperCase()];
                let last_price;

                if (!ticker['isFrozen']) {
                    last_price = ticker['last_price'];
                    this.calcedVal = this.roundedNumber(amount * last_price);
                }
            });

            return true;
        },

        valueChangeHandler(event) {
            this.calculate(event.target.value);
            this.sendVal = event.target.value
        },

        changeGet(event) {
            this.selected_market[1] = event.target.value
            this.calculate(this.sendVal);
            console.log(this.selected_market);
        },

        changeHandler() {
            this.exoptions = [];
            this.$api.getTicker(this.send).then(result => {
                for (let key in result) {
                    if (!result[key].isFrozen) {
                        this.exoptions.push({
                            value: key,
                            name: key.split('_')[1],
                            last_price: result[key]['last_price']
                        });
                    }
                }

                this.get = ref(this.exoptions[0]['name']);
                this.selected_market = this.exoptions[0].value.split('_');
                this.calculate(0.1);

                console.log(this.selected_market);
            });
        },

        submitExchange() {
            console.log('okay okay :D');
        },

        buy(side) {
            let ticker = this.selected_market.join('_');

            console.log(`I want to ${side} ${this.sendVal} ${ticker.split('_')[0]} ${(side == 'sell') ? 'to buy' : 'with'} ${ticker.split('_')[1]}`);

            this.$api.quickExchange(ticker, this.calcedVal, side).then(result => {
                console.log(result);
            })
        }
    },

    mounted() {
        this.loadTickers();
        this.changeHandler(null)
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
  justify-content: center; /* centers the container horizontally */
  margin: 0 -1rem; /* negates the margin added to the buttons */
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
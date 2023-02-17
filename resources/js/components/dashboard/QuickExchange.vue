<template>
    <div class="wrapper">
        <h3 class="mt-1 text-bold">Crypto exchange</h3>
        <p class="text-secondary">You can quickly exchange cryptocurrency from here</p>

        <form @submit.prevent="submitExchange" class="ex-form" autocomplete="off">
            <div class="flex field">
                <div class="input-group">
                    <label for="send">You send</label>
                    <input type="text" @keyup="valueChangeHandler" autocomplete="no" class="input" name="send" value="0.1">
                </div>

                <select @change="changeHandler" v-model="send">
                    <option v-for="ticker in options" :value="ticker" :selected="(ticker == 'BTC') ? 'selected' : null">
                        {{ ticker }}</option>
                </select>
            </div>

            <div class="flex field">
                <div class="input-group">
                    <label for="get">You get</label>
                    <input type="text" autocomplete="off" disabled class="input" name="get" :value="calcedVal">
                </div>

                <select v-model="get" @change="changeGet">
                    <option v-for="ticker in exoptions" :value="ticker.name">{{ ticker.name }}</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Exchange</button>
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

        changeGet() {
            this.calculate(this.sendVal);
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
                this.calculate(0.1);
            });
        },

        submitExchange() {
            console.log('okay okay :D');
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
    padding: 14px !important;
    border-radius: 24px;
    margin-top: 18px;
    box-shadow: 0px 2px 21px #355dff3b;
}

.btn:hover {
    box-shadow: 0px 2px 21px var(--color-primary);
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
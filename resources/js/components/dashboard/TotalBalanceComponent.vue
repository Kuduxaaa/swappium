<template>
    <div class="wrapper mt-3 text-center">
        <img src="/assets/img/wallet.svg" alt="Balance" class="mt-4 balance-image mb-4">

        <h4 class="mt-3">Total balance</h4>
        <p class="text-secondary mb-4">Here you can see your total balance</p>

        <p class="balance">{{ balances.btc }} BTC</p>
        <p class="balance-usd">{{ balances.usd }} USD</p>

        <router-link to="/console/wallets" class="btn btn-primary mt-4 mb-4">Go to wallets</router-link>
    </div>
</template>

<script>
export default {
    name: 'TotalBalanceComponent',

    data() {
        return {
            timerInterval: null,
            balances: {
                btc: '0.000',
                usd: '0.000'
            }
        }
    },

    methods: {
        getBalance(ticker) {
            this.$axios.post('user/wallet/balance', {ticker: ticker}).then(response => {
                if ('success' in response.data) {
                    if (response.data.success && 'amount' in response.data) {
                        this.balances[ticker.toLowerCase()] = response.data.amount.toString();

                    } else {
                        this.$snackbar.add({
                            type: 'error',
                            text: response.data.message ?? 'Woops... something went wrong'
                        });

                        return 0;
                    }
                }
            }).catch(error => {
                this.$snackbar.add({
                    type: 'error',
                    text: 'Woops... something went wrong'
                });

                console.log(error);
            })
        }
    },

    mounted() {
        this.getBalance('BTC')
        this.getBalance('USD')
    }
}
</script>

<style scoped>
.balance-image {
    max-width: 200px;
    width: 100%;
    /* height: 100px; */
}

.balance-usd {
    color: #4FBF67;
    font-size: 22px;
    margin-bottom: 28px;
}


.balance {
    font-size: 32px;
    margin: 38px 0 0 !important
}
</style>

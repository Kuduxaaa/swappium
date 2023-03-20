<template>
    <div class="wrapper mt-3 text-center">
        <img src="/assets/img/wallet.svg" alt="Balance" class="mt-4 balance-image mb-4">

        <h4 class="mt-3">Total balance</h4>
        <p class="text-secondary mb-4">Here you can see your total balance</p>

        <p class="balance">0.000 BTC</p>
        <p class="balance-usd">0.00 USD</p>

        <router-link to="/console/wallets" class="btn btn-primary mt-4 mb-4">Go to wallets</router-link>
    </div>
</template>

<script>
export default {
    name: 'TotalBalanceComponent',

    data() {
        return {
            timerInterval: null
        }
    },

    methods: {

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

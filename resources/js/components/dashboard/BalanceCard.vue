<template>
    <div class="wallet-wrapper">        
        <div class="wallet">
            <img :src="'/assets/img/' + image" class="coin-icon" draggable="false">

            <div class="balance">
                <h1 class="text-center"><span v-if="currency == 'USD'">$</span>{{ balance }}</h1>
                <p class="text-center">{{ currency }}</p>
            </div>
        </div>

        <div class="w-type text-center text-bold">
            <button class="btn btn-primary-soft nomax px-4">Deposit now</button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'BalanceCard',

    props: {
        image: String,
        currency: String,
        balance: String
    }
}
</script>

<style scoped>
.wallet-wrapper {
    background-color: var(--dark-light);
    padding: 30px;
    border-radius: 28px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
    -webkit-box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
    position: relative;
}

h4 {
    margin: 0px;
}

.dollar-sym {
    line-height: 54px;
}

.wallet {
    margin-top: 18px;
}

.w-type button {
    width: 100%;
    border-bottom-left-radius: 28px;
    border-bottom-right-radius: 28px;
}

.w-type {
    position: absolute;
    bottom: 0px;
    display: block;
    width: 100%;
    left: 0px;
}

.balance {
    justify-content: center;
}

.coin-icon {
    width: 70px;
    text-align: center;
    margin: 12px auto;
    display: block;
    margin-bottom: 28px;
}

.balance h1 {
    font-size: 22px;
}

.balance p {
    font-family: monospace;
}
</style>
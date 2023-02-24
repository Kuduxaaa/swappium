<template>
    <div class="dashboard">
        <aside>
            <SidebarComponent />
        </aside>

        <main>
            <h1>Wallets</h1>

            <div class="wallets">
                <div class="wallet">
                    <img src="/assets/img/icons/btc_.png" alt="Bitcoin" class="icon">

                    <h5 class="mt-4 text-center">Bitcoin wallet</h5>
                    <p class="text-secondary text-center">0.00312373 BTC</p>

                    <div class="flex field">
                        <div class="input-group">
                            <input type="text" autocomplete="off" disabled class="input" name="get"
                                value="1F1tAaz5x1HUXrCNLbtMDqcw6o5GNn4xqX">
                            <span><i class="bi bi-clipboard"></i></span>
                        </div>
                    </div>
                </div>

                <div class="wallet">
                    <img src="/assets/img/icons/eth_.png" alt="Etherium " class="icon">

                    <h5 class="mt-4 text-center">Etherium wallet</h5>
                    <p class="text-secondary text-center">0.312373 ETH</p>

                    <div class="flex field">
                        <div class="input-group">
                            <input type="text" autocomplete="off" disabled class="input" name="get"
                                value="0x71C7656EC7ab88b098defB751B7401B5f6d8976F">
                            <span><i class="bi bi-clipboard"></i></span>
                        </div>
                    </div>
                </div>

                <div class="wallet">
                    <img src="/assets/img/icons/xmr_.png" alt="Monero" class="icon">

                    <h5 class="mt-4 text-center">Monero wallet</h5>
                    <p class="text-secondary text-center">6.312373 XMR</p>

                    <div class="flex field">
                        <div class="input-group">
                            <input type="text" autocomplete="off" disabled class="input" name="get"
                                value="Ad87deD4gEe8dG57Ede4eEg5dREs4d5e8f4e">
                            <span><i class="bi bi-clipboard"></i></span>
                        </div>
                    </div>
                </div>

                <div class="wallet">
                    <img src="/assets/img/icons/trx_.png" alt="Tron" class="icon">

                    <h5 class="mt-4 text-center">Tron wallet</h5>
                    <p class="text-secondary text-center">0.2373 TRX</p>

                    <div class="flex field">
                        <div class="input-group">
                            <input type="text" autocomplete="off" disabled class="input" name="get"
                                value="Ad87deD4gEe8dG57Ede4eEg5dREs4d5e8f4e">
                            <span><i class="bi bi-clipboard"></i></span>
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
export default {
    name: 'DashboardWallets',
    components: {
        SidebarComponent
    },


}
</script>

<style scoped>
header {
    padding-bottom: 36px;
}

main {
    padding: 40px 40px;
}

.field {
    display: block;
    max-width: 315px;
    width: 100%;
}

.wallets {
    margin: 32px auto;
    display: flex;
    flex-wrap: wrap;
}

.input-group {
    margin: 0px auto;
    background-color: #2c2f39;
    color: #eaecfd;
    border-radius: 24px;
}

span i {
    font-size: 19px;
    line-height: 60px;
    cursor: pointer;
}

.input-group input {
    background-color: transparent;
    border: none;
    border-radius: 24px;
    padding: 18px;
    text-align: left;
    max-width: 86%;
    width: 100%;
}

.wallet {
    background-color: var(--dark-light);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
    -webkit-box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
    border-radius: 24px;
    max-width: 380px;
    padding: 30px 10px 30px 10px;
    margin: 12px;
    width: calc(23% + 7px);
}

h5 {
    margin-top: 40px !important;
}

.wallet .text-secondary {
    margin-bottom: 38px !important;
}

.wallet img {
    margin: 14px auto;
    width: 100px;
    display: block;
}

.flex {
    display: flex;
}


@media only screen and (max-width: 1680px) {
    .wallet {
        max-width: calc(100% / 2 - 24px);
        width: 100%;
    }

    .wallets {
        width: 100% !important;
    }
}


@media only screen and (max-width: 1040px) {
    main {
        padding: 30px 12px;
    }

    .wallet {
        max-width: 100%;
    }
}</style>
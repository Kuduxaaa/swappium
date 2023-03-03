<template>
    <div class="dashboard">
        <aside>
            <SidebarComponent />
        </aside>

        <main>
            <div class="d-flex justify-content-between">
                <h1>Wallets</h1>

                <div class="tab-wrapper d-flex mx-4">
                    <span @click="switchTab" :class="'tab' + (!tabPos ? ' selected' : '')">Crypto</span>
                    <span @click="switchTab" :class="'tab' + (tabPos ? ' selected' : '')">Fiat</span>
                </div>
            </div>

            <div class="wallets">
                <div class="wallet" v-for="(wallet, key) in  wallets">
                    <img v-bind:src="'/assets/img/icons/' + wallet.ticker.toLowerCase() + '_.png'" :alt="wallet.name"
                        class="icon">

                    <h5 class="mt-4 text-center">{{ wallet.name }}</h5>
                    <p class="text-secondary text-center">{{ wallet.amount }} {{ wallet.ticker }}</p>

                    <div class="flex field" v-if="!tabPos">
                        <div class="input-group">
                            <input type="text" autocomplete="off" disabled class="input" name="get"
                                v-bind:value="wallet.address">

                            <span @click="copy(key)"><i class="bi bi-clipboard"></i></span>
                        </div>
                    </div>
                    <div v-else class="text-center">
                        <button @click="depositFiat(key)" class="btn btn-primary-soft mx-2">Deposit now</button>
                        <button @click="depositFiat(key)" class="btn btn-primary-soft mx-2">Withdraw</button>
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

    data() {
        return {
            wallets: [],
            tabPos: false
        }
    },

    methods: {
        getWallets(type) {
            this.$api.getWallets(type).then(response => {
                this.wallets = response
            });
        },

        copy(key) {
            navigator.clipboard.writeText(this.wallets[key].address);

            this.$snackbar.add({
                type: 'success',
                text: 'Address copied in the clipboard!'
            });
        },

        switchTab() {
            this.tabPos = !this.tabPos
            this.wallets = [];

            this.getWallets((!this.tabPos) ? 'crypto' : 'fiat');
        },

        depositFiat(key) {
            this.$swal({
                title: 'Deposit',
                html: `
                <p class="text-secondary dep-sub">From here you can top up your deposit, note that our fee is 1%</p>
                <div class="flex field">
                    <div class="input-group dep">
                        <input type="number" placeholder="Enter amount" autocomplete="off" class="input-dep" style="width: 100%" name="get">
                    </div>
                </div>
                `,
                showCancelButton: false,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
            }).then((result) => {
                const amount = document.querySelector('.input-dep').value;

                if (result.isConfirmed) {
                    this.$swal.fire({
                        title: 'Get started!',
                        html: '<p class="text-secondary mb-4">Now a tab will open where you can top up the balance, follow the instructions to the end</p>',
                        timer: 2000,
                        timerProgressBar: true,

                        didOpen: () => {
                            this.$swal.showLoading();
                        },
                        willClose: () => {
                            clearInterval(this.timerInterval)
                        }
                    });

                    this.$axios.post('user/balance/deposit', {
                        ticker: this.wallets[key].ticker,
                        amount: amount,
                        provider: JSON.parse(this.wallets[key].provider)[0]

                    }).then(response => {
                        if ('success' in response.data && !response.data.success) {
                            this.$snackbar.add({
                                type: 'error',
                                text: response.data.message
                            });

                            return;
                        } else if ('errors' in response.data) {
                            for (const key in response.data.errors) {
                                this.$snackbar.add({
                                    type: 'error',
                                    text: response.data.errors[key][0]
                                });
                            }
                        }

                        if ('url' in response.data) {
                            window.open(response.data.url, '_blank', 'width=500,height=800');
                            return
                        }

                    }).catch(error => {
                        this.$snackbar.add({
                            type: 'error',
                            text: error.response.data.message
                        });

                        return;
                    });
                }
            })
        }
    },

    mounted() {
        this.getWallets('crypto');
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


.btn-primary-soft {
    box-shadow: none;
}

.tab-wrapper {
    width: 280px;
    background-color: #1f2128;
    border-radius: 24px;
    display: flex;
    justify-content: space-between;
    box-shadow: 0px 0px 10px #00000021;
}

.tab-wrapper .tab {
    text-align: center;
    border-radius: 24px;
    width: 50%;
    line-height: 56px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    user-select: none;
}

.tab-wrapper .tab.selected {
    background-color: var(--color-primary);
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
}
</style>
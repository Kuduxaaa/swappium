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
                <div class="wallet add-wallet" @click="addWallet" v-if="!tabPos">
                    <div class="container h-100">
                        <div class="row align-items-center h-100">
                            <div class="col-6 mx-auto">
                                <div class="jumbotron">
                                    <p class="display-1 text-center mt-4"><i class="bi bi-plus"></i></p>
                                    <p class="text-center bold">Add new wallet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                

                <div class="wallet" v-for="(wallet, key) in  wallets">
                    <img v-lazy="{ src: '/assets/img/icons/' + wallet.ticker.toLowerCase() + '_.png', loading: '/assets/img/icons/loading.svg', error: '/assets/img/icons/err_.png' }" class="icon" />

                    <h5 class="mt-4 text-center">{{ wallet.name }}</h5>
                    <p class="text-secondary text-center">{{ wallet.amount }} {{ wallet.ticker }}</p>

                    <div class="flex field" v-if="!tabPos">
                        <div class="input-group">
                            <input type="text" autocomplete="off" disabled class="input" name="get"
                                v-bind:value="wallet.address">

                            <span @click="copy(key)"><i class="bi bi-clipboard"></i></span>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button v-if="tabPos" @click="depositFiat(key)" class="btn btn-primary-soft mx-2">Deposit
                            now</button>
                        <button @click="withdraw(key)" class="btn btn-primary-soft mx-2">Withdraw</button>
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
            wallet_type: 'crypto',
            tabPos: false,
            assets: {}
        }
    },

    methods: {
        getWallets(type) {
            this.$api.getWallets(type).then(response => {
                this.wallets = response;
                this.wallet_type = type;
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

        withdraw(key) {
            this.$api.getAssets().then(response => {
                let ticker = response[this.wallets[key]['ticker']];
                let selectHtml = '';

                if (ticker['can_withdraw'] && ticker['networks']) {
                    const options = ticker.networks.withdraws;
                    const selectElement = document.createElement('select');

                    selectElement.setAttribute('name', 'network');
                    selectElement.style = 'width: 100%;background: #2c2f39;color: #fff;font-weight: 600;border: none;border-radius: 24px;padding: 19px 18px;-moz-appearance: none;-webkit-appearance: none;appearance: none;cursor: pointer;';

                    options.forEach((option) => {
                        const optionElement = document.createElement('option');
                        optionElement.value = option;
                        optionElement.text = option;

                        if (option == ticker['networks']['default']) {
                            optionElement.setAttribute('selected', 'selected');
                        }

                        selectElement.appendChild(optionElement);
                    });

                    selectHtml = selectElement.outerHTML;
                }

                this.$swal({
                    title: 'Withdraw',
                    html: (this.wallet_type == 'fiat') ? `
                <p class="text-secondary dep-sub">From here you can top up your deposit, note that our fee is 1%</p>
                <div class="flex field">
                    <div class="input-group dep">
                        <input type="number" placeholder="Enter your card number" autocomplete="off" class="input-dep" style="width: 100%" name="card_num">
                    </div>
                </div>

                <div class="flex field">
                    <div class="input-group dep">
                        <input type="text" placeholder="Enter your phone number" autocomplete="off" class="input-dep" style="width: 100%" name="phone_num">
                    </div>
                </div>

                <div class="flex field">
                    <div class="input-group dep">
                        <input type="number" placeholder="Amount" autocomplete="off" class="input-dep" style="width: 100%" name="amount">
                    </div>
                </div>
                ` : `<p class="text-secondary dep-sub">From here you can top up your deposit, note that our fee is 1%</p>
                <div class="flex field">
                    <div class="input-group dep">
                        <input type="text" placeholder="Enter wallet address" autocomplete="off" class="input-dep" style="width: 100%" name="wallet">
                    </div>
                </div>

                <div class="flex field">
                    <div class="input-group dep">
                        <input type="text" placeholder="Enter amount" autocomplete="off" class="input-dep" style="width: 100%" name="amount">
                    </div>
                </div>

                <div class="flex field">
                    ${selectHtml}
                </div>
                <p style="text-align:left;margin-bottom:18px;">You can choose a custom network</p>
                `,
                    showCancelButton: false,
                    confirmButtonText: 'Submit',
                }).then((result) => {
                    if (this.wallet_type == 'crypto') {
                        const amount = document.querySelector('input[name="amount"]').value;
                        const wallet = document.querySelector('input[name="wallet"]').value;
                        const network = document.querySelector('select[name="network"]').value;

                        if (result.isConfirmed) {
                            this.$axios.post('user/balance/withdraw/crypto', {
                                ticker: this.wallets[key].ticker,
                                amount: amount,
                                address: wallet,
                                network: network

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

                                if ('sucess' in response.data) {
                                    this.$snackbar.add({
                                        type: 'success',
                                        text: 'Withdraw request successfully created'
                                    });
                                }

                            }).catch(error => {
                                this.$snackbar.add({
                                    type: 'error',
                                    text: error.response.data.message
                                });

                                return;
                            });
                        }
                    } else {
                        const card_num = document.querySelector('input[name="card_num"]').value;
                        const phone_num = document.querySelector('input[name="phone_num"]').value;
                        const amount = document.querySelector('input[name="amount"]').value;

                        if (result.isConfirmed) {
                            this.$axios.post('user/balance/withdraw', {
                                ticker: this.wallets[key].ticker,
                                amount: amount,
                                card_number: card_num,
                                phone: phone_num

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

                                if ('sucess' in response.data) {
                                    this.$snackbar.add({
                                        type: 'success',
                                        text: 'Withdraw request successfully created'
                                    });
                                }

                            }).catch(error => {
                                this.$snackbar.add({
                                    type: 'error',
                                    text: error.response.data.message
                                });

                                return;
                            });
                        }
                    }
                });
            });
        },

        addWallet() {
            if (Object.keys(this.assets).length === 0) {
                this.$api.getAssets().then(assets => {
                    if (typeof assets === 'object') {
                        for (const asset in assets) {
                            if (assets.hasOwnProperty(asset) &&
                                assets[asset].can_withdraw &&
                                !assets[asset].is_memo &&
                                assets[asset].can_deposit &&
                                assets[asset].networks) {
                                this.assets[asset] = assets[asset];
                            }
                        }
                        
                        this.addWalletStepTwo();
                    } else {
                        console.error("assets is not an object");
                    }
                }).catch(error => {
                    console.log(error);
                });
            } else {
                for (const asset in this.assets) {
                    if (this.assets.hasOwnProperty(asset) &&
                        this.assets[asset].can_withdraw &&
                        !assets[asset].is_memo &&
                        this.assets[asset].can_deposit &&
                        this.assets[asset].networks) {
                        this.assets[asset] = this.assets[asset];
                    } else {
                        delete this.assets[asset];
                    }
                }
                this.addWalletStepTwo();
            }
        },



        addWalletStepTwo() {
            console.log(this.assets)
            let select = document.createElement('select');
            select.setAttribute('name', 'ticker')
            select.style = 'width: 100%;background: #2c2f39;color: #fff;font-weight: 600;border: none;border-radius: 24px;padding: 19px 18px;-moz-appearance: none;-webkit-appearance: none;appearance: none;cursor: pointer;';

            for (let key in this.assets) {
                let option = document.createElement('option');
                option.setAttribute('value', key);
                option.textContent = key;
                select.appendChild(option);
            }

            this.$swal({
                    title: 'New wallet',
                    html: `
                <p style="text-align:left;margin-top:27px;color: #9b9caa;margin-bottom: -7px;margin-left: 8px;">Select crypto currency</p>
                <div class="flex field">
                    ${select.outerHTML}
                </div>
                `,
                    showCancelButton: false,
                    confirmButtonText: 'Submit'
                }).then((result) => {
                    const currency = document.querySelector('select[name="ticker"]').value;

                    if (result.isConfirmed) {
                        this.$axios.post('user/wallet/add', {ticker: currency}).then(result => {
                            console.log(result.data);

                            this.$snackbar.add({
                                type: (result.data.success) ? 'success' : 'error',
                                text: ('message' in result.data) ? result.data.message : 'Something went wrong'
                            });
                            
                            this.getWallets('crypto');

                        }).catch((error) => {
                            console.log(error.response);   
                        })
                    }
                })

        },

        depositFiat(key) {
            this.$api.getAssets().then(response => {
                let ticker = response[this.wallets[key]['ticker']];
                let selectHtml = '';

                if (ticker['can_withdraw'] && ticker['providers']) {
                    const options = ticker.providers.withdraws;
                    const selectElement = document.createElement('select');

                    selectElement.setAttribute('name', 'provider');
                    selectElement.style = 'width: 100%;background: #2c2f39;color: #fff;font-weight: 600;border: none;border-radius: 24px;padding: 19px 18px;-moz-appearance: none;-webkit-appearance: none;appearance: none;cursor: pointer;';

                    options.forEach((option) => {
                        const optionElement = document.createElement('option');
                        optionElement.value = option;
                        optionElement.text = option;

                        if (option == 'VISAMASTER') {
                            optionElement.setAttribute('selected', 'selected');
                        }

                        selectElement.appendChild(optionElement);
                    });

                    selectHtml = selectElement.outerHTML;
                }

                this.$swal({
                    title: 'Deposit',
                    html: `
                <p class="text-secondary dep-sub">From here you can top up your deposit, note that our fee is 1%</p>
                <div class="flex field">
                    <div class="input-group dep">
                        <input type="number" placeholder="Enter amount" autocomplete="off" class="input-dep" style="width: 100%" name="amount">
                    </div>
                </div>
                <div class="flex field">
                    ${selectHtml}
                </div>
                <p style="text-align:left;margin-bottom:18px;">You can choose a custom network</p>
                `,
                    showCancelButton: false,
                    confirmButtonText: 'Submit'
                }).then((result) => {
                    const amount = document.querySelector('input[name="amount"]').value;
                    const provider = document.querySelector('select[name="provider"]').value;

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
                            provider: provider

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

            });
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

.add-wallet {
    cursor: pointer;
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
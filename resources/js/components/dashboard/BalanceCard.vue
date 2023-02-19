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
            <button @click="alert" class="btn btn-primary-soft nomax px-4">Deposit now</button>
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
    },

    data() {
        return {
            timerInterval: null
        }
    },

    methods: {
        alert() {
            this.$swal({
                title: 'Deposit',
                html: `
                <p class="text-secondary dep-sub">From here you can top up your deposit, note that our fee is 1%</p>
                <div class="flex field">
                    <div class="input-group dep">
                        <select class="deposit">
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <option value="UAH">UAH</option>
                            <option value="RUB">RUB</option>
                        </select>
                        
                        <input type="number" placeholder="Enter amount" autocomplete="off" class="input-dep" name="get">
                    </div>
                </div>
                `,
                showCancelButton: false,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
            }).then((result) => {
                const ticker = document.querySelector('.deposit').value;
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
                        ticker: ticker,
                        amount: amount
                        
                    }).then(response => {
                        if ('success' in response.data && !response.data.success) {
                            this.$snackbar.add({
                                type: 'error',
                                text: response.data.message
                            });

                            return;
                        }

                        if ('url' in response.data) {
                            window.open(response.data.url, '_blank', 'width=500,height=800');
                            return
                        }

                        console.log(response.data);

                    }).catch(error => {
                        if (error.response.data.hasOwnProperty('message')) {
                            this.$snackbar.add({
                                type: 'error',
                                text: error.response.data.message
                            });
                        }

                        return;
                    });
                }
            })
        }
    }
}
</script>

<style>
select.deposit {
    max-width: 95px;
    width: 100%;
    background-color: #2c2f39;
    color: #fff;
    font-weight: 600;
    border: none;

    margin-left: 6px;
    padding: 0px 18px;

    -moz-appearance: none;
    -webkit-appearance: none;
    appearance: none;
    cursor: pointer;

    border-right: 8px solid #282b33;
    border-top-left-radius: 24px;
    border-bottom-left-radius: 24px;
    text-align: center;
}

.swal2-title {
    margin-top: 12px;
}

.swal2-actions {
    margin-bottom: 14px !important;
    margin-top: 4px !important;
    margin: 0 auto;
    width: 92% !important;
}

.swal2-confirm {
    border-radius: 24px !important;
    width: 100%;
}

.dep-sub {
    margin-bottom: 44px;
}

.swal2-popup.swal2-modal.swal2-show {
    width: 630px;
}


.field {
    margin: 18px auto;
    flex-wrap: nowrap;
    margin-top: 24px;
    box-shadow: 0px 0px 10px #0000000f;
}

.input-group.dep {
    display: flex;
    background-color: #2c2f39;
    color: #eaecfd;
    height: 66px;
    border-radius: 24px;
}

.swal2-popup.swal2-modal.swal2-show {
    padding: 18px;
}

.input-group input.input-dep {
    color: #dadada;
    background-color: transparent;
    border: none;
    padding: 12px 20px;
    width: calc(100% - 101px);
    -moz-appearance: textfield;
    -webkit-appearance: textfield;
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
</style>

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
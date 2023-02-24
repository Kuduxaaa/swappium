<template>
    <div class="wrapper mt-3 text-center">
        <img src="/assets/img/wallet.svg" alt="Balance" class="mt-4 balance-image mb-4">

        <h4 class="mt-3">Total balance</h4>
        <p class="text-secondary mb-4">Here you can see your total balance</p>

        <p class="balance">0.16231428</p>
        <p class="balance-usd">3,700.96 USD</p>

        <button @click="alert" class="btn btn-primary mt-4 mb-4">Withdraw</button>
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
<template>
    <div class="dashboard">
        <aside>
            <SidebarComponent />
        </aside>

        <main>
            <h1>Settings</h1>
            <p class="mb-4 text-secondary">From here you can manage your profile informartion and access to API</p>

            <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                        type="button" role="tab" aria-controls="home" aria-selected="true">Profile</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                        type="button" role="tab" aria-controls="profile" aria-selected="false">API Access</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button @click="getUserMerchants" class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                        type="button" role="tab" aria-controls="contact" aria-selected="false">Merchants</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row">
                        <div class="col-md-8">
                            <form class="mt-4" method="post">
                                <div class="form-group mb-3">
                                    <input type="email" placeholder="Email address" required="" autofocus=""
                                        class="form-control p-3 shadow-sm px-4" v-model="info['email']" disabled>
                                </div>

                                <div class="form-group mb-3">
                                    <input type="text" placeholder="Your full name" required=""
                                        class="form-control shadow-sm px-4 p-3 text-primary" v-model="info['fullName']">
                                </div>

                                <button type="submit" class="btn btn-primary btn-block mt-4 w-100 shadow-sm">Update
                                    informartion</button>
                            </form>


                        </div>

                        <div class="col-md-4">
                            <form class="mt-4" method="post" @submit.prevent="changePassword">
                                <div class="form-group mb-3">
                                    <input type="password" name="old_pasword" placeholder="Your current password"
                                        required="" class="form-control shadow-sm px-4 p-3 text-primary"
                                        v-model="pass['old_password']">
                                </div>
                                <div class="form-group mb-3">
                                    <input type="password" name="password" placeholder="New password" required=""
                                        class="form-control shadow-sm px-4 p-3 text-primary" v-model="pass['password']">
                                </div>
                                <div class="form-group mb-3">
                                    <input type="password" name="password_confirmation"
                                        placeholder="Confirm new password" required=""
                                        class="form-control shadow-sm px-4 p-3 text-primary"
                                        v-model="pass['password_confirmation']">
                                </div>

                                <button type="submit" class="btn btn-primary btn-block mt-4 w-100 shadow-sm">Change
                                    password</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade mb-4" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <button class="btn btn-primary mt-4 w-100" @click="generateNewKey">Request to generate new API Key</button>
                    <div class="table-responsive mt-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Key name</th>
                                    <th>Key</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(api, index) in apis">
                                    <td>{{ index + 1 }}</td>
                                    <td style="color:lightgray;">{{ api['name'] ?? 'API Number #' + api['id'] }}</td>
                                    <td style="color:lightgray;">{{ api['key'] }}</td>
                                    <td>
                                        <p v-if="api['enabled'] == 0" style="color:orange;">Pending</p>
                                        <p v-else style="color:limegreen;">Active</p>
                                    </td>
                                    <td>
                                        <a @click="deleteKey(api['key'])" style="color: red;text-decoration: none;cursor: pointer;">Delete</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <button class="btn btn-primary mt-4 w-100" @click="createMerchant"> Create new merchant </button>
                    <div class="table-responsive mt-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Currency</th>
                                    <th>Address</th>
                                    <th>Network</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in umerchants">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ item.ticker }}</td>
                                    <td>{{ item.address }}</td>
                                    <td>{{ item.network }}</td>
                                    <td>
                                        <a @click="deleteMerchant(item.id)" style="color: red;text-decoration: none;cursor: pointer;">Delete</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
    import {
        ref
    } from 'vue';

    export default {
        name: 'DashboardSettings',

        data() {
            return {
                pass: {
                    old_password: ref(''),
                    password: ref(''),
                    password_confirmation: ref('')
                },

                info: {
                    email: ref(''),
                    fullName: ref('')
                },

                apis: [],
                assets: {},
                umerchants: [],
            }
        },

        components: {
            SidebarComponent
        },

        methods: {
            changePassword() {
                if (this.pass.old_password.length == 0 || this.pass.password.length == 0 || this.pass
                    .password_confirmation.length == 0) {
                    this.$snackbar.add({
                        type: 'error',
                        text: 'Please fill all field'
                    });
                    return;
                }

                this.$axios.post('user/password/change', {
                        old_password: this.pass.old_password,
                        password: this.pass.password,
                        password_confirmation: this.pass.password_confirmation
                    })
                    .then(response => {
                        if ('success' in response.data) {
                            if (response.data.success) {
                                this.$snackbar.add({
                                    type: 'success',
                                    text: response.data.message
                                });

                                this.$auth.logout();
                                this.$router.push('/auth/login');

                            } else {
                                this.$snackbar.add({
                                    type: 'error',
                                    text: response.data.message
                                });
                            }
                        }
                    })
                    .catch(error => {
                        this.$snackbar.add({
                            type: 'error',
                            text: error.response.data.message
                        });
                    });
            },

            loadClientAPIs() {
                this.$axios.get('user/api/keys').then(response => {
                    if ('success' in response.data && response.data.success) {
                        this.apis = response.data.data;
                    }
                });
            },

            generateNewKey() {
                this.$axios.post('user/api/key/generate').then(response => {
                    if ('success' in response.data && response.data.success) {
                        if ('message' in response.data) {
                            this.$snackbar.add({
                                type: 'success',
                                text: response.data.message
                            });

                            this.loadClientAPIs();
                        }
                    }
                });
            },

            deleteKey(key) {
                this.$axios.post('user/api/key/delete', {key: key}).then(response => {
                    if ('success' in response.data) {
                        if (response.data.success) {
                            this.$snackbar.add({
                                type: 'success',
                                text: response.data.message
                            });
                        } else {
                            this.$snackbar.add({
                                type: 'error',
                                text: response.data.message
                            });
                        }

                        this.loadClientAPIs();
                    }
                });
            },

            updateInformation() {

            },

            createMerchant() {
                if (this.apis.length > 0 && this.apis.some(obj => obj.enabled)) {
                    let enabledApis = this.apis.filter(obj => obj.enabled);
                    const apiSelect = document.createElement('select');
                    apiSelect.setAttribute('name', 'api_key');
                    apiSelect.style = 'width: 100%;background: #2c2f39;color: #fff;font-weight: 600;border: none;border-radius: 24px;padding: 19px 18px;-moz-appearance: none;-webkit-appearance: none;appearance: none;cursor: pointer;';
                    let selectHtml = '';

                    enabledApis.forEach((item, index) => {
                        const op = document.createElement('option');

                        op.value = item.key;
                        op.text = item.name ?? `API Number #${item.id} (${item.key.substr(0, 6)}...)`;

                        if (index == 0) {
                            op.selected = true;
                        }

                        apiSelect.appendChild(op);
                    });

                    this.$api.getAssets().then(response => {
                        const selectElement = document.createElement('select');
                        selectElement.setAttribute('name', 'ticker');
                        selectElement.style = 'width: 100%;background: #2c2f39;color: #fff;font-weight: 600;border: none;border-radius: 24px;padding: 19px 18px;-moz-appearance: none;-webkit-appearance: none;appearance: none;cursor: pointer;';
                        this.assets = response;

                        for (let key in response) {
                            let ticker = response[key];

                            if (ticker['can_withdraw']) {
                                const option = ticker.name;
                                const optionElement = document.createElement('option');

                                optionElement.value = key;
                                optionElement.text = option;

                                if (option == 'Bitcoin') {
                                    optionElement.setAttribute('selected', 'selected');
                                }

                                selectElement.appendChild(optionElement);
                            }
                        }

                        selectHtml = selectElement.outerHTML;
                    
                        this.$swal({
                            title: 'Merchant',
                            html: `<p class="text-secondary dep-sub">Please enter destionation details, note that our fee is 1%</p>
                                    <div class="flex field">
                                        ${selectHtml}
                                    </div>

                                    <div class="flex field">
                                        <div class="input-group dep">
                                            <input type="text" placeholder="Enter wallet address" autocomplete="off" class="input-dep" style="width: 100%" name="wallet">
                                        </div>
                                    </div>
                                    
                                    <div class="flex field">
                                        ${apiSelect.outerHTML}
                                    </div>
                                    <p style="text-align:left;margin-bottom:18px;">API key for this merchant</p>`,

                            confirmButtonText: 'Next'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const wallet = document.querySelector('input[name="wallet"]').value;
                                const ticker = document.querySelector('select[name="ticker"]').value;
                                const api = document.querySelector('select[name="api_key"]').value;

                                let coinNets = this.assets[ticker];
                                let net;

                                if (coinNets == null) {
                                    this.$snackbar.add({
                                        type: 'error',
                                        message: 'An error occurred'
                                    });

                                    return;
                                }

                                if ('networks' in coinNets && 'withdraws' in coinNets.networks && coinNets.networks.withdraws.length !== 0) {
                                    if (coinNets.networks.withdraws.length > 1) {
                                        let netsSelect = document.createElement('select');
                                        netsSelect.style = 'width: 100%;background: #2c2f39;color: #fff;font-weight: 600;border: none;border-radius: 24px;padding: 19px 18px;-moz-appearance: none;-webkit-appearance: none;appearance: none;cursor: pointer;';
                                        netsSelect.setAttribute('name', 'network')

                                        coinNets.networks.withdraws.forEach((item, index) => {
                                            const netOptElement = document.createElement('option');

                                            netOptElement.value = item;
                                            netOptElement.text = item;

                                            if (index == 0) {
                                                netOptElement.setAttribute('selected', 'selected');
                                            }

                                            netsSelect.appendChild(netOptElement);
                                        })

                                        this.$swal({
                                            title: 'Select network',
                                            html: `<p class="text-secondary dep-sub">Please select network for ${ticker}</p>
                                                    <div class="flex field">
                                                        ${netsSelect.outerHTML}
                                                    </div>

                                                    <p style="text-align:left;margin-bottom:18px;">You can choose a custom network</p>`
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                net = document.querySelector('select[name="network"]').value;

                                                this.doMerchant(ticker, wallet, net, api);
                                                return;
                                            } else {
                                                return;
                                            }
                                        })

                                    } else if (coinNets.networks.withdraws.length == 1) {
                                        net = coinNets.networks.withdraws[0]
                                    }
                                    
                                } else {
                                    this.$snackbar.add({
                                        type: 'error',
                                        message: 'You cannot use this currency'
                                    });

                                    return;
                                }

                                if (net !== undefined) {
                                    this.doMerchant(ticker, wallet, net, api);
                                }
                            }
                        });
                    });
                } else {
                    this.$snackbar.add({
                        type: 'error',
                        text: 'You don\'t have active API key'
                    });
                }
            },

            doMerchant(ticker, address, net, api) {
                address = address.trim();
                
                this.$axios.post('merchant/create', {
                    ticker: ticker,
                    address: address,
                    network: net
                }, {
                    headers: {
                        'X-Swappium-Key': api
                    }
                }).then(response => {
                    if ('success' in response.data) {
                        if (response.data.success && 'message' in response.data) {
                            this.$snackbar.add({
                                type: 'success',
                                text: response.data.message
                            })
                        } else if (!response.data.success && 'message' in response.data) {
                            this.$snackbar.add({
                                type: 'error',
                                text: response.data.message
                            })
                        }
                    }
                }).catch(error => {
                    if (error.response.data.hasOwnProperty('message')) {
                        this.$snackbar.add({
                            type: 'error',
                            text: error.response.data.message
                        });
                    } else {
                        console.log(error);
                    }
                });

                this.getUserMerchants();
            },

            getUserMerchants() {
                this.$axios.get('user/merchants').then(response => {
                    if ('merchants' in response.data) {
                        this.umerchants = response.data.merchants
                        console.log(this.umerchants);
                    }
                });
            },

            deleteMerchant(id) {
                console.log(id);

                this.$axios.post('user/merchant/delete', {merchant_id: id}).then(response => {
                    console.log(response);

                    if (response.data.hasOwnProperty('message')) {
                        this.$snackbar.add({
                            type: ((response.data.success ?? false) ? 'success' : 'error'),
                            text: response.data.message
                        });

                        this.getUserMerchants();
                    }
                    
                }).catch(error => {
                    console.log(error)
                });
            }
        },

        mounted() {
            let user = this.$auth.user;

            this.info.email = user.email;
            this.info.fullName = user.name;

            this.loadClientAPIs();
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

    @media only screen and (max-width: 600px) {
        main {
            padding: 30px 12px;
        }
    }
</style>

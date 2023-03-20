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
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
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
                    <button class="btn btn-primary mt-4 w-100">Generate new API Key</button>
                    <div class="table-responsive mt-4">
                        <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Key name</th>
                                <th>Key</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td style="color:lightgray;">My API Key</td>
                                <td style="color:lightgray;">xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx</td>
                                <td>
                                    <a href="" style="color: red;text-decoration: none;">Delete</a>
                                    <a href="" class="mx-3" style="color: gray;text-decoration: none;">Disable</a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>My API Key #02</td>
                                <td>xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx</td>
                                <td>
                                    <a href="" style="color: red;text-decoration: none;">Delete</a>
                                    <a href="" class="mx-3" style="text-decoration: none;">Enable</a>
                                </td>
                            </tr>
                            <!-- Add more rows for additional API keys -->
                        </tbody>
                    </table>
                    </div>

                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
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
                }
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


            updateInformation() {

            }
        },

        mounted() {
            let user = this.$auth.user;

            this.info.email = user.email;
            this.info.fullName = user.name;
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

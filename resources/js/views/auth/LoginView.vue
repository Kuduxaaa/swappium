<template>
    <div class="container-fluid">
        <div class="row no-gutter">
            <div class="col-md-5 d-none d-md-flex bg-image">
                <div class="logo-image">
                    <router-link to="/">
                        <img src="/assets/img/logo.png" alt="Swappium">
                    </router-link>
                </div>

                <h1 class="login-title">The Crypto Asset Exchange</h1>
                <p>Swappium</p>
            </div>

            <div class="col-md-7">
                <div class="login d-flex align-items-center py-5">
                    <div class="container">
                        <div class="row">
                            <div class="login-form-wrapper col-lg-10 col-xl-7 mx-auto">
                                <h1>Welcome back<span class="text-gradient">!</span></h1>
                                <p class="login-text text-secondary mb-4">Welcome back, Please signin to continue</p>

                                <form class="mt-4" method="post" @submit.prevent="login">
                                    <div class="form-group mb-3">
                                        <input v-model="email" type="email" placeholder="Email address" required="" autofocus="" class="form-control p-3 shadow-sm px-4">
                                    </div>

                                    <div class="form-group mb-3">
                                        <input v-model="password" type="password" placeholder="Password" required="" class="form-control shadow-sm px-4 p-3 text-primary">
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block mt-4 w-100 shadow-sm">Sign in</button>
                                    <p class="mt-4 text-center text-secondary">Don't have account yet? <router-link to="/auth/register">Register</router-link></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>

<script>
import { ref } from "vue";

export default {
    data() {
        return {
            email: ref(''),
            password: ref('')
        }
    },

    created () {
        if (this.$auth.check()) {
            this.$router.push('/');
        }
    },

    methods: {
        login() {
            axios.post('auth/login', {
                'email': this.email,
                'password': this.password

            }).then(response => {
                if (!response.data.success) {
                    let message = 'Something went wrong';

                    if (response.data.hasOwnProperty('message')) {
                        message = response.data.message;
                    }

                    this.$snackbar.add({
                        type: 'error',
                        text: message
                    });

                    return;
                }

                if (response.data.success && response.data.hasOwnProperty('token')) {
                    this.$auth.login(response.data.token, response.data.user);

                    this.$snackbar.add({
                        type: 'success',
                        text: 'Successfully logged in!'
                    });

                    window.location.href = '/console';
                }

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
    }
}
</script>

<style scoped>
.login,
.image {
  min-height: 100vh;
}

.btn.btn-primary.metamask.btn-block.mb-2.mt-2.w-100.shadow-sm {
    background-color: #f5841e !important;
    font-weight: 500;
    border: 1px solid #fb953c !important;
}

.bg-image {
    background-image: url('/assets/img/figures-2.png');
    background-size: cover;
    background-position: -370px 330px;
    background-repeat: no-repeat;
    background-clip: border-box;

    display: block !important;
    background-color: var(--color-accent);
    padding: 80px
}

.bg-image p {
    font-weight: 600;
    margin-top: 8px;
    text-transform: uppercase;
}

.logo-image img {
    width: 75px;
    height: 75px;
}

.login-title {
    font-size: 56px;
    letter-spacing: -1px;
    margin-top: 46px;
    font-weight: 600;
    max-width: 70%;
}

.login-text {
    margin-bottom: 3rem !important;
}

.form-control {
    background-color: rgba(228, 228, 228, 0.03);
    border: 1px solid #404040;
    color: rgb(228, 228, 228) !important;
    font-size: 20px;
    border-radius: 14px;
}

.form-control::placeholder {
    font-size: 1rem;
}

.form-control:focus {
    border: 1px solid var(--color-primary);
    background-color: rgba(228, 228, 228, 0.04);
}

.login-form-wrapper {
    max-width: 412px;
    margin: auto;
}
</style>

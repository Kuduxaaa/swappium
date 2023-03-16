<template>
    <div class="container-fluid">
        <div class="row no-gutter">
            <div class="col-md-5 d-block bg-image">
                <div class="logo-image">
                    <router-link to="/">
                        <img src="/assets/img/logo.png" alt="Swappium">
                    </router-link>
                </div>

                <h1 class="login-title">Join our crypto community</h1>
                <p>Swappium</p>
            </div>
            
            <div class="col-md-7">
                <div class="login d-flex align-items-center py-5">
                    <div class="container">
                        <div class="row">
                            <div class="login-form-wrapper col-xl-7 mx-auto">
                                <h1>Registration<span class="text-gradient">!</span></h1>
                                <p class="login-text text-secondary mb-4">Fill in all the fields to register</p>

                                <form class="mt-4" method="post" @submit.prevent="register">
                                    <div class="form-group mb-3">
                                        <input v-model="full_name" type="text" placeholder="Your full name" required="" autofocus="" class="form-control p-3 shadow-sm px-4">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <input v-model="email" type="email" placeholder="Email address" required="" class="form-control p-3 shadow-sm px-4">
                                    </div>

                                    <div class="form-group mb-3">
                                        <input v-model="password" type="password" placeholder="Password" required="" class="form-control shadow-sm px-4 p-3 text-primary">
                                    </div>

                                    <div class="form-group mb-3">
                                        <input v-model="password_confirmation" type="password" placeholder="Confirm password" required="" class="form-control shadow-sm px-4 p-3 text-primary">
                                    </div>

                                    <div class="form-group mb-3">
                                        <input v-model="referral_code" type="text" placeholder="Referral code (optional)" class="form-control shadow-sm px-4 p-3 text-primary">
                                    </div>

                                    <p class="legal">By signing up I agree that I am 18 years of age or older, to the <router-link to="/privacy">Privacy Policy</router-link>, <router-link to="/terms">Terms and Conditions</router-link> and <router-link to="/aml-kyc">AML/KYC</router-link>.</p>

                                    <button type="submit" class="btn btn-primary btn-block mb-2 mt-4 w-100 shadow-sm">Register</button>

                                    <p class="mt-4 text-center text-secondary">Already have an account? <router-link to="/auth/login">Login</router-link></p>
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
    setup() {
        console.log(this);
    },

    data() {
        return {
            email: ref(''),
            password: ref(''),
            password_confirmation: ref(''),
            referral_code: ref(''),
            full_name: ref(''),
        }
    },

    created () {
        if (this.$auth.check()) {
            this.$router.push('/');
        }
    },
    
    methods: {
        register () {
            axios.post('auth/register', {
                'email': this.email,
                'password': this.password,
                'password_confirmation': this.password_confirmation,
                'referral_code': this.referral_code,
                'name': this.full_name
                
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
                    this.$snackbar.add({
                        type: 'success',
                        text: 'Successfully registered!'
                    });

                    this.$router.push('/auth/login');
                }

            }).catch(error => {
                if (error.response.data.hasOwnProperty('errors')) {
                    let validation_messages = error.response.data.errors;
                    
                    for (var key in validation_messages) {
                        if (!validation_messages.hasOwnProperty(key)) continue;

                        var obj = validation_messages[key];
                        for (var prop in obj) {
                            if (!obj.hasOwnProperty(prop)) continue;

                            this.$snackbar.add({
                                type: 'error',
                                text: obj[prop]
                            });
                        }
                    }
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

@media only screen and (max-width: 779px) {
    .bg-image {
        display: none !important;
    }
}

.bg-image {
    background-image: url('/assets/img/figures-2.png');
    background-size: cover;
    background-position: -370px 330px;
    background-repeat: no-repeat;
    background-clip: border-box;

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

.legal {
    margin-top: 32px;
    font-size: 12px;
    font-weight: 500;
    color: #808191;
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
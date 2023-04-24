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
                            <div class="kyc-wrapper" v-if="is_kyc">
                                <div v-if="doc_type == null">
                                    <h1 class="text-center verify-text">Select verification document</h1>
                                    <div class="container h-100 d-flex justify-content-center mt-4 flex-wrap">
                                        <div class="row align-items-center doc-type" @click="() => doc_type = 'id_card'">
                                            <div class="col-6 mx-auto">
                                                <div class="jumbotron">
                                                    <p class="display-1 text-center mb-4"><i class="dti bi bi-file-earmark-person-fill"></i></p>
                                                    <p class="text-center mt-4">ID Card</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row align-items-center doc-type" @click="() => doc_type = 'passport'">
                                            <div class="col-6 mx-auto">
                                                <div class="jumbotron">
                                                    <p class="display-1 text-center mb-4"><i class="dti bi bi-globe2"></i></p>
                                                    <p class="text-center mt-4">Passport</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="login-form-wrapper col-xl-7 mx-auto">
                                        <h1>Upload files<span class="text-gradient">!</span></h1>
                                        <p class="login-text text-secondary mb-4">You selected 
                                            <span v-if="doc_type == 'passport'">Passport</span>
                                            <span v-else>ID Card</span>.
                                            <span v-if="doc_type == 'passport'">
                                                Please upload your document front side images, allowed mimes are: <b>jpg</b>, <b>jpeg</b>, <b>png</b>
                                            </span>
                                            <span v-else>Please upload your document front and back side images, allowed mimes are: <b>jpg</b>, <b>jpeg</b>, <b>png</b></span>.
                                        </p>

                                        <form class="mt-4" method="post" @submit.prevent="register">
                                            <div class="form-group mb-4" v-if="doc_type == 'passport'">
                                                <input class="form-control d-none" type="file" v-on:change="handleFileUpload" accept="image/*" name="doc_front" id="doc_front">
                                                    <label for="doc_front" class="f-upload-label">
                                                        <span v-if="doc_front == null">Click to select document image (<b>Front Side</b>)</span>
                                                        <span v-else>{{ doc_front.name }}</span>
                                                    </label>
                                            </div>

                                            <div class="form-group mb-4" v-else>
                                                <div class="mt-3">
                                                    <input class="form-control d-none" type="file" v-on:change="handleFileUpload" accept="image/*" name="doc_front" id="doc_front">
                                                    <label for="doc_front" class="f-upload-label">
                                                        <span v-if="doc_front == null">Click to select document image (<b>Front Side</b>)</span>
                                                        <span v-else>{{ doc_front.name }}</span>
                                                    </label>
                                                </div>

                                                <div class="mt-3">
                                                    <input class="form-control d-none" type="file" v-on:change="handleFileUpload" accept="image/*" name="doc_back" id="doc_back">
                                                    <label for="doc_back" class="f-upload-label">
                                                        <span v-if="doc_back == null">Click to select document image (<b>Back Side</b>)</span>
                                                        <span v-else>{{ doc_back.name }}</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary btn-block mt-4 w-100 shadow-sm">Register</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="login-form-wrapper col-xl-7 mx-auto" v-else>
                                <h1>Registration<span class="text-gradient">!</span></h1>
                                <p class="login-text text-secondary mb-4">Fill in all the fields to register</p>

                                <form class="mt-4" method="post" @submit.prevent="next">
                                    <div class="form-group mb-3">
                                        <input v-model="full_name" type="text" placeholder="Your full name" autofocus="" class="form-control p-3 shadow-sm px-4">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <input v-model="email" type="text" placeholder="Email address" class="form-control p-3 shadow-sm px-4">
                                    </div>

                                    <div class="form-group mb-3">
                                        <input v-model="password" type="password" placeholder="Password" class="form-control shadow-sm px-4 p-3 text-primary">
                                    </div>

                                    <div class="form-group mb-3">
                                        <input v-model="password_confirmation" type="password" placeholder="Confirm password" class="form-control shadow-sm px-4 p-3 text-primary">
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
            email: ref(null),
            password: ref(null),
            password_confirmation: ref(null),
            referral_code: ref(null),
            full_name: ref(null),
            is_kyc: false,
            doc_type: null,

            doc_front: ref(null),
            doc_back: ref(null)
        }
    },

    created () {
        if (this.$auth.check()) {
            this.$router.push('/');
        }
    },
    
    methods: {
        next() {
            const requiredFields = [
                { field: 'full_name', error: 'Your full name is required!' },
                { field: 'email', error: 'Email field is required!', email: true },
                { field: 'password', error: 'Password field is required!', minLength: 8 },
                { field: 'password_confirmation', error: 'Please confirm your password!' },
            ];

            for (const field of requiredFields) {
                if (!this[field.field]) {
                    this.$snackbar.add({
                        type: 'error',
                        text: field.error,
                    });

                    return;
                }

                if (field.email && !/\S+@\S+\.\S+/.test(this[field.field])) {
                    this.$snackbar.add({
                        type: 'error',
                        text: 'Invalid email address!',
                    });

                    return;
                }

                if (field.minLength && this[field.field].length < field.minLength) {
                    this.$snackbar.add({
                        type: 'error',
                        text: `Password should be at least ${field.minLength} characters long!`,
                    });

                    return;
                }
            }

            if (this.password !== this.password_confirmation) {
                this.$snackbar.add({
                    type: 'error',
                    text: 'Entered passwords don\'t match!',
                });

                return;
            }

            this.is_kyc = true;
        },

        register () {
            if ((this.doc_type == 'id_card' && (this.doc_front == null || this.doc_back == null)) ||
                (this.doc_type == 'passport' && this.doc_front == null)) {
                this.$snackbar.add({
                    type: 'error',
                    text: 'Please fill all fields'
                });

                return;

            } else if (!['id_card', 'passport'].includes(this.doc_type)) {
                this.$snackbar.add({
                    type: 'error',
                    text: 'Invalid document type'
                });

                return;
            }

            const formData = new FormData();
            formData.append('doc_type', this.doc_type);
            formData.append('doc_back', this.doc_back);
            formData.append('doc_front', this.doc_front);
            formData.append('email', this.email);
            formData.append('password', this.password);
            formData.append('password_confirmation', this.password_confirmation);
            formData.append('referral_code', this.referral_code);
            formData.append('name', this.full_name);

            this.$snackbar.add({
                type: 'info',
                text: 'Request sent. Please wait...'
            })

            this.$axios.post('auth/register', formData).then(response => {
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

                if (response.data.success) {
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
            })

            // axios.post('auth/register', {
            //     'email': this.email,
            //     'password': this.password,
            //     'password_confirmation': this.password_confirmation,
            //     'referral_code': this.referral_code,
            //     'name': this.full_name
                
            // }).then(response => {
            //     

            //     if (response.data.success && response.data.hasOwnProperty('token')) {
            //         this.$snackbar.add({
            //             type: 'success',
            //             text: 'Successfully registered!'
            //         });

            //         this.$router.push('/auth/login');
            //     }

            // }).catch(error => {
            //     if (error.response.data.hasOwnProperty('errors')) {
            //         let validation_messages = error.response.data.errors;
                    
            //         for (var key in validation_messages) {
            //             if (!validation_messages.hasOwnProperty(key)) continue;

            //             var obj = validation_messages[key];
            //             for (var prop in obj) {
            //                 if (!obj.hasOwnProperty(prop)) continue;

            //                 this.$snackbar.add({
            //                     type: 'error',
            //                     text: obj[prop]
            //                 });
            //             }
            //         }
            //     }

            //     return;
            // });
        },

        handleFileUpload(event) {
            const dataType = event.target.getAttribute('name');

            if (event.target.files.length <= 0) {
                this.$snackbar.add({
                    type: 'error',
                    text: 'Please select a file'
                });

                return;
            }
            
            const file = event.target.files[0];
            const fileExtention = file.name.split('.').pop();

            if(!['jpg', 'jpeg', 'png'].includes(fileExtention)) {
                this.$snackbar.add({
                    type: 'error',
                    text: 'Please select image file type (jpg, jpeg, png)'
                });

                return;
            }

            switch(dataType) {
                case 'doc_front':
                    this.doc_front = file;
                    break;

                case 'doc_back':
                    this.doc_back = file;
                    break;

                default:
                    this.$snackbar.add({
                        type: 'error',
                        text: 'Invalid document type'
                    });

                    return;
            }
        }   
    }
}
</script>

<style scoped>
.login,
.image {
  min-height: 100vh;
}

@media only screen and (max-width: 1204px) {
    .bg-image {
        display: none !important;
    }

    .login {
        min-height: 48vh;
    }

    .col-md-7 {
        width: 100%;
    }
}

.f-upload-label {
    width: 100%;
    border: 1px solid #404040;
    cursor: pointer;
    border-radius: 10px;
    padding: 16px;
    background-color: rgba(228, 228, 228, 0.03);
    color: rgb(176, 186, 204) !important;
}

.f-upload-label:hover {
    border: 1px solid #355cfd;
    background-color: rgba(228, 228, 228, 0.04);
}

@media only screen and (max-width: 760px) {
    .doc-type {
        max-width: 100% !important;
        width: 100% !important;
    }

    .doc-type:nth-child(2) {
        margin-top: 32px !important;
    }
}

.dti {
    color: #adc5ff;
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

.verify-text {
    margin-bottom: 80px;
}

.doc-type {
    max-width: 240px;
    width: 100%;
    border: 1px solid #676c79;
    border-radius: 23px;
    margin: 0px 18px;
    cursor: pointer;
    min-height: 270px;
}

.doc-type:hover {
    background-color: #bfcfff14;
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
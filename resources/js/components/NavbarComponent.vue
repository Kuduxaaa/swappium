<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <router-link to="/" class="navbar-brand">
                <img src="assets/img/logo-blue.png" height="50" alt="Swappium">
            </router-link>

            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <i class="bi bi-list"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-4">
                    <router-link to="/" class="nav-item nav-link active">Home</router-link>
                    <router-link to="/about" class="nav-item nav-link active">About</router-link>
                    <router-link to="/privacy" class="nav-item nav-link active">Privacy</router-link>
                    <a href="tel:+995551240280" class="nav-item nav-link active">
                        <i class="bi bi-telephone"></i>
                        Call
                    </a>
                </div>
                <div class="navbar-nav ms-auto">
                    <router-link v-if="!isLogedin" class="action-link" to="/auth/login">
                        <button class="btn btn-primary-soft nomax px-4">Login</button>
                    </router-link>

                    <router-link class="action-link" :to="(isLogedin) ? '/console' : '/auth/register'">
                        <button class="btn btn-primary">Get started</button>
                    </router-link>

                    <div class="change-lang">
                        <select v-model="currentLang" id="language" @change="changeLanguage">
                            <option v-for="locale in availableLangs" :value="locale" :selected="currentLang === locale">{{ locale.toUpperCase() }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import store from '../store';
import localize from '../i18n/localize';
import { ref } from 'vue';

export default {
    name: "NavbarComponent",

    data() {
        return {
            isLogedin: null,
            availableLangs: [],
            currentLang: ref('en')
        }
    },

    mixins: [localize],
    methods: {
        changeLanguage(event) {
            let lang = event.target.value;
            
            this.$i18n.locale = lang
            store.commit('setLocale', lang);
        }
    },

    created() {
        this.isLogedin = this.$auth.check();

        this.currentLang = this.$i18n.locale;
        this.availableLangs = this.$i18n.availableLocales;
        
        console.log()
    }
}
</script>

<style scoped>
.navbar {
    background-color: rgba(8, 8, 10, 0.62) !important;
    box-shadow: 0 0.375rem 1.5rem 0 rgba(0, 0, 0, 0.125);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: saturate(180%) blur(18px);
    -webkit-backdrop-filter: saturate(180%) blur(18px); /* for older versions of Safari and iOS */
    -moz-backdrop-filter: saturate(180%) blur(18px); /* for older versions of Firefox */
    -ms-backdrop-filter: saturate(180%) blur(18px); /* for older versions of Microsoft Edge and IE */
    -o-backdrop-filter: saturate(180%) blur(18px); /* for older versions of Opera */


    padding: 12px;
    position: fixed;
    width: 100%;
    z-index: 9348923;
}

select#language {
    background-color: transparent;
    border: none;
    color: #fff;
    cursor: pointer;
    height: 100%;
    margin-left: 18px;
}

option {
    color: #000;
}

.navbar-toggler:focus {
    box-shadow: none !important;
}
.navbar-toggler {
    color: #fff !important;
}

.action-link {
    color: #222;
    text-decoration: none;
    margin: 0px 6px;
}

.action-link:hover {
    color: var(--color-primary);
}

.action-link:nth-child(1) {
    line-height: 46px;
}

.btn-primary {
    padding: 10px;
    width: 111px;
}

@media only screen and (max-width: 995px) {
    .action-link {
        margin: 0px;
    }

    .navbar-nav {
        margin: 20px 0px !important;
    }

    .btn-primary {
        margin-top: 12px;
    }

    .navbar-collapse {
        height: calc(100vh - 80px);
        display: grid;
    }

    .action-link button {
        width: 100%;
    }
}

.nav-item {
    color: #fff !important;
}
</style>

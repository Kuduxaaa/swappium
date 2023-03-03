<template>
    <div v-bind:class="(showMenu) ? 'navbar bg-mode' : 'navbar navbar-pd bg-mode'">
        <div class="menu-toggle" @click="toggleMenu">
            <i class="bi bi-list"></i>
        </div>

        <div class="user">
            <div class="user-info">
                <span class="user-name text-bold">${{ userBalance }}</span>
            </div>  
        </div>
    </div>

    <div v-bind:class="(showMenu) ? 'sidebar bg-mode' : 'sidebar sidebar-pd bg-mode'">
        <section class="logo">
            <router-link to="/">
                <img src="/assets/img/logo.png" class="logo-image" />
                <span class="logo-text">
                    Swappium
                </span>
            </router-link>
        </section>

        <section class="menu-items">
            <div v-bind:class="(routePath == '/app') ? 'item selected' : 'item'">
                <router-link to="/app">
                    <i class="bi bi-grid"></i>
                    Home
                </router-link>
            </div>

            <div v-bind:class="(routePath == '/app/markets') ? 'item selected' : 'item'">
                <router-link to="/app/markets">
                    <i class="bi bi-shop"></i>
                    Markets
                </router-link>
            </div>

            <div v-bind:class="(routePath == '/app/wallets') ? 'item selected' : 'item'">
                <router-link to="/app/wallets">
                    <i class="bi bi-wallet2"></i>
                    My Wallet
                </router-link>
            </div>

            <div v-bind:class="(routePath == '/app/orders') ? 'item selected' : 'item'">
                <router-link to="/app/orders">
                    <i class="bi bi-journal-text"></i>
                    Orders
                </router-link>
            </div>

            <div v-if="userData.role == 2" v-bind:class="(routePath == '/app/settings') ? 'item selected' : 'item'">
                <router-link to="/app/settings">
                    <i class="bi bi-circle"></i>
                    God Mode
                </router-link>
            </div>

            <div v-bind:class="(routePath == '/app/settings') ? 'item selected' : 'item'">
                <router-link to="/app/settings">
                    <i class="bi bi-gear"></i>
                    Settings
                </router-link>
            </div>
        </section>

        <section class="sidebar-footer">
            <div class="item" @click="logout">
                <a href="javascript:void[0];">
                    <i class="bi bi-box-arrow-left"></i>
                    logout
                </a>
            </div>  
        </section>
    </div>
</template>

<script>
export default {
    data() {
        return {
            showMenu: true,
            userData: this.$auth.getUser(),
            userBalance: 0,
            routePath: this.$route.path
        }
    },

    methods: {
        toggleMenu() {
            this.showMenu = !this.showMenu;
        },

        logout() {
            this.$auth.logout();
            this.$router.push('/');
        },

        getBalance() {
            // this.$axios.get('user/balance').then(response => {
            //     this.userBalance = response.data.amount;
                
            // }).catch(response => {

            //     if (response.response.status == 401) {
            //         this.$snackbar.add({
            //             type: 'error',
            //             text: 'Session expired, Please re-login!'
            //         });

            //         this.$auth.logout();
            //         this.$router.push('/auth/login');
            //     } else {
            //         if (response.response.data.hasOwnProperty('message')) {
            //             this.$snackbar.add({
            //                 type: 'error',
            //                 text: response.response.data.message
            //             });
            //         } else {
            //             console.log(response.response);
            //         }
            //     }
            // });
        }
    },

    mounted() {
        this.showMenu = (window.innerWidth >= 822);
        this.getBalance();
    }
}
</script>

<style scoped>
.sidebar {
    width: 280px;
    height: 100vh;
    position: fixed;
    background-color: #1F2128;
    top: 0;
    left: 0;
    z-index: 999999;
}

.logo {
    width: 100%;
    padding: 43px 41px 0px 41px;
}

.logo a {
    display: flex;
    text-decoration: none;
    width: 205px;
}

.logo-image {
    width: 50px;
    margin-right: 15px;
    height: 50px;
}

.logo-text {
    color: #fff;
    font-weight: 600;
    font-size: 28px;
    line-height: 45px;
}

.menu-items {
    margin-top: 28px;
    height: calc(100vh - 268px);
    overflow: auto;
}

.item {
    margin: 13px 26px;
    border-radius: 10px;
}

.item a {
    text-decoration: none;
    font-size: 1rem;
    padding: 12px 18px;
    display: block;
    color: #808191;
}

.item a i {
    margin-right: 16px;
    font-size: 1.2rem;
}

.item.selected {
    background-color: #191B20;
}

.item.selected a {
    color: var(--color-primary);
}

.item:hover:not(.selected) {
    background-color: #191B20;
}

.item:hover:not(.selected) a {
    color: #fff;
}

.navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    background-color: #0f0f0f;
    border-bottom: 1px solid rgba(0, 0, 0, 0.8);
    width: calc(100% - 280px);
    margin-left: 280px;
}

.menu-toggle {
    font-size: 2rem;
    padding: 10px;
    cursor: pointer;
}

.user-image {
    width: 40px;
    border-radius: 50%;
}

.user {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.user-info {
    margin-right: 12px;
}

.sidebar-footer .item {
    position: fixed;
    bottom: 20px;
    width: 241px;
}

@media only screen and (max-width: 822px) {
    .sidebar {
        /* left: -100%; */
        width: 100%;
    }

    .navbar {
        margin-left: 0px;
        width: 100%;
    }

    .sidebar-footer .item {
        width: calc(100% - 42px);
    }
}

.navbar-pd {
    width: 100% !important;
    margin-left: 0px !important;
}

.sidebar-pd {
    left: -100%;
}

@media only screen and (max-width: 350px) {
    .user-info {
        display: none;
    }
}


</style>
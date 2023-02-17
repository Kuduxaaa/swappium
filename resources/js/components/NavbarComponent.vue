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
                    <a href="#" class="nav-item nav-link active">Home</a>
                    <a href="#" class="nav-item nav-link">Profile</a>
                    <a href="#" class="nav-item nav-link">Messages</a>
                    <a href="#" class="nav-item nav-link disabled" tabindex="-1">Reports</a>
                </div>
                <div class="navbar-nav ms-auto">
                    <router-link v-if="!isLogedin" class="action-link" to="/auth/login">
                        <button class="btn btn-primary-soft nomax px-4">Login</button>
                    </router-link>
                    
                    <router-link class="action-link" :to="(isLogedin) ? '/app' : '/auth/register'">
                        <button class="btn btn-primary">Get started</button>
                    </router-link>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        name: "NavbarComponent",

        data() {
            return {
                isLogedin: null
            }
        },

        created() {
            this.isLogedin = this.$auth.check();
        }
    }
</script>

<style scoped>
.navbar {
    background-color: rgba(8, 8, 10, 0.62) !important;
    box-shadow: 0 0.375rem 1.5rem 0 rgba(0, 0, 0, 0.125);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: saturate(180%) blur(18px);

    padding: 16px;
    position: fixed;
    margin-top: 38px;
    width: 100%;
    z-index: 9348923;
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
        height: 100vh;
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
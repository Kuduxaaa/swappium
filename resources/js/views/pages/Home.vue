<template>
    <header>
        <MarqueeComponent />
        <NavbarComponent />
    </header>

    <section class="home">
        <div class="left-effects position-absolute"></div>
        <div class="right-effects position-absolute"></div>

        <div class="container">
            <div class="row align-items-center justify-content-center home-content">
                <div class="col-lg-6 text-center">
                    <h2 class="display-4"><span class="text-gradient">Swappium</span> is the best</h2>
                    <p class="text-secondary mt-4">Welcome to Swappium, the premier platform for buying, selling, and trading cryptocurrencies. With our advanced technology and user-friendly interface, managing your digital assets has never been easier. Whether you're a seasoned trader or new to the world of crypto, Swappium has everything you need to succeed. Join us today and take control of your financial future</p>

                    <div class="pt-4">
                        <router-link to="/auth/register" v-if="!isAuthed">
                            <a class="btn btn-primary me-3" href="#">Sign up to join</a>
                        </router-link>
                        <router-link to="/console" v-else>
                            <a class="btn btn-primary me-3" href="#">Go to console</a>
                        </router-link>

                        <router-link to="/about">
                            <a class="btn btn-primary-soft" href="#">About swappium</a>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <FaqComponent />
    <GetAppComponent />
    <FooterComponent />
</template>

<script setup>
import NavbarComponent from '../../components/NavbarComponent.vue';
import MarqueeComponent from '../../components/MarqueeComponent.vue';
import FooterComponent from '../../components/FooterComponent.vue';
import GetAppComponent from '../../components/GetAppComponent.vue'
import FaqComponent from '../../components/FaqComponent.vue'
</script>

<script>
export default {
    name: 'Home',

    data() {
        return {
            isAuthed: false,
        }
    },

    mounted() {
        this.isAuthed = this.$auth.check();
    }
}
</script>

<style scoped>
.home {
    height: calc(100vh - 131px);
    background-image: url('/assets/img/home-banner-1.png');
    background-size: 210%;
    background-repeat: no-repeat;
    background-position: center;
    position: relative;
}


h2 {
    color: #fff;
    font-weight: 600;
}

.home-content {
    padding-top: 13rem;
}

@media only screen and (max-width: 822px) {
    .home {
        background-image: inherit;
    }
}
</style>

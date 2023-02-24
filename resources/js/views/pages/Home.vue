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
                    <p class="text-secondary mt-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled</p>
                    
                    <div class="pt-4">
                        <a class="btn btn-primary me-3" href="#">Sign up to join</a>
                        <a class="btn btn-primary-soft" href="#">Token Distribution</a>
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
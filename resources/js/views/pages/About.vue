<template>
    <header>
        <MarqueeComponent />
        <NavbarComponent />
    </header>

    <div>      
        <section class="py-5 bg-mode">
            <div class="container">
                <div class="row">
                    <div class="my-3 text-md-start text-center">
                        <h3 class="h2 fw-400 text-center mb-4">About Swappium</h3>
                        <p class="mt-4">We are the SWAPPIUM community (eng. SWAP - exchange, lat. PIUM - honesty) That supports the spread of cryptocurrency decentralization in humanity. Since cryptocurrencies are the most secure model if owned by an individual in the internet space, we are an individual that helps the public easily exchange cryptocurrencies through the SWAPPIUM platform.</p>
                        <p class="mt-4">Blockchain technology is an international digital currency project, it is the same digital asset that several countries agree to regulate, and several countries do not recognize as currency. Cryptocurrency is not tied to any particular country or central bank, so its value depends on the number of users and commercial organizations that accept it along with supply and demand. </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <FooterComponent />
</template>

<script setup>
import NavbarComponent from '../../components/NavbarComponent.vue';
import MarqueeComponent from '../../components/MarqueeComponent.vue';
import FooterComponent from '../../components/FooterComponent.vue';
</script>

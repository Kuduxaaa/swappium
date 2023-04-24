<template>
    <!-- <coingecko-coin-price-marquee-widget 
        coin-ids="tether,bitcoin,ethereum,dash,litecoin,dogecoin,solana,cardano,shiba-inu,ripple" 
        currency="usd" 
        background-color="#090b0f"
        font-color="#909090"
        locale="en" 
        vce-ready="">

    </coingecko-coin-price-marquee-widget> -->
</template>

<script>
    export default {
        name: "MarqueeComponent"
    }
</script>

<style scoped>
coingecko-coin-price-marquee-widget {
    position: fixed;
    box-shadow: 0 0.375rem 1.5rem 0 rgba(0, 0, 0, 0.125);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: saturate(180%) blur(18px);
    z-index: 9348923;
}
</style>
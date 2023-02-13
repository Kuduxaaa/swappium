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
                        <h3 class="h2 fw-400 text-center mb-4">AML / KYC</h3>
                        <h4 class="subtitle">AML</h4>
                        <p class="mt-4">What is the Anti-Money Laundering and Anti-Terrorism Policy (AML Policy)</p>
                        <p class="mt-4">Money laundering is a crime against economic activity that allows illegal money to circulate legally. The purpose of the mentioned action is to turn the illegally obtained money into legal circulation unnoticed, so that its illegal nature remains unnoticed. Accordingly, a person who participates in money laundering or launders illegal money himself, allows the illegally obtained money to circulate in the economy and benefits from it, which in turn threatens the economic stability of the country.</p>
                        <p class="mt-4">In addition, the money obtained by a person who commits an illegal act may be dangerous and used for other illegal acts. With the money obtained at this time, it puts honest business representatives in an unequal position, who obtained the money through legal means and became participants in the economic turnover. Therefore, it is important to detect such crimes in a timely manner and to involve those representatives of the private sector who have daily contact with financial transactions in the process of avoiding illegal actions.</p>
                    
                        <h4 class="subtitle">KYC</h4>
                        <p class="mt-4">This is also a policy model when a person is identified at the time of registration on the platform, of course, if the user agrees to all this, if the user passes KYC verification on the site, he will be able to exchange cryptocurrencies, and if he does not agree to the terms of the site within a reasonable time, then the platform will automatically suspend the registered user.</p>
                        <p class="mt-4">KYC means Know Your Customer and sometimes Know Your Client. KYC or KYC check is the mandatory process of identifying and verifying the client's identity when opening an account and periodically over time. In other words, banks must make sure that their clients are genuinely who they claim to be.</p>
                        <p class="mt-4">The global anti-money laundering (AML) and countering the financing of terrorism (CFT) landscape raise tremendous stakes for financial institutions.</p>
                        <p class="mt-4"> International regulations influenced by standards like The Financial Action Task Force (FATF) are now implemented in national laws encompassing strong directives like AML 4 and 5 and preventive measures like "KYC" for client identification. </p>
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

<style scoped>
.subtitle {
    margin-top: 4rem;
}
</style>

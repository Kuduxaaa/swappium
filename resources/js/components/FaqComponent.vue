<template>
    <div>
        <section class="section">
            <div class="container">
                <div class="row justify-content-center section-heading mb-4">
                    <div class="col-lg-5 text-center">
                        <h6 class="text-uppercase text-gradient d-inline-block">Why we are?</h6>
                        <h3 class="h1 m-0">Have Any Questions?</h3>
                    </div>
                </div>
                <div class="row mt-4 anss">
                    <div class="col-md-6">
                        <div class="accordion" id="home_cy_acc_1">
                            <div class="accordion-item shadow-sm">
                                <h2 class="accordion-header" id="headingOne_1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne_1" aria-expanded="false" aria-controls="collapseOne_1">
                                        What is swappium?
                                    </button>
                                </h2>
                                <div id="collapseOne_1" class="accordion-collapse collapse show" aria-labelledby="headingOne_1" data-bs-parent="#home_cy_acc_1">
                                    <div class="accordion-body collapse show">
                                        Swappium is a leading platform for buying, selling, and trading cryptocurrencies. Our user-friendly interface and advanced technology make it easy for anyone to manage their digital assets.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item shadow-sm">
                                <h2 class="accordion-header" id="headingTwo_2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo_2" aria-expanded="false" aria-controls="collapseTwo_2">
                                        How do I create an account on Swappium?
                                    </button>
                                </h2>
                                <div id="collapseTwo_2" class="accordion-collapse collapse" aria-labelledby="headingTwo_2" data-bs-parent="#home_cy_acc_1">
                                    <div class="accordion-body">
                                        To create an account on Swappium, simply click on the "Sign Up" button on our homepage and follow the instructions. You'll need to provide some basic information and verify your email address.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item shadow-sm">
                                <h2 class="accordion-header" id="headingThree_3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree_3" aria-expanded="false" aria-controls="collapseThree_3">
                                        What types of cryptocurrencies can I trade on Swappium?
                                    </button>
                                </h2>
                                <div id="collapseThree_3" class="accordion-collapse collapse" aria-labelledby="headingThree_3" data-bs-parent="#home_cy_acc_1">
                                    <div class="accordion-body">
                                        Swappium supports a wide variety of cryptocurrencies, including Bitcoin, Ethereum, Litecoin, and many more. We're constantly adding new coins to our platform to give our users the best possible trading experience.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="accordion" id="home_cy_acc_2">
                            <div class="accordion-item shadow-sm">
                                <h2 class="accordion-header" id="headingOne_1_2">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne_1_2" aria-expanded="false" aria-controls="collapseOne_1_2">
                                        How do I deposit funds into my Swappium wallet?
                                    </button>
                                </h2>
                                <div id="collapseOne_1_2" class="accordion-collapse collapse show" aria-labelledby="headingOne_1_2" data-bs-parent="#home_cy_acc_2">
                                    <div class="accordion-body collapse show">
                                        To deposit funds into your Swappium wallet, simply navigate to the "Deposit" page and select the currency you wish to deposit. Follow the instructions to complete the transaction and your funds will be credited to your wallet.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item shadow-sm">
                                <h2 class="accordion-header" id="headingTwo_2_2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo_2_2" aria-expanded="false" aria-controls="collapseTwo_2_2">
                                        Is my information secure on Swappium?
                                    </button>
                                </h2>
                                <div id="collapseTwo_2_2" class="accordion-collapse collapse" aria-labelledby="headingTwo_2_2" data-bs-parent="#home_cy_acc_2">
                                    <div class="accordion-body">
                                        Yes, Swappium takes the security of our users' information very seriously. We use advanced encryption and security protocols to ensure that your data is always protected.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item shadow-sm">
                                <h2 class="accordion-header" id="headingThree_3_2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree_3_2" aria-expanded="false" aria-controls="collapseThree_3_2">
                                        How do I contact Swappium support?
                                    </button>
                                </h2>
                                <div id="collapseThree_3_2" class="accordion-collapse collapse" aria-labelledby="headingThree_3_2" data-bs-parent="#home_cy_acc_2">
                                    <div class="accordion-body">
                                        If you need help with anything on Swappium, our support team is available 24/7 to assist you. Simply click on the "Contact Us" link at the bottom of our homepage and fill out the form to get in touch with us.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
.accordion-button,
.accordion-button:not(.collapsed) {
    background-color: #12161e;
}

.accordion-button.collapsed {
    color: #ddd;
}

.accordion-body {
    background-color: #12161e;
    color: #909090;
}

.accordion-item {
    border: 1px solid #2f2f2f !important;
}

.accordion {
    --bs-accordion-border-color: #2f2f2f;
    --bs-accordion-bg: #000;
}

.anss {
    margin-top: 66px !important;
}

@media only screen and (max-width: 995px) {
    .col-md-6 {
        margin-top: 38px;
    }
}
</style>
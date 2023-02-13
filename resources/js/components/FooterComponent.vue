<template>
    <div>
        <footer class="footer">
            <div class="py-7 py-md-8 py-lg-10">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 my-3 me-auto mx-4 footer-top">
                            <div class="py-4">
                                <img src="/assets/img/logo.png" alt="Swappium" width="70px">
                            </div>

                            <p class="text-secondary">Crypto collectibles and non-fungible tokens (NFTs). Buy, sell, and discover exclusive digital items.</p>
                        </div>
                        <div class="col-lg-3 col-sm-6 my-3">
                            <h5 class="footer-title">Company & Team</h5>
                            <ul class="list-unstyled footer-link-01 m-0">
                                <li>
                                    <router-link class="link-white" to="/about">About</router-link>
                                </li>
                                <li>
                                    <router-link class="link-white" to="/how-it-works">How it works</router-link>
                                </li>
                                <li>
                                    <router-link class="link-white" to="/contact">Contacts</router-link>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-3 col-sm-6 my-3  ">
                            <h5 class="footer-title">Legal Documents</h5>
                            <ul class="list-unstyled footer-link-01 m-0">
                                <li>
                                    <router-link class="link-white" to="/privacy">Privace &amp; Policy</router-link>
                                </li>
                                <li>
                                    <router-link class="link-white" to="/aml-kyc">AML / KYC</router-link>
                                </li>
                                <li>
                                    <router-link class="link-white" to="/terms">Terms and Conditions</router-link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="container footer-bottom">
                <div class="row py-3 gy-3 align-items-center">
                    <div class="col-md-6">
                        <ul class="nav justify-content-center justify-content-md-start my-2 links-white small list-unstyled footer-link-01 m-0">
                            <li class="p-0 mx-3 ms-md-0 me-md-3"><a href="#" class="link-white">Privace &amp; Policy</a></li>
                            <li class="p-0 mx-3 ms-md-0 me-md-3"><a href="#" class="link-white">AML</a></li>
                            <li class="p-0 mx-3 ms-md-0 me-md-3"><a href="#" class="link-white">Terms</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="text-white-85 small m-0">© 2023 Swappium </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.footer-title {
    text-transform: uppercase;
    font-weight: 300;
    font-size: 15px;
    letter-spacing: 4px;
    margin-bottom: 30px;
    margin-top: 30px;
}

a {
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 4px;
    text-decoration: none;
}

.footer-bottom {
    margin-top: 8px;
}

li {
    margin-top: 8px;
}

ul {
    margin-bottom: 12px
}

@media only screen and (max-width: 995px) {
    .footer-top {
        display: none;
    }
}
</style>
<template>
    <div class="dashboard">
        <aside>
            <SidebarComponent />
        </aside>

        <main>
            <h1>Settings</h1>
            <p class="mb-4 text-secondary">From here you can manage your profile informartion and access to API</p>

            <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                        type="button" role="tab" aria-controls="home" aria-selected="true">Profile</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                        type="button" role="tab" aria-controls="profile" aria-selected="false">API Access</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                        type="button" role="tab" aria-controls="contact" aria-selected="false">Merchants</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row">
                        <div class="col-md-8">
                            <form class="mt-4" method="post">
                                <div class="form-group mb-3">
                                    <input type="email" placeholder="Email address" required="" autofocus=""
                                        class="form-control p-3 shadow-sm px-4" disabled>
                                </div>

                                <div class="form-group mb-3">
                                    <input type="text" placeholder="Your full name" required=""
                                        class="form-control shadow-sm px-4 p-3 text-primary">
                                </div>

                                <button type="submit" class="btn btn-primary btn-block mt-4 w-100 shadow-sm">Update
                                    informartion</button>
                            </form>

                            
                        </div>

                        <div class="col-md-4">
                            <form class="mt-4" method="post">
                                <div class="form-group mb-3">
                                    <input type="password" name="old_pasword" placeholder="Your current password" required=""
                                        class="form-control shadow-sm px-4 p-3 text-primary" v-model="pass['old_password']">
                                </div>
                                <div class="form-group mb-3">
                                    <input type="password" name="password" placeholder="New password" required=""
                                        class="form-control shadow-sm px-4 p-3 text-primary" v-model="pass['password']">
                                </div>
                                <div class="form-group mb-3">
                                    <input type="password" name="password_confirmation" placeholder="Confirm new password" required=""
                                        class="form-control shadow-sm px-4 p-3 text-primary" v-model="pass['password_confirmation']">
                                </div>

                                <button type="submit" class="btn btn-primary btn-block mt-4 w-100 shadow-sm">Change
                                    password</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
            </div>

        </main>
    </div>
</template>

<script setup>
    import SidebarComponent from '../../components/dashboard/SidebarComponent.vue';
</script>

<script>
import { ref } from 'vue';

export default {
    name: 'DashboardSettings',

    data() {
        return {
            pass: {
                old_password: ref(''),
                password: ref(''),
                password_confirmation: ref('')
            }
        }
    },

    components: {
        SidebarComponent
    },

    methods: {
        changePassword() {

        }
    }
}
</script>

<style scoped>
    header {
        padding-bottom: 36px;
    }

    main {
        padding: 40px 40px;
    }

    @media only screen and (max-width: 600px) {
        main {
            padding: 30px 12px;
        }
    }
</style>

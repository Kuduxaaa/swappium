import './bootstrap';

import { createApp } from 'vue';
import { SnackbarService, Vue3Snackbar } from 'vue3-snackbar';

import App from './views/App.vue';
import router from './router/index';
import axios from 'axios';
import Auth from './auth';
import Api from './api';
import VueLazyLoad from 'vue3-lazyload'
import VueSweetalert2 from 'vue-sweetalert2';

import 'sweetalert2/dist/sweetalert2.min.css';
import 'vue3-snackbar/dist/style.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

import './assets/app.css';

axios.defaults.baseURL = '/api/';

const app = createApp(App);
const auth = new Auth();
const api = new Api(axios);

app.config.globalProperties.$router = router;
app.config.globalProperties.$axios = axios;
app.config.globalProperties.$api = api;
app.config.globalProperties.$auth = auth;

app.use(router);
app.use(SnackbarService);
app.use(VueSweetalert2);
app.use(VueLazyLoad);
app.component("vue3-snackbar", Vue3Snackbar);
app.mount('#app');

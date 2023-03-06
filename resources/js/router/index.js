import { createRouter, createWebHistory } from "vue-router";
import HomeView from '../views/pages/Home.vue'
import AboutView from '../views/pages/About.vue'
import LoginView from '../views/auth/LoginView.vue'
import RegisterView from '../views/auth/RegisterView.vue'

import Terms from '../views/pages/Terms.vue';
import Privacy from'../views/pages/Privacy.vue';
import AmlKyc from'../views/pages/AmlKyc.vue';
import HowItWorks from'../views/pages/HowItWorks.vue';
import Contact from'../views/pages/Contact.vue';
import NotFound from '../views/errors/NotFound.vue';

import DashboardHome from '../views/dashboard/DashboardHome.vue';
import DashboardMarkets from '../views/dashboard/DashboardMarkets.vue';
import DashboardOrders from '../views/dashboard/DashboardOrders.vue';
import DashboardSettings from '../views/dashboard/DashboardSettings.vue';
import DashboardWallets from '../views/dashboard/DashboardWallets.vue';
import TickerDetails from '../views/dashboard/TickerDetails.vue';
import Auth from "../auth";


const routes = [
    {
        path: '/',
        name: 'home',
        component: HomeView,
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/about',
        name: 'about',
        component: AboutView,
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/about',
        name: 'about',
        component: AboutView,
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/how-it-works',
        name: 'how-it-works',
        component: HowItWorks,
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/contact',
        name: 'contact',
        component: Contact,
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/privacy',
        name: 'privacy',
        component: Privacy,
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/aml-kyc',
        name: 'aml-kyc',
        component: AmlKyc,
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/terms',
        name: 'terms',
        component: Terms,
        meta: {
            requiresAuth: false
        }
    },

    // Auth routes

    {
        path: '/auth/login',
        name: 'login',
        component: LoginView,
        meta: {
            requiresAuth: false
        }
    },

    {
        path: '/auth/register',
        name: 'register',
        component: RegisterView,
        meta: {
            requiresAuth: false
        }
    },

    // Dashboard

    {
        path: '/console',
        name: 'dashboard',
        component: DashboardHome,
        meta: {
            requiresAuth: true
        }
    },

    {
        path: '/console/markets',
        name: 'dashboard.markets',
        component: DashboardMarkets,
        meta: {
            requiresAuth: true
        }
    },

    {
        path: '/console/orders',
        name: 'dashboard.orders',
        component: DashboardOrders,
        meta: {
            requiresAuth: true
        }
    },

    {
        path: '/console/settings',
        name: 'dashboard.settings',
        component: DashboardSettings,
        meta: {
            requiresAuth: true
        }
    },

    {
        path: '/console/wallets',
        name: 'dashboard.wallets',
        component: DashboardWallets,
        meta: {
            requiresAuth: true
        }
    },

    {
        path: '/console/markets/:ticker',
        name: 'dashboard.ticker',
        component: TickerDetails,
        meta: {
            requiresAuth: true
        }
    },

    // Handlers

    {
        path: "/:catchAll(.*)",
        name: 'Not Found',
        component: NotFound,
        meta: {
            requiresAuth: false
        }
    },
]

const auth = new Auth();
const router = createRouter({
    history: createWebHistory(),
    routes: routes
});

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth) {
        if (auth.check()) {
            next();
        } else {
            next('/auth/login');
        }
    } else {
        next();
    }
});

export default router;

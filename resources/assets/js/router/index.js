import Vue from 'vue';
import VueRouter from 'vue-router';

import Register from '../views/Auth/Register.vue';
import Login from '../views/Auth/Login.vue';
import Account from '../views/Account/Account.vue';
import Transaction from '../views/Account/Transaction.vue';
import Admin from '../views/Admin.vue';
import Landing from '../views/Landing.vue';
Vue.use(VueRouter);

const router = new VueRouter({
    routes: [
        {path: '/', component: Landing},
        {path: '/accounts', component: Account},
        {path: '/transactions', component: Transaction},
        {path: '/login', component: Login},
        {path: '/register', component: Register},
        {path: '/admin', component: Admin},
    ]
});

export default router;
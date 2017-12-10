import Vue from 'vue';
import VueRouter from 'vue-router';

import Register from '../views/Auth/Register.vue';
import Login from '../views/Auth/Login.vue';
import Account from '../views/Account/Account.vue';
import Transaction from '../views/Account/Transaction.vue';
import Admin from '../views/Admin.vue';
import Landing from '../views/Landing.vue';
import Change from '../views/Auth/Change.vue';
import UserIndex from '../views/Admin/User/Index.vue';
import UserCreate from '../views/Admin/User/Create.vue';
import UserEdit from '../views/Admin/User/Edit.vue';
import AccountIndex from '../views/Admin/Account/Index.vue';
import AccountCreate from '../views/Admin/Account/Create.vue';
import AccountEdit from '../views/Admin/Account/Edit.vue';

Vue.use(VueRouter);

const router = new VueRouter({
    routes: [
        {path: '/', component: Landing},
        {path: '/accounts', component: Account},
        {path: '/transactions', component: Transaction},
        {path: '/login', component: Login},
        {path: '/register', component: Register},
        {path: '/admin', component: Admin},
        {path: '/admin/users', component: UserIndex},
        {path: '/admin/users/create', component: UserCreate},
        {path: '/admin/users/edit/:id', component: UserEdit},
        {path: '/admin/accounts', component: AccountIndex},
        {path: '/admin/accounts/create', component: AccountCreate},
        {path: '/admin/accounts/edit/:id', component: AccountEdit},
        {path: '/change', component: Change},
    ]
});

export default router;
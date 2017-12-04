import './bootstrap';

import App from './App.vue';
import router from './router';

const app = new Vue({
    el: '#root-1',
    template: '<app></app>',
    components: {App},
    router
});
import VueRouter from 'vue-router';

var routes = [
    {path: '/', component: view('User')}
];

function view(name) {
    return function(resolve) {
        require(['./components/' + name + '.vue'], resolve);
    };
}

export default new VueRouter({
    routes
});
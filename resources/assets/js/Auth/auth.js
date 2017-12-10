export default {
    state: {
        api_token: null,
        user_id: null,
        is_admin: null,
        first_name: null,
    },
    initialize() {
        this.state.api_token = localStorage.getItem('api_token');
        this.state.user_id = localStorage.getItem('user_id');
        this.state.is_admin = localStorage.getItem('is_admin');
        this.state.first_name = localStorage.getItem('first_name');
    },
    set(api_token, user_id, is_admin, first_name) {
        localStorage.setItem('api_token', api_token);
        localStorage.setItem('user_id', user_id);
        localStorage.setItem('is_admin', is_admin);
        localStorage.setItem('first_name', first_name);
        this.initialize();
    },
    remove() {
        localStorage.removeItem('api_token');
        localStorage.removeItem('user_id');
        localStorage.removeItem('is_admin');
        localStorage.removeItem('first_name');
        this.initialize();
    }
}
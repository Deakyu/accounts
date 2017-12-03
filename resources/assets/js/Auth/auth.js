export default {
    state: {
        api_token: null,
        user_id: null,
        is_admin: null,
    },
    initialize() {
        this.state.api_token = localStorage.getItem('api_token');
        this.state.user_id = localStorage.getItem('user_id');
        this.state.is_admin = localStorage.getItem('is_admin');
    },
    set(api_token, user_id, is_admin) {
        localStorage.setItem('api_token', api_token);
        localStorage.setItem('user_id', user_id);
        localStorage.setItem('is_admin', is_admin);
        this.initialize();
    },
    remove() {
        localStorage.removeItem('api_token');
        localStorage.removeItem('user_id');
        localStorage.removeItem('is_admin');
        this.initialize();
    }
}
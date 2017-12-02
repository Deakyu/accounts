import Auth from '../Auth/auth';

export function get(url) {
    return axios({
        method: 'get',
        url: url,
        headers: {
            'Authorization': `Bearer ${Auth.state.api_token}`
        }
    });
};
export function post(url, data) {
    return axios({
        method: 'post',
        url: url,
        data: data,
        headers: {
            'Authorization': `Bearer ${Auth.state.api_token}`
        }
    });
}

export function del(url) {
    return axios({
        method: 'delete',
        url: url,
        headers: {
            'Authorization' : `Bearer ${Auth.state.api_token}`
        }
    });
}
import axios from 'axios';
import store from "../../store.js";

class Auth {
    /**
     * Login user
     * @param body
     * @returns {Promise<axios.AxiosResponse<any>>}
     */
    login(body) {
        return axios.post('/oauth/token', body);
    }

    /**
     * Register user
     * @param body
     * @returns {Promise<axios.AxiosResponse<any>>}
     */
    register(body) {
        return axios.post('/api/auth/register', body);
    }

    /**
     * Logout user
     * @returns {Promise<axios.AxiosResponse<any>>}
     */
    logout() {
        return axios.delete('/api/auth/logout');
    }
}

export default new Auth();

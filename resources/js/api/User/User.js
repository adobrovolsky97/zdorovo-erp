import axios from 'axios';

class Auth {

    /**
     * Get self user
     *
     * @returns {Promise<axios.AxiosResponse<any>>}
     */
    getMe() {
        return axios.get('/api/users/me');
    }
}

export default new Auth();

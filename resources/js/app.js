import './bootstrap';
import '../css/app.css';

import {createApp} from 'vue';
import router from "./router.js";
import store from './store'

import {APP_URL, GOOGLE_CLIENT_ID} from "./env.js";
import vue3GoogleLogin from 'vue3-google-login'

import axios from "axios";

axios.defaults.headers['Content-Type'] = 'application/json';
axios.defaults.headers['Accept'] = 'application/json';

axios.interceptors.request.use(config => {
    const token = localStorage.getItem("token") ?? localStorage.getItem('packer_token');
    if (token) {
        config.headers["Authorization"] = `Bearer ${token}`;
    }
    return config;
});

axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response.status == 401) {
            store.commit('clearUser')
            store.commit('clearPackerUser')
            router.push({name: 'login'});
        }
        throw error;
    }
);

import App from './views/App.vue';

const app = createApp(App);
app.use(router);
app.use(store);

app.use(vue3GoogleLogin, {
    clientId: GOOGLE_CLIENT_ID,
})


app.mount('#app');

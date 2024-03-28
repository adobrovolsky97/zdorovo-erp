import {createStore} from 'vuex'
import {LIGHT_THEME} from "./env.js";

const store = createStore({
    state: {
        user: localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null,
        token: localStorage.getItem('token'),
        theme: localStorage.getItem('theme') || LIGHT_THEME,
        packer_user: localStorage.getItem('packer_user') ? JSON.parse(localStorage.getItem('packer_user')) : null,
        packer_token: localStorage.getItem('packer_token'),
    },
    mutations: {
        clearPackerUser(state) {
            state.packer_user = null;
            state.packer_token = null;
            localStorage.removeItem('packer_user');
            localStorage.removeItem('packer_token');
        },
        setPackerUser(state, user) {
            store.state.packer_user = user;
            localStorage.setItem('packer_user', JSON.stringify(user));
        },
        setPackerToken(state, token) {
            state.packer_token = token;
            localStorage.setItem('packer_token', token);
        },
        clearUser(state) {
            state.user = null;
            state.token = null;
            localStorage.removeItem('user');
            localStorage.removeItem('token');
        },
        setUser(state, user) {
            store.state.user = user;
            localStorage.setItem('user', JSON.stringify(user));
        },
        setToken(state, token) {
            state.token = token;
            localStorage.setItem('token', token);
        },
        setTheme(state, theme) {
            state.theme = theme;
            localStorage.setItem('theme', theme);
        }
    },
    getters: {
        isLightTheme(state) {
            return state.theme === LIGHT_THEME;
        }
    }
})

export default store;

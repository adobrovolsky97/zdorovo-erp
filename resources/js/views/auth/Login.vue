<template>
    <div class="flex flex-col items-center justify-center gap-3 register-controller relative h-full w-full" ref="form">
        <div class="flex-cell self-center pb-2">
            <h3 class="title text-4xl font-bold">Авторизація 👋</h3>
        </div>
        <div class="flex flex-col w-full m-auto p-4">
            <label class="form-control w-full">
                <div class="label">
                    <span class="label-text">Email</span>
                </div>
                <input type="text" v-model="username" placeholder="Email" class="input input-bordered w-full"/>
            </label>
            <label class="form-control w-full">
                <div class="label">
                    <span class="label-text">Пароль</span>
                </div>
                <input type="password" v-model="password" placeholder="Пароль" class="input input-bordered w-full"/>
                <div class="label" v-if="isError">
                    <span class="label-text text-error">Невірні дані для входу</span>
                </div>
            </label>

            <button class="btn w-full btn-outline mt-8" :class="{'btn-disabled': isLoading}" @click="login">Вхід
            </button>
        </div>
        <div>
            Увійти як
            <router-link to="/packer-login" class="link">
                пакувальник
            </router-link>
        </div>
    </div>
</template>
<script>

import User from "../../api/User/User.js";
import Auth from "../../api/Auth/Auth.js";
import {CLIENT_SECRET, CLIENT_ID} from "../../env.js";
import {useLoading} from 'vue3-loading-overlay';
import 'vue3-loading-overlay/dist/vue3-loading-overlay.css';
import {googleTokenLogin} from "vue3-google-login";

export default {
    data() {
        return {
            isLoading: false,
            isError: false,
            username: null,
            password: null,
            loader: useLoading()
        }
    },
    methods: {
        /**
         * Login
         */
        login() {
            // this.loader.show({container: this.$refs.form, loader: 'bars'});
            this.isError = false;
            Auth.login({
                username: this.username,
                password: this.password,
                grant_type: 'password',
                client_id: CLIENT_ID,
                client_secret: CLIENT_SECRET,
            })
                .then((response) => {
                    this.$store.commit('setToken', response.data.access_token)
                })
                .then(() => {
                    User.getMe()
                        .then((response) => {
                            this.$store.commit('setUser', response.data.data);
                            this.$router.push({name: 'products'});
                        })
                })
                .catch(err => {
                    this.isError = true;
                    // this.loader.hide();
                })

        }
    }
}
</script>

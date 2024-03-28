<template>
    <div class="flex flex-col items-center justify-center gap-3 register-controller relative h-full w-full">
        <div class="flex-cell self-center pb-2">
            <h3 class="title text-4xl font-bold">Register</h3>
        </div>
        <div class="flex flex-col w-full m-auto p-4">
            <label class="form-control w-full">
                <div class="label">
                    <span class="label-text">Name</span>
                </div>
                <input type="text" v-model="form.name" placeholder="First Name"
                       class="input input-bordered w-full"/>
                <span class="text-red-600 text-xs" v-if="errors?.name">{{ errors.name[0] }}</span>
            </label>

            <label class="form-control w-full">
                <div class="label">
                    <span class="label-text">GitHub Username</span>
                </div>
                <input type="text" v-model="form.github_username" placeholder="GitHub Username"
                       class="input input-bordered w-full"/>
                <span class="text-red-600 text-xs" v-if="errors?.github_username">{{ errors.github_username[0] }}</span>
            </label>

            <label class="form-control w-full">
                <div class="label">
                    <span class="label-text">GitHub Token</span>
                </div>
                <input type="text" v-model="form.github_token" placeholder="GiHub Token"
                       class="input input-bordered w-full"/>
                <span class="text-red-600 text-xs" v-if="errors?.github_token">{{ errors.github_token[0] }}</span>
            </label>

            <label class="form-control w-full">
                <div class="label">
                    <span class="label-text">Email</span>
                </div>
                <input type="text" name="eml" v-model="form.email" placeholder="Email" autocomplete="off" class="input input-bordered w-full"/>
                <span class="text-red-600 text-xs" v-if="errors?.email">{{ errors.email[0] }}</span>
            </label>

            <label class="form-control w-full">
                <div class="label">
                    <span class="label-text">Password</span>
                </div>
                <input type="password" name="pwd" autocomplete="off" v-model="form.password" placeholder="Password"
                       class="input input-bordered w-full"/>
                <span class="text-red-600 text-xs" v-if="errors?.password">{{ errors.password[0] }}</span>
            </label>
            <label class="form-control w-full">
                <div class="label">
                    <span class="label-text">Confirm Password</span>
                </div>
                <input type="password" v-model="form.password_confirmation" placeholder="Password Confirmation"
                       class="input input-bordered w-full"/>
                <span class="text-red-600 text-xs"
                      v-if="errors?.password_confirmation">{{ errors.password_confirmation[0] }}</span>
            </label>
            <button class="btn w-full btn-outline mt-8" @click="register">Sign Up</button>
            <small class="mt-1 text-center">By signing up you accept service terms and conditions.</small>
            <div class="flex flex-col w-full">
                <div class="divider">OR</div>
            </div>

            <div class="socials">
                <button class="btn w-full btn-outline" @click="socialAuth">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7"
                        fill="currentColor"
                        style="color: #ea4335"
                        viewBox="0 0 24 24">
                        <path
                            d="M7 11v2.4h3.97c-.16 1.029-1.2 3.02-3.97 3.02-2.39 0-4.34-1.979-4.34-4.42 0-2.44 1.95-4.42 4.34-4.42 1.36 0 2.27.58 2.79 1.08l1.9-1.83c-1.22-1.14-2.8-1.83-4.69-1.83-3.87 0-7 3.13-7 7s3.13 7 7 7c4.04 0 6.721-2.84 6.721-6.84 0-.46-.051-.81-.111-1.16h-6.61zm0 0 17 2h-3v3h-2v-3h-3v-2h3v-3h2v3h3v2z"
                            fill-rule="evenodd"
                            clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
        <div>
            Already have account?
            <router-link to="/login" class="link">
                Please login
            </router-link>
        </div>
    </div>
</template>
<script>
import {googleTokenLogin} from "vue3-google-login";
import Auth from "../../api/Auth/Auth.js";
import {CLIENT_ID, CLIENT_SECRET} from "../../env.js";
import User from "../../api/User/User.js";
import {useLoading} from "vue3-loading-overlay";

export default {
    data() {
        return {
            errors: {},
            loader: useLoading(),
            validationRules: {
                email: 'required|email',
                password: 'required|minLength:8',
            },
            auth: Auth,
            form: {
                name: null,
                github_username: null,
                github_token: null,
                email: null,
                password: null,
                password_confirmation: null,
            }
        }
    },
    methods: {
        /**
         * Social auth
         */
        socialAuth() {
            googleTokenLogin().then((response) => {
                this.loader.show({container: this.$refs.form, loader: 'bars'});
                Auth.login({
                    access_token: response.access_token,
                    provider: 'google',
                    grant_type: 'social',
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
                    .finally(() => {
                        this.loader.hide();
                    });
            })
        },
        /**
         * Register user
         */
        register() {
            this.errors = {};
            this.loader.show({container: this.$refs.form, loader: 'bars'});

            Auth.register(this.form)
                .then(() => {
                    Auth.login({
                        username: this.form.email,
                        password: this.form.password,
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
                                .finally(() => {
                                    this.loader.hide();
                                })
                        })
                        .catch(() => {
                            this.loader.hide();
                        })
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors
                    }
                    this.loader.hide();
                })

        }
    }
}
</script>

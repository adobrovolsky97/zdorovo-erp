<template>
    <div class="data">

        <div class="flex flex-row justify-between items-center">
            <h1 class="text-3xl font-extrabold">Категорії</h1>
            <div
                class="gap-4 mt-2">
                <p class="badge badge-outline py-4 text-left text-sm">Знайдено категорій: {{
                        data?.meta?.total ?? 0
                    }}</p>
            </div>
        </div>

        <div class="mt-8">
            <div class="form flex flex-row justify-between items-center gap-4">
                <input type="text" v-model="form.name" placeholder="Назва категорії"
                       class="input input-bordered w-full"/>
                <button class="btn btn-success" @click="createCategory">
                    <span v-if="isBtnLoading" class="loading loading-spinner"></span>
                    Додати
                </button>
            </div>
            <TableSkeleton :items="15" v-if="!isDataLoaded"/>

            <div class="mt-8 border rounded-lg p-4" v-if="isDataLoaded && data?.meta?.total > 0">
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <!-- head -->
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Назва</th>
                            <th>Дії</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="category in data.data" :key="category.id">
                            <th>{{ category.id }}</th>
                            <td><input type="text" v-model="category.name" class="input input-bordered"/></td>
                            <td class="flex flex-row gap-2">
                                <button @click="updateCategory(category.id)" class="btn btn-success btn-sm btn-circle">
                                    <svg class="h-5 w-5 text-green-200" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                        <polyline points="22 4 12 14.01 9 11.01"/>
                                    </svg>
                                </button>
                                <button @click="deleteCategory(category.id)" class="btn btn-error btn-sm btn-circle">
                                    <svg class="h-5 w-5 text-red-200" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4" v-if="isDataLoaded && data?.meta?.total === 0">
                <p class="text-center text-2xl font-bold">Не знайдено категорій</p>
            </div>
        </div>
        <div class="pagination mt-4"
             v-if="isDataLoaded && data?.meta?.last_page > 1">
            <Pagination
                :limit="5"
                :data="data"
                @pagination-change-page="updatePage"
            />
        </div>
    </div>
</template>
<script>
import TableSkeleton from "../../components/skeleton/TableSkeleton.vue";
import Pagination from "../../components/pagination/Pagination.vue";
import {useRoute} from "vue-router";
import axios from "axios";

export default {
    name: "List",
    data() {
        return {
            timer: null,
            isDataLoaded: false,
            isBtnLoading: false,
            data: {},
            filters: {
                page: 1,
            },
            form: {
                name: null,
            },
            route: useRoute()
        }
    },
    components: {
        Pagination,
        TableSkeleton
    },
    computed: {
        user() {
            return this.$store.state.user;
        }
    },
    beforeRouteLeave() {
        this.isDataLoaded = false;
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        /**
         * Update page
         * @param page
         */
        updatePage(page) {
            this.filters.page = page;
        },
        /**
         * Fetch data from API
         */
        fetchData() {
            this.isDataLoaded = false;
            axios.get('/api/categories', {params: this.filters})
                .then((response) => {
                    this.data = response.data;
                })
                .finally(() => {
                    this.isDataLoaded = true;
                });
        },
        createCategory() {
            this.isBtnLoading = true;
            axios.post('/api/categories', this.form)
                .then((response) => {
                    this.fetchData();
                    this.form.name = null;
                })
                .finally(() => {
                    this.isBtnLoading = false;
                });
        },
        deleteCategory(id) {
            axios.delete(`/api/categories/${id}`)
                .then((response) => {
                    this.fetchData();
                });
        },
        updateCategory(id) {
            let category = this.data.data.find(category => category.id === id);
            axios.put(`/api/categories/${id}`, category);
        }
    }
}
</script>

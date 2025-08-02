<template>
    <div class="data">

        <div class="flex flex-row justify-between items-center">
            <h1 class="text-3xl font-extrabold">Товари</h1>

            <div class="flex flex-row items-center gap-4">
                <button @click="exportProducts" class="btn btn-sm btn-info rounded-full text-white">Експортувати</button>

                <p class="badge badge-outline py-4 text-left text-sm">Знайдено товарів: {{data?.meta?.total ?? 0}}</p>
            </div>
        </div>

        <div class="mt-8">
            <div class="form mb-8 flex flex-row justify-between items-start gap-4">
                <div class="w-full">
                    <div class="label">
                        <span class="label-text">Назва товару</span>
                    </div>
                    <input type="text" v-model="filters.search" placeholder="Назва товару"
                           class="input input-bordered w-full"/>
                </div>
                <div class="w-full">
                    <TagInput v-model="filters['categories[]']" @updated="handleCategoryUpdated" :options="categories"
                              :label="'Обрати Категорії'"/>
                </div>
                <div class="w-full">
                    <div class="label">
                        <span class="label-text">Наявність</span>
                    </div>
                    <select class="select select-bordered w-full" v-model="filters.is_available">
                        <option :value="null">Показувати всі</option>
                        <option :value="1">Лише в наявності</option>
                        <option :value="0">Лише не в наявності</option>
                    </select>
                </div>
                <div class="w-full">
                    <div class="label">
                        <span class="label-text">Bimpsoft Синхронізація</span>
                    </div>
                    <select class="select select-bordered w-full" v-model="filters.is_synced_with_crm">
                        <option :value="null">Показувати всі</option>
                        <option :value="1">Лише синхронізовані</option>
                        <option :value="0">Лише не синхронізовані</option>
                    </select>
                </div>
            </div>


            <TableSkeleton :items="15" v-if="!isDataLoaded"/>

            <div v-if="isDataLoaded" class="list grid grid-cols-1 md:grid-cols-3 gap-4">
                <Item :product="product" :categories="categories" v-for="product in data?.data" :key="product.id"/>
            </div>
            <div v-if="isDataLoaded && data?.meta?.total === 0">
                <p class="text-center text-2xl font-bold">Не знайдено товарів</p>
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
import Item from "./Item.vue";
import TagInput from "../../components/TagInput/TagInput.vue";
import {toast} from "vue3-toastify";

export default {
    name: "List",
    data() {
        return {
            timer: null,
            isDataLoaded: false,
            data: {},
            categories: [],
            search: '',
            filters: {
                is_available: null,
                is_synced_with_crm: null,
                page: 1,
                'categories[]': [],
                search: '',
            },
            route: useRoute()
        }
    },
    components: {
        TagInput,
        Item,
        Pagination,
        TableSkeleton
    },
    watch: {
        'filters': {
            handler: function () {
                this.fetchData();
            },
            deep: true
        }
    },
    mounted() {
        this.fetchCategories()
            .then(() => {
                this.resolveQueryParams();
            });
    },
    computed: {
        queryParams() {
            return this.filters;
        },
        user() {
            return this.$store.state.user;
        }
    },
    beforeRouteLeave() {
        this.isDataLoaded = false;
    },
    methods: {
        /**
         * Update page
         * @param page
         */
        updatePage(page) {
            this.filters.page = page;
        },
        handleCategoryUpdated(categories) {
            this.filters['categories[]'] = categories;
        },
        /**
         * Update query params
         */
        getQueryParams() {
            let params = {...this.queryParams};

            if (params['categories[]']?.length > 0) {
                // map only if its object and not integer
                params['categories[]'] = params['categories[]'].map(category => category.id ? category.id : category);
            }

            return params;
        },
        fetchCategories() {
            return axios.get('/api/categories')
                .then((response) => {
                    this.categories = response.data.data;
                });
        },
        /**
         * Fetch data from API
         */
        fetchData() {
            let params = this.getQueryParams();
            this.$router.push({query: params});

            this.isDataLoaded = false;

            axios.get('/api/products', {params: params})
                .then((response) => {
                    this.data = response.data;
                })
                .finally(() => {
                    this.isDataLoaded = true;
                });
        },
        exportProducts() {
            let params = this.getQueryParams();
            delete params.page;
            axios.post('/api/exports/products/create', {
                categories: params['categories[]'],
                search: params.search,
                is_available: params.is_available,
                is_synced_with_crm: params.is_synced_with_crm
            })
                .then(() => {
                    toast.success('Експорт успішно створено. Завантажте файл з вкладки експорти...');
                })
                .catch(() => {
                    toast.error('Сталась помилка при експорті товарів.');
                });
        },
        /**
         * Resolve query params
         */
        resolveQueryParams() {
            this.filters = {...this.filters, ...this.route.query};

            if (this.filters['categories[]'].length > 0) {
                this.filters['categories[]'] = Object.values(this.filters['categories[]']);
                this.filters['categories[]'] = this.categories.filter(category => this.filters['categories[]'].includes(category.id.toString()));
            }
        },
    }
}
</script>

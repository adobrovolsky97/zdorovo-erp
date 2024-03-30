<template>
    <div class="data">

        <div class="flex flex-row justify-between items-center">
            <h1 class="text-3xl font-extrabold">Товари</h1>
            <div
                class="gap-4 mt-2">
                <p class="badge badge-outline py-4 text-left text-sm">Знайдено товарів: {{
                        data?.meta?.total ?? 0
                    }}</p>
            </div>
        </div>

        <div class="mt-8">

            <div class="form mb-8 flex flex-row justify-between items-start gap-4">
                <div class="w-full">
                    <div class="label">
                        <span class="label-text">Назва товару</span>
                    </div>
                    <div class="join w-full">
                        <div class="w-full">
                            <div>
                                <input type="text" v-model="filters.search" placeholder="Назва товару"
                                       class="input input-bordered w-full join-item"/>
                            </div>
                        </div>
                        <div class="indicator">
                            <button @click="clearSearch" class="btn btn-outline join-item">Очистити</button>
                        </div>
                    </div>

                </div>
                <div class="w-full">
                    <TagInput v-model="filters['categories[]']" @updated="handleCategoryUpdated" :options="categories"
                              :label="'Обрати Категорії'"/>
                </div>
            </div>


            <TableSkeleton :items="15" v-if="!isDataLoaded"/>

            <div v-if="isDataLoaded" class="list grid grid-cols-1 md:grid-cols-3 gap-4">
                <Item
                    :show-edit="false"
                    :packed-product="getPackedProduct(product.id)"
                    :product="product"
                    v-for="product in data?.data"
                    :key="product.id"
                />
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
import Item from "../product/PackerItem.vue";
import TagInput from "../../components/TagInput/TagInput.vue";

export default {
    name: "List",
    data() {
        return {
            timer: null,
            isDataLoaded: false,
            data: {},
            categories: [],
            package: [],
            packedProducts: [],
            search: '',
            filters: {
                page: 1,
                is_synced_with_crm: null,
                is_available: null,
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
                this.fetchPackage();
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
        getPackedProduct(id) {
            return this.packedProducts.find(product => product.id === id);
        },
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
        clearSearch() {
            this.filters.search = '';
        },
        fetchPackage() {
            axios.get('/api/packages')
                .then((response) => {
                    this.package = response.data.data;

                    if (this.package?.products?.length > 0) {
                        this.packedProducts = this.package.products.map((product) => {
                            return {
                                id: product.id,
                                quantity: product.quantity
                            }
                        });
                    }
                });
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
            params.is_available = 1;
            params.is_synced_with_crm = 1;
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

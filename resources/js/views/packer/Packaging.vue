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

            <div v-if="isModalShown && form.product" class="relative z-10" aria-labelledby="modal-title" role="dialog"
                 aria-modal="true">
                <div class="fixed inset-0 bg-opacity-75 transition-opacity"></div>

                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div class="card bg-neutral text-neutral-content" style="width: 500px">
                            <div class="card-body items-center text-center border rounded-lg">
                                <h2 class="card-title mb-2">Налаштування упакування</h2>
                                <div class="card-content">
                                    <p class="mb-4">Товар '{{ form.product.name }}'</p>
                                    <div v-if="form.product.is_synced_with_crm"
                                         class="flex flex-col gap-2 justify-center items-center">
                                        <div class="flex flex-col w-full justify-start items-start">
                                            <p class="text-sm">Дой-Пак</p>
                                            <select v-model="form.pack" class="select select-bordered w-full">
                                                <option :value="null">Дой-Пак не обрано</option>
                                                <option :value="pack" v-for="pack in packValues" :key="pack">
                                                    {{ pack }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="flex mt-4 flex-row w-full justify-center gap-2 items-start">
                                            <button @click="decrement"
                                                    class="btn w-12 btn-success btn-outline">-
                                            </button>
                                            <input type="text" v-model="form.qty" class="input input-bordered w-48">
                                            <button @click="increment"
                                                    class="btn w-12 btn-success btn-outline">+
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-actions justify-end mt-4">
                                    <button @click="addProduct" class="btn btn-success">Запакувати</button>
                                    <button @click="hideAddModal" class="btn btn-outline">Закрити</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="isDataLoaded" class="list grid grid-cols-1 md:grid-cols-3 gap-4">
                <router-link
                    v-for="product in data?.data"
                    :key="product.id"
                    :to="{name: 'products'}"
                    custom
                    v-slot="{ navigate }"
                >
                    <div class="card bg-base-100 shadow-xl border">
                        <figure class="pt-10">
                            <img :src="product.image" alt="image" style="width: 200px; height: 170px;"/>
                        </figure>
                        <div class="card-body items-center text-center">
                            <h2 class="text-md font-bold">{{ product.name }}</h2>
                            <div class="flex flex-col gap-2 w-full">
                                <div class="flex flex-row items-center justify-center gap-2">
                                    <span class="font-bold">Категорія:</span> {{
                                        product.category?.name ?? 'Не вказана'
                                    }}
                                </div>
                                <div class="flex flex-row items-center justify-center gap-2">
                                    <span class="font-bold">Дой-Пак:</span> {{ product.pack ?? 'Не вказано' }}
                                </div>
                                <div class="flex flex-row items-center justify-center gap-2">
                                </div>
                                <div class="border rounded-lg p-2" v-if="getPackedProductsForCurrentProduct(product).length > 0">
                                    Запаковано:
                                    <div v-for="packedProduct in getPackedProductsForCurrentProduct(product)" :key="product.id">
                                        <p>{{packedProduct.custom_pack ? packedProduct.custom_pack + ' Дой-Пак' :  'Стандартний Дой-Пак'}} -  {{packedProduct.quantity}} уп.</p>
                                    </div>
                                </div>

                                <button v-if="product.is_synced_with_crm"
                                        @click="showAddModal(product)"
                                        class="btn btn-outline mt-2 btn-success">
                                    Запакувати
                                </button>
                            </div>
                        </div>
                    </div>
                </router-link>
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
import TagInput from "../../components/TagInput/TagInput.vue";
import {toast} from "vue3-toastify";

export default {
    name: "List",
    data() {
        return {
            packValues: [
                150, 250, 500, 1000
            ],
            timer: null,
            isModalShown: false,
            isDataLoaded: false,
            data: {},
            categories: [],
            package: [],
            packedProducts: [],
            search: '',
            form: {
                product: null,
                pack: null,
                qty: 1
            },
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
        },
    },
    beforeRouteLeave() {
        this.isDataLoaded = false;
    },
    methods: {
        showAddModal(product) {
            this.form.product = product;
            this.isModalShown = true;
            this.form.qty = 1;
        },
        getPackedProductsForCurrentProduct(product) {
            return this.packedProducts.filter(packedProduct => packedProduct.id === product.id);
        },
        hideAddModal() {
            this.isModalShown = false;
            this.form.product = null;
            this.form.qty = 1;
            this.form.pack = null;
        },
        addProduct() {
            if (this.form.qty < 1) {
                toast("Невірна кількість товару", {
                    "position": "bottom-right",
                    "theme": this.$store.state.theme,
                    "type": "error",
                })
                this.form.qty = 1;
                return;
            }

            axios.post('/api/packages/products/' + this.form.product.id, {
                quantity: this.form.qty,
                pack: this.form.pack
            })
                .then(response => {
                    toast("Товар додано до пакету", {
                        "position": "bottom-right",
                        "theme": this.$store.state.theme,
                        "type": "success",
                    })
                    this.packedProducts = [...response?.data?.data?.products];

                    this.isModalShown = false;
                    this.form.product = null;
                    this.form.qty = 1;
                    this.form.pack = null;
                })
                .catch(error => {
                    toast("Щось пішло не так", {
                        "position": "bottom-right",
                        "theme": this.$store.state.theme,
                        "type": "error",
                    })
                });
        },
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
        increment() {
            this.form.qty++;
        },
        decrement() {
            if (this.form.qty > 1) {
                this.form.qty--;
            }
        },
        fetchPackage() {
            axios.get('/api/packages')
                .then((response) => {
                    this.package = response.data.data;

                    if (this.package?.products?.length > 0) {
                        this.packedProducts = this.package.products.map((product) => {
                            return {
                                id: product.id,
                                quantity: product.quantity,
                                custom_pack: product.custom_pack
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

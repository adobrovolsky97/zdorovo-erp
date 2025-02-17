<template>
    <div class="data">

        <div class="flex flex-row justify-between items-center">
            <h1 class="text-3xl font-extrabold">Завдання на фасовку</h1>
            <div
                class="gap-4 mt-2">
                <p class="badge badge-outline py-4 text-left text-sm">Знайдено завдань: {{
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
                    <input type="text" v-model="filters.search" placeholder="Назва товару"
                           class="input input-bordered w-full"/>
                </div>
            </div>
            <TableSkeleton :items="15" v-if="!isDataLoaded"/>

            <div v-if="isDataLoaded && data?.meta?.total > 0" class="w-full border rounded-lg p-4">
                <div class="overflow-x-auto">
                    <table class="table">
                        <!-- head -->
                        <thead>
                        <tr>
                            <th :class="{'text-neutral': filters.order_by === 'name'}">
                                <span @click="toggleSort('name')"
                                      class="flex cursor-pointer flex-row gap-1 items-center"
                                      v-if="filters.order_dir === 'asc'">
                                    Назва
                                    <svg v-if="filters.order_by==='name'" class="h-5 w-5" width="24" height="24"
                                         viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line
                                        x1="12" y1="5" x2="12" y2="19"/>  <line x1="18" y1="11" x2="12" y2="5"/>  <line
                                        x1="6" y1="11" x2="12" y2="5"/></svg>
                                </span>
                                <span @click="toggleSort('name')"
                                      class="flex cursor-pointer flex-row gap-1 items-center" v-else>
                                    Назва
                                    <svg v-if="filters.order_by==='name'" class="h-5 w-5" width="24" height="24"
                                         viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line
                                        x1="12" y1="5" x2="12" y2="19"/>  <line x1="18" y1="13" x2="12" y2="19"/>  <line
                                        x1="6" y1="13" x2="12" y2="19"/></svg>
                                </span>
                            </th>
                            <th :class="{'text-neutral': filters.order_by === 'leftovers'}">
                                <span @click="toggleSort('leftovers')"
                                      class="flex cursor-pointer flex-row gap-1 items-center"
                                      v-if="filters.order_dir === 'asc'">
                                    Залишок на складі
                                    <svg v-if="filters.order_by==='leftovers'" class="h-5 w-5" width="24" height="24"
                                         viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line
                                        x1="12" y1="5" x2="12" y2="19"/>  <line x1="18" y1="11" x2="12" y2="5"/>  <line
                                        x1="6" y1="11" x2="12" y2="5"/></svg>
                                </span>
                                <span @click="toggleSort('leftovers')"
                                      class="flex cursor-pointer flex-row gap-1 items-center" v-else>
                                    Залишок на складі
                                    <svg v-if="filters.order_by==='leftovers'" class="h-5 w-5" width="24" height="24"
                                         viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line
                                        x1="12" y1="5" x2="12" y2="19"/>  <line x1="18" y1="13" x2="12" y2="19"/>  <line
                                        x1="6" y1="13" x2="12" y2="19"/></svg>
                                </span>
                            </th>
                            <th :class="{'text-neutral': filters.order_by === 'ordered_qty'}">
                                <span @click="toggleSort('ordered_qty')"
                                      class="flex cursor-pointer flex-row gap-1 items-center"
                                      v-if="filters.order_dir === 'asc'">
                                   Кількість у замовленнях
                                    <svg v-if="filters.order_by==='ordered_qty'" class="h-5 w-5" width="24" height="24"
                                         viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line
                                        x1="12" y1="5" x2="12" y2="19"/>  <line x1="18" y1="11" x2="12" y2="5"/>  <line
                                        x1="6" y1="11" x2="12" y2="5"/></svg>
                                </span>
                                <span @click="toggleSort('ordered_qty')"
                                      class="flex cursor-pointer flex-row gap-1 items-center" v-else>
                                    Кількість у замовленнях
                                    <svg v-if="filters.order_by==='ordered_qty'" class="h-5 w-5" width="24" height="24"
                                         viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line
                                        x1="12" y1="5" x2="12" y2="19"/>  <line x1="18" y1="13" x2="12" y2="19"/>  <line
                                        x1="6" y1="13" x2="12" y2="19"/></svg>
                                </span>
                            </th>
                            <th :class="{'text-neutral': filters.order_by === 'qty_to_process'}">
                                <span @click="toggleSort('qty_to_process')"
                                      class="flex cursor-pointer flex-row gap-1 items-center"
                                      v-if="filters.order_dir === 'asc'">
                                   Потрібно нафасувати
                                    <svg v-if="filters.order_by==='qty_to_process'" class="h-5 w-5" width="24" height="24"
                                         viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line
                                        x1="12" y1="5" x2="12" y2="19"/>  <line x1="18" y1="11" x2="12" y2="5"/>  <line
                                        x1="6" y1="11" x2="12" y2="5"/></svg>
                                </span>
                                <span @click="toggleSort('qty_to_process')"
                                      class="flex cursor-pointer flex-row gap-1 items-center" v-else>
                                    Потрібно нафасувати
                                    <svg v-if="filters.order_by==='qty_to_process'" class="h-5 w-5" width="24" height="24"
                                         viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line
                                        x1="12" y1="5" x2="12" y2="19"/>  <line x1="18" y1="13" x2="12" y2="19"/>  <line
                                        x1="6" y1="13" x2="12" y2="19"/></svg>
                                </span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr :class="{'bg-red-100': item.quantity_to_process > 0, 'bg-green-100': item.quantity_to_process < 0}" v-for="item in data.data" :key="item.id">
                            <th>{{ item.name }}</th>
                            <th>{{ item.leftovers ?? 0 }}</th>
                            <th>{{ item.ordered_qty ?? 0 }}</th>
                            <th>{{ item.quantity_to_process}}</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div v-if="isDataLoaded && data?.meta?.total === 0">
                <p class="text-center text-2xl font-bold">Не знайдено залишків</p>
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

export default {
    name: "Leftovers",
    data() {
        return {
            timer: null,
            isDataLoaded: false,
            data: {},
            search: '',
            filters: {
                order_by: 'name',
                order_dir: 'asc',
                page: 1,
            },
            route: useRoute()
        }
    },
    components: {
        TagInput,
        Pagination,
        TableSkeleton
    },
    mounted() {
        this.resolveQueryParams();
    },
    watch: {
        'filters' : {
            handler: function () {
                this.fetchData();
            },
            deep: true
        },
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
        /**
         * Update query params
         */
        getQueryParams() {
            return {...this.queryParams};
        },
        /**
         * Fetch data from API
         */
        fetchData() {
            let params = this.getQueryParams();
            this.$router.push({query: params});

            this.isDataLoaded = false;

            axios.get('/api/products/tasks', {params: params})
                .then((response) => {
                    this.data = response.data;
                })
                .finally(() => {
                    this.isDataLoaded = true;
                });
        },
        toggleSort(param) {
            this.filters.order_dir = this.filters.order_by !== param ? 'asc' : (this.filters.order_dir === 'asc' ? 'desc' : 'asc');
            this.filters.order_by = param;
        },
        /**
         * Resolve query params
         */
        resolveQueryParams() {
            this.filters = {...this.filters, ...this.route.query};
        },
    }
}
</script>

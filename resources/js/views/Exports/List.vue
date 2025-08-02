<template>
    <div class="data">

        <div class="flex flex-row justify-between items-center">
            <h1 class="text-3xl font-extrabold">Експорти</h1>
            <div
                class="gap-4 mt-2">
                <p class="badge badge-outline py-4 text-left text-sm">Знайдено експортів: {{
                        data?.meta?.total ?? 0
                    }}</p>
            </div>
        </div>

        <div class="mt-8">
            <TableSkeleton :items="15" v-if="!isDataLoaded"/>

            <div v-if="isDataLoaded && data?.meta?.total > 0" class="w-full border rounded-lg p-4">
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <!-- head -->
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Назва</th>
                            <th>Статус</th>
                            <th>Помилка</th>
                            <th>Час</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in data.data" :key="item.id">
                            <th>{{ item.id }}</th>
                            <th>{{ item.name }}</th>
                            <th>{{ item.status }}</th>
                            <th>{{ item.error }}</th>
                            <th>{{ item.created_at }}</th>
                            <th>
                                <svg v-if="item.status === 'finished'" class="h-5 w-5 cursor-pointer"
                                     @click="downloadExport(item)" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                            </th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div v-if="isDataLoaded && data?.meta?.total === 0">
                <p class="text-center text-2xl font-bold">Не знайдено експортів</p>
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
    name: "ExportsList",
    data() {
        return {
            timer: null,
            isDataLoaded: false,
            data: {},
            search: '',
            filters: {
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
    watch: {
        'filters': {
            handler: function () {
                this.fetchData();
            },
            deep: true
        }
    },
    mounted() {
        this.resolveQueryParams();
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

            axios.get('/api/exports', {params: params})
                .then((response) => {
                    this.data = response.data;
                })
                .finally(() => {
                    this.isDataLoaded = true;
                });
        },
        downloadExport(exportModel){
            axios({
                url: `/api/exports/${exportModel.id}/download`,
                method: 'GET',
                responseType: 'blob'
            })
                .then(response => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', exportModel.name); // or any other file name
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                })
                .catch((e) => {
                    console.log(e)
                    toast.error('Помилка завантаження експорту');
                })
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

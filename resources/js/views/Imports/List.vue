<template>
    <div class="data">

        <div class="flex flex-row justify-between items-center">
            <h1 class="text-3xl font-extrabold">Імпорти</h1>
            <div
                class="gap-4 mt-2">
                <p class="badge badge-outline py-4 text-left text-sm">Знайдено імпортів: {{
                        data?.meta?.total ?? 0
                    }}</p>
            </div>
        </div>

        <div class="w-full flex flex-row justify-start items-center mt-4 gap-2">
            <label class="form-control w-full">
                <input ref="fileInput" type="file" class="file-input file-input-bordered" @change="onFileChange"/>
            </label>
            <div>
                <button
                    class="btn btn-info text-white ml-2"
                    :disabled="!selectedFile || isUploading"
                    @click.prevent="uploadFile"
                >
                    {{ isUploading ? 'Завантаження...' : 'Завантажити' }}
                </button>
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
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in data.data" :key="item.id">
                            <th>{{ item.id }}</th>
                            <th>{{ item.name }}</th>
                            <th>{{ item.status }}</th>
                            <th>{{ item.error }}</th>
                            <th>{{ item.created_at }}</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div v-if="isDataLoaded && data?.meta?.total === 0">
                <p class="text-center text-2xl font-bold">Не знайдено імпортів</p>
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
            selectedFile: null,
            isUploading: false,
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
        onFileChange(event) {
            const file = event.target.files[0];
            this.selectedFile = file ? file : null;
        },
        async uploadFile() {
            if (!this.selectedFile) return;

            this.isUploading = true;

            try {
                const formData = new FormData();
                formData.append('file', this.selectedFile);

                // Приклад запиту - підстав свій URL і налаштування
                const response = await axios.post('/api/imports/products/upload', formData, {
                    headers: {'Content-Type': 'multipart/form-data'}
                });

                toast.success('Файл успішно завантажено!');
                this.selectedFile = null;
                this.$refs.fileInput.value = null;

                this.fetchData();
            } catch (error) {
                toast.error('Помилка завантаження файлу');
                console.error(error);
            } finally {
                this.isUploading = false;
            }
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

            axios.get('/api/imports', {params: params})
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
        },
    }
}
</script>

<template>
    <div class="data">

        <div class="flex flex-row justify-between items-center">
            <h1 class="text-3xl font-extrabold">Товари</h1>
            <div
                class="gap-4 mt-2">
                <p class="badge badge-outline py-4 text-left text-sm">Запаковано позицій: {{
                        package?.products?.length ?? 0
                    }}</p>
            </div>
        </div>

        <div class="mt-8">

            <TableSkeleton :items="15" v-if="isLoading"/>

            <div v-if="isModalShown" class="relative z-10" aria-labelledby="modal-title" role="dialog"
                 aria-modal="true">
                <div class="fixed inset-0 bg-opacity-75 transition-opacity"></div>

                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div class="card w-96 bg-base-100">
                            <div class="card-body items-center text-center border rounded-lg">
                                <h2 class="card-title mb-2">Чи підтверджуєте Ви видалення?</h2>
                                <div class="card-actions justify-end">
                                    <button @click="deleteProduct" class="btn btn-warning">Так</button>
                                    <button @click="hideDeleteModal" class="btn btn-outline">Ні</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="isSendModalShown" class="relative z-10" aria-labelledby="modal-title" role="dialog"
                 aria-modal="true">
                <div class="fixed inset-0 bg-opacity-75 transition-opacity"></div>

                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div class="card w-96 bg-base-100">
                            <div class="card-body items-center text-center border rounded-lg">
                                <h2 class="card-title mb-2">Чи підтверджуєте Ви відправку замовлення у Bimpsoft?</h2>
                                <div class="card-actions justify-end">
                                    <button :disabled="isButtonDisabled" @click="sendToCrm" class="btn btn-success">
                                        Так
                                    </button>
                                    <button @click="isSendModalShown = false" class="btn btn-outline">Ні</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 border rounded-lg p-4" v-if="!isLoading && package?.products?.length">
                <div class="w-full flex flex-row justify-end">
                    <button @click="isSendModalShown = true" class="btn btn-success self-end">Відправити в Bimpsoft
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <!-- head -->
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Назва</th>
                            <th>Кількість</th>
                            <th>Кастомний Дой-Пак</th>
                            <th>Дії</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="product in package?.products" :key="product.id">
                            <th>{{ product.id }}</th>
                            <th>{{ product.name }}</th>
                            <th>
                                <input type="text" class="input-bordered input input-sm w-24"
                                       v-model="product.quantity">
                            </th>
                            <th>{{ product.custom_pack }}</th>
                            <td class="flex flex-row gap-2">
                                <button @click="updateProduct(product.id, product.quantity)"
                                        class="btn btn-success btn-sm btn-circle">
                                    <svg class="h-5 w-5 text-green-200" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                        <polyline points="22 4 12 14.01 9 11.01"/>
                                    </svg>
                                </button>
                                <button @click="showDeleteModal(product)" class="btn btn-error btn-sm btn-circle">
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
            <div v-if="!isLoading && !package?.products?.length">
                <p class="text-center text-2xl font-bold">Немає запакованих товарів</p>
            </div>
        </div>
    </div>
</template>
<script>
import Pagination from "../../components/pagination/Pagination.vue";
import TagInput from "../../components/TagInput/TagInput.vue";
import TableSkeleton from "../../components/skeleton/TableSkeleton.vue";
import {toast} from "vue3-toastify";

export default {
    name: "Package",
    components: {TableSkeleton, TagInput, Pagination},
    data() {
        return {
            isButtonDisabled: false,
            isSendModalShown: false,
            productToDelete: null,
            package: [],
            isModalShown: false,
            isLoading: true,
        }
    },
    mounted() {
        this.fetchPackage();
    },
    methods: {
        fetchPackage() {
            this.isLoading = true;

            axios.get('/api/packages')
                .then(response => {
                    this.package = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                })
                .finally(() => {
                    this.isLoading = false;
                })
        },
        showDeleteModal(product) {
            this.productToDelete = product;
            this.isModalShown = true;
        },
        hideDeleteModal() {
            this.productToDelete = null;
            this.isModalShown = false;
        },
        deleteProduct() {

            if (!this.productToDelete) {
                return;
            }

            axios.delete(`/api/packages/products/${this.productToDelete.pack_id}`)
                .then((response) => {
                    this.package = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                })
                .finally(() => {
                    this.productToDelete = null;
                    this.isModalShown = false;
                });
        },
        sendToCrm() {
            this.isButtonDisabled = true;
            axios.post('/api/packages/' + this.package.id + '/send')
                .then(() => {
                    this.isSendModalShown = false;
                    toast("Замовлення відправлено у Bimpsoft", {
                        "position": "bottom-right",
                        "theme": this.$store.state.theme,
                        "type": "success",
                    })
                    this.package = null;
                })
                .catch(error => {
                    toast('Сталась помилка при відправці у Bimpsoft', {
                        "position": "bottom-right",
                        "theme": this.$store.state.theme,
                        "type": "error",
                    })
                })
                .finally(() => {
                    this.isButtonDisabled = false;
                });
        },
        updateProduct(id, qty) {
            if (qty < 0) {
                toast("Невірна кількість товару", {
                    "position": "bottom-right",
                    "theme": this.$store.state.theme,
                    "type": "error",
                })
                return;
            }
            axios.post(`/api/packages/products/${id}`, {
                quantity: qty
            })
                .then((response) => {
                    this.package = response.data.data;
                    toast("Товар оновлено", {
                        "position": "bottom-right",
                        "theme": this.$store.state.theme,
                        "type": "success",
                    })
                })
                .catch(error => {
                    toast("Невірна кількість товару", {
                        "position": "bottom-right",
                        "theme": this.$store.state.theme,
                        "type": "error",
                    })
                })
        }
    },
}
</script>

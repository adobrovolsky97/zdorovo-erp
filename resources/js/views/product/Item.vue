<template>
    <router-link
        :to="{name: 'products'}"
        custom
        v-slot="{ navigate }"
    >
        <div class="card bg-base-100 shadow-xl border">
            <div v-if="product.is_synced_with_crm" class="badge badge-success absolute left-3 top-3">Синхронізано з Bimpsoft</div>
            <div class="badge badge-neutral absolute left-3 top-10" v-if="product.leftover !== null">
                Залишок: {{ product.leftover}}
            </div>

            <figure class="pt-10">
                <img :src="product.image" alt="image" style="width: 200px; height: 170px;"/>
            </figure>
            <button @click="isEditMode = !isEditMode"
                    class="btn bg-orange-500 btn-sm btn-circle absolute right-2 top-2">
                <svg class="h-5 w-5 text-orange-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>

            </button>
            <div class="card-body items-center text-center flex flex-col justify-between">
                <h2 class="text-md font-bold">{{ product.name }}</h2>
                <div class="card-actions w-full flex flex-col gap-2" v-if="isEditMode">
                    <div class="flex flex-col w-full justify-start items-start">
                        <p class="text-sm">Категорія</p>
                        <select v-model="localProduct.category.id" class="select select-sm select-bordered w-full">
                            <option :value="null">Категорія не обрана</option>
                            <option :value="category.id" v-for="category in categories" :key="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                    <div class="flex flex-col w-full justify-start items-start">
                        <p class="text-sm">Дой-Пак</p>
                        <select v-model="localProduct.pack" class="select select-sm select-bordered w-full">
                            <option :value="null">Дой-Пак не обрано</option>
                            <option :value="pack" v-for="pack in packValues" :key="pack">
                                {{ pack }}
                            </option>
                        </select>
                    </div>
                    <div class="flex flex-col w-full justify-start items-start">
                        <p class="text-sm">Показувати?</p>
                        <input true-value="1" false-value="0" type="checkbox" v-model="localProduct.is_available" class="checkbox checkbox-warning">
                    </div>
                    <button class="btn btn-success btn-sm self-end" @click="updateProduct">Зберегти</button>
                </div>
                <div v-else class="flex flex-col gap-2 w-full">
                    <div class="flex flex-row items-center justify-center gap-2">
                        <span class="font-bold">Категорія:</span> {{ localProduct.category?.name ?? 'Не вказана' }}
                    </div>
                    <div class="flex flex-row items-center justify-center gap-2">
                        <span class="font-bold">Дой-Пак:</span> {{ localProduct.pack ?? 'Не вказано' }}
                    </div>
                    <div class="flex flex-row items-center justify-center gap-2">
                        <span class="font-bold"
                              :class="{'text-success': localProduct.is_available, 'text-warning': !localProduct.is_available}">{{ localProduct.is_available ? 'Відображається' : 'Не відображається' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </router-link>
</template>
<script>
import {toast} from "vue3-toastify";
import 'vue3-toastify/dist/index.css';

export default {

    props: {
        product: {
            type: Object,
            required: true
        },
        categories: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            localProduct: this.product,
            isEditMode: false,
            packValues: [
                150, 250, 500, 1000
            ]
        }
    },
    mounted() {
    },
    methods: {
        updateProduct() {
            axios.put(`/api/products/${this.localProduct.id}`, {
                category_id: this.localProduct.category.id,
                pack: this.localProduct.pack,
                is_available: this.localProduct.is_available
            })
                .then(response => {
                    this.localProduct = response.data.data;

                    toast("Товар оновлено", {
                        "position": "bottom-right",
                        "theme": this.$store.state.theme,
                        "type": "success",
                    })
                    this.isEditMode = false;
                })
                .catch(error => {
                    toast("Виникла помилка при оновленні товара", {
                        "position": "bottom-right",
                        "theme": this.$store.state.theme,
                        "type": "error",
                    })
                });
        }
    }
}
</script>

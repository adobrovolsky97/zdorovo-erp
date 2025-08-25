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
                            <option :value="pack.id" v-for="pack in packValues" :key="pack.id">
                                {{ pack.name }}
                            </option>
                        </select>
                    </div>
                    <div class="flex flex-col w-full justify-start items-start">
                        <p class="text-sm">Ярлик</p>
                        <select v-model="localProduct.label.id" class="select select-sm select-bordered w-full">
                            <option :value="null">Ярлик не обрано</option>
                            <option :value="label.id" v-for="label in labelValues" :key="label.id">
                                {{ label.name }}
                            </option>
                        </select>
                    </div>
                    <div class="flex flex-col w-full justify-start items-start">
                        <p class="text-sm">Показувати?</p>
                        <input true-value="1" false-value="0" type="checkbox" v-model="localProduct.is_available" class="checkbox checkbox-warning">
                    </div>
                    <button class="btn btn-success btn-sm self-end" @click="updateProduct">Зберегти</button>
                </div>
                <div v-else class="flex flex-col gap-1 w-full">
                    <div class="flex flex-row items-center justify-center gap-2">
                        <span class="font-bold">Категорія:</span> {{ localProduct.category?.name ?? 'Не вказана' }}
                    </div>
                    <div class="flex flex-row items-center justify-center gap-2">
                        <span class="font-bold">Дой-Пак:</span> {{ localProduct.pack ?? 'Не вказано' }}
                    </div>
                    <div class="flex flex-row items-center justify-center gap-2">
                        <span class="font-bold">Ярлик:</span> {{ localProduct.label?.name ?? 'Не вказано' }}
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
            labelValues: [
                {
                    id: 'big_reserve_100',
                    name: 'Великий резерв 100'
                },
                {
                    id: 'big_reserve_300',
                    name: 'Великий резерв 300'
                },
                {
                    id: 'big_reserve_500',
                    name: 'Великий резерв 500'
                },
                {
                    id: 'small_reserve_10',
                    name: 'Малий резерв 10'
                },
                {
                    id: 'no_reserve',
                    name: 'Без резерву (ФК)'
                },
            ],
            packValues: [
                {id: '150', name: '150'},
                {id: '250', name: '250'},
                {id: '250-sh', name: '250-Ш'},
                {id: '500', name: '500'},
                {id: '500-b', name: '500-В'},
                {id: '1000', name: '1000'},
                {id: 'bag', name: 'Паперовий Мішок'},
                {id: 'polipropylen_bag', name: 'Поліпропіленовий мішок'},
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
                label: this.localProduct.label.id,
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

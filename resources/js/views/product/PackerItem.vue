<template>
    <router-link
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
                        <span class="font-bold">Категорія:</span> {{ product.category?.name ?? 'Не вказана' }}
                    </div>
                    <div class="flex flex-row items-center justify-center gap-2">
                        <span class="font-bold">Дой-Пак:</span> {{ product.pack ?? 'Не вказано' }}
                    </div>
                    <div class="flex flex-row items-center justify-center gap-2">
                        <span class="font-bold"
                              :class="{'text-success': product.is_available, 'text-warning': !product.is_available}">{{
                                product.is_available ? 'Відображається' : 'Не відображається'
                            }}</span>
                    </div>
                    <div v-if="product.is_synced_with_crm" class="flex flex-row gap-2 justify-center items-center">
                        <button @click="decrement" class="btn btn-sm btn-circle btn-warning btn-outline">-</button>
                        <input type="text" v-model="qty" class="input input-sm input-bordered w-24">
                        <button @click="increment" class="btn btn-circle btn-sm btn-warning btn-outline">+</button>
                    </div>
                    <button v-if="product.is_synced_with_crm" @click="addProduct" class="btn btn-outline btn-sm mt-2"
                            :class="{'btn-success': localPackedProduct === null, 'btn-warning': localPackedProduct !== null}">
                        {{ localPackedProduct === null ? 'Запакувати' : 'Запаковано' }}
                    </button>
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
        showEdit: {
            type: Boolean,
            default: true
        },
        packedProduct: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            localPackedProduct: this.packedProduct,
            qty: this.packedProduct?.quantity || 1
        }
    },
    mounted() {
    },
    methods: {
        increment() {
            this.qty++;
        },
        decrement() {
            if (this.qty > 1) {
                this.qty--;
            }
        },
        addProduct() {
            if (this.qty < 1) {
                toast("Невірна кількість товару", {
                    "position": "bottom-right",
                    "theme": this.$store.state.theme,
                    "type": "error",
                })
                this.qty = 1;
            }

            axios.post('/api/packages/products/' + this.product.id, {
                quantity: this.qty
            })
                .then(response => {
                    toast("Товар додано до пакету", {
                        "position": "bottom-right",
                        "theme": this.$store.state.theme,
                        "type": "success",
                    })
                    this.localPackedProduct = {
                        id: this.product.id,
                        quantity: this.qty
                    }
                })
                .catch(error => {
                    toast("Щось пішло не так", {
                        "position": "bottom-right",
                        "theme": this.$store.state.theme,
                        "type": "error",
                    })
                })
        }
    }
}
</script>

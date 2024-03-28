<template>
    <div class="page min-h-screen">
        <main>
            <div class="navbar-area mx-auto max-w-7xl px-1 pt-8">
                <Navbar/>
            </div>
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-4">
                <router-view v-slot="{ Component }">
                    <transition name="fade" mode="out-in">
                        <component :is="Component"/>
                    </transition>
                </router-view>
            </div>
        </main>
    </div>
</template>

<script setup>
import Navbar from "../dashboard/Navbar.vue";
</script>

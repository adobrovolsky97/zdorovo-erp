<template>
    <main>
        <div class="flex items-center justify-center gap-0 authentication mx-auto h-screen text-sm">
            <div class="container mx-auto px-4 sm:px-0 max-w-[500px] border rounded-xl">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <router-view v-slot="{ Component }">
                            <transition name="fade" mode="out-in">
                                <component :is="Component"/>
                            </transition>
                        </router-view>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
<script setup>
</script>

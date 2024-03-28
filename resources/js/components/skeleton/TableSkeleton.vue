<template>
    <div class="table-skeleton flex flex-col gap-4">
        <div class="skeleton mt-4 h-10 w-full" v-for="item in Array.from({ length: items ?? 15 })"
             :key="item"></div>
    </div>
</template>
<script>
export default {
    props: ['items']
}
</script>

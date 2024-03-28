<template>
    <div class="flex flex-col gap-4">
        <div v-for="(item, index) in items" :key="index" class="skeleton" :class="item"></div>
    </div>
</template>
<script>
export default {
    name: "ContentSkeleton",
    props:  {
        items : {
            type: Array,
            default: () => [
                'h-32 w-full',
                'h-4 w-full',
                'h-4 w-full'
            ]
        }
    }
}
</script>

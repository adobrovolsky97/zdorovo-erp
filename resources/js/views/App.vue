<template>
    <div :data-theme="theme">
        <router-view></router-view>
    </div>
</template>

<script>
import { computed } from 'vue'
import { useStore } from 'vuex'

export default {
    setup () {
        const store = useStore()

        return {
            theme: computed(() => store.state.theme),
        }
    }
}
</script>

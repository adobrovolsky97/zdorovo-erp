<template>
    <RenderlessPagination
        :data="data"
        :limit="limit"
        :keep-length="keepLength"
        @pagination-change-page="onPaginationChangePage"
        v-slot="slotProps"
    >
        <div class="join border bg-base-100 border-gray-100 cursor-pointer w-full block mb-4 rounded-5xl p-2 shadow-xl" v-bind="$attrs" v-if="slotProps.computed.total > slotProps.computed.perPage">
            <button
                class="join-item btn btn-sm"
                :disabled="!slotProps.computed.prevPageUrl"
                v-on="slotProps.prevButtonEvents"
            >
                <slot name="prev-nav">
                    Previous
                </slot>
            </button>

            <button
                class="join-item btn btn-sm"
                :aria-current="slotProps.computed.currentPage ? 'page' : null"
                v-for="(page, key) in slotProps.computed.pageRange"
                :class="{'btn-neutral': slotProps.computed.currentPage === page}"
                :key="key"
                v-on="slotProps.pageButtonEvents(page)"
            >
                {{ page }}
            </button>

            <button
                class="join-item btn btn-sm"
                :disabled="!slotProps.computed.nextPageUrl"
                v-on="slotProps.nextButtonEvents"
            >
                <slot name="next-nav">
                    Next
                </slot>
            </button>
        </div>
    </RenderlessPagination>
</template>
<script>
import {RenderlessPagination} from "laravel-vue-pagination";

export default {
    inheritAttrs: false,
    emits: ['pagination-change-page'],
    components: {
        RenderlessPagination
    },
    props: {
        data: {
            type: Object,
            default: () => {
            }
        },
        limit: {
            type: Number,
            default: 0
        },
        keepLength: {
            type: Boolean,
            default: false
        },
    },
    methods: {
        onPaginationChangePage(page) {
            this.$emit('pagination-change-page', page);
        }
    }
}
</script>

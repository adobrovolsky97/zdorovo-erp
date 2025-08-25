<template>
    <div class="tags-input flex flex-col">
        <div class="label" v-if="label.length">
            <span class="label-text">{{ label }}</span>
        </div>
        <div class="input-area relative" v-click-outside="onClickOutside">
            <input ref="search" @click="handleInputClick" type="text" @input="searchTag" :placeholder="placeholder"
                   class="input input-bordered w-full"/>
            <div class="p-2 rounded-b-box bg-base-200 block w-full absolute left-0 top-full  z-[99]" v-if="searchAreaShown">
                <div v-if="optionsData.length > 0" class="badge badge-outline cursor-pointer p-3 m-1 whitespace-nowrap"
                     @click="addTag(tag)"
                     v-for="tag in optionsData"
                     :key="tag.id">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12M6 12h12"/>
                    </svg>
                    {{ tag.name }}
                </div>
                <div v-else>
                    <p class="text-center p-4">Не знайдено результатів.</p>
                </div>
            </div>
        </div>
        <div class="selected mt-2 flex flex-wrap gap-1.5">
            <div class="badge badge-neutral p-3 whitespace-nowrap" v-for="tag in localValue" :key="tag.id">
                {{ tag.name }}
                <svg xmlns="http://www.w3.org/2000/svg" @click="removeTag(tag)" fill="none" viewBox="0 0 24 24"
                     class="inline-block cursor-pointer w-4 h-4 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>
    </div>
</template>

<script>
import vClickOutside from "click-outside-vue3"

export default {
    name: "TagInput",
    props: {
        modelValue: {
            type: Array,
            default: () => []
        },
        limit: {
            type: Number,
            default: 15
        },
        options: {
            type: Array,
            default: () => []
        },
        placeholder: {
            type: String,
            default: 'Шукати...'
        },
        label: {
            type: String,
            default: 'Select tags'
        }
    },
    data() {
        return {
            optionsData: [],
            searchAreaShown: false,
            localValue: this.modelValue
        }
    },
    directives: {
        clickOutside: vClickOutside.directive
    },
    watch: {
        modelValue(newVal) {
            this.localValue = newVal;
        },
        localValue(newVal) {
            this.$emit('updated', newVal);
        },
    },
    methods: {
        removeTag(tag) {
            this.localValue = this.localValue.filter(t => t.id !== tag.id);
            this.$emit('updated', this.localValue);
        },
        searchTag() {
            this.searchAreaShown = true;
            // search tag and remove already selected tags and limit results to 10
            this.optionsData = this.options.filter(tag => {
                return tag.name.toLowerCase().startsWith(this.$refs.search.value.toLowerCase()) && !this.localValue.find(t => t.id === tag.id);
            }).slice(0, this.limit);
        },
        addTag(tag) {
            // do not allow duplicate tags
            if (this.localValue.find(t => t.id === tag.id)) {
                return;
            }

            this.localValue.push(tag);
            this.$emit('updated', this.localValue);
            this.$refs.search.value = '';
            this.searchAreaShown = false;
            this.$refs.search.focus();
        },
        handleInputClick() {
            this.searchAreaShown = true;

            if (this.$refs.search.value.length <= 0) {
                this.optionsData = this.options.filter(tag => {
                    return !this.localValue.find(t => t.id === tag.id);
                }).slice(0, this.limit);
            }
        },
        onClickOutside(event) {
            this.searchAreaShown = false;
        }
    }
}
</script>
<style>
</style>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    filters: Object,
    categories: Array
})

const hasFilters = computed(() => {
    return (props.filters?.search && props.filters.search.length > 0)
        || (props.filters?.category && props.filters.category.length > 0)
        || (props.filters?.sort && props.filters.sort !== 'normal')
})

const activeCategoryName = computed(() => {
    if (!props.filters?.category) return null
    return props.categories?.find(c => c.slug === props.filters.category)?.name || null
})

const sortLabel = computed(() => {
    const map = {
        latest:    'Paling Baru',
        cheapest:  'Harga Termurah',
        expensive: 'Harga Termahal',
    }
    return map[props.filters?.sort] || null
})
</script>
<template>
    <div v-if="hasFilters" class="flex flex-wrap items-center gap-2 mb-6">
        <span class="text-xs font-bold text-text-muted uppercase tracking-wider">Filter Aktif:</span>
        <span v-if="filters?.search" class="filter-tag inline-flex items-center gap-1.5 px-3 py-1 bg-accent/10 border border-accent/20 text-accent rounded-full text-xs font-bold">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            "{{ filters.search }}"
        </span>
        <span v-if="activeCategoryName" class="filter-tag inline-flex items-center gap-1.5 px-3 py-1 bg-accent/10 border border-accent/20 text-accent rounded-full text-xs font-bold">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
            {{ activeCategoryName }}
        </span>
        <span v-if="sortLabel" class="filter-tag inline-flex items-center gap-1.5 px-3 py-1 bg-surface border border-border-theme text-text-muted rounded-full text-xs font-bold">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
            {{ sortLabel }}
        </span>
    </div>
</template>
<style scoped>
.filter-tag {
    animation: tagPop 0.2s ease-out;
}
@keyframes tagPop {
    from { opacity: 0; transform: scale(0.85); }
    to   { opacity: 1; transform: scale(1); }
}
</style>

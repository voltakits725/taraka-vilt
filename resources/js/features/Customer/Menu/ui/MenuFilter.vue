<script setup>
import { ref, watch } from 'vue'
import { router, Link } from '@inertiajs/vue3'

const props = defineProps({
    categories: Array,
    filters: Object
})

const searchQuery = ref(typeof props.filters?.search === 'string' ? props.filters.search : '')
const sortBy = ref(typeof props.filters?.sort === 'string' ? props.filters.sort : 'normal')

const sortOptions = [
    { value: 'normal',    label: 'Normal',         icon: '✦' },
    { value: 'latest',    label: 'Terbaru',         icon: '🕐' },
    { value: 'cheapest',  label: 'Harga Termurah',  icon: '↑' },
    { value: 'expensive', label: 'Harga Termahal',  icon: '↓' },
]

let searchTimeout = null
watch([searchQuery, sortBy], ([newSearch, newSort]) => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        router.get('/menu', {
            search: newSearch,
            category: typeof props.filters?.category === 'string' ? props.filters.category : null,
            sort: newSort
        }, { preserveState: true, preserveScroll: true, replace: true })
    }, 350)
})

const clearSearch = () => {
    searchQuery.value = ''
}

const getActiveCategoryName = () => {
    if (!props.filters?.category) return null
    const cat = props.categories?.find(c => c.slug === props.filters.category)
    return cat?.name || null
}
</script>

<template>
    <div class="filter-container mb-10">

        <!-- Search & Sort Row -->
        <div class="flex flex-col sm:flex-row gap-3 mb-5">

            <!-- Search Input -->
            <div class="relative flex-1 group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                    <svg class="w-5 h-5 text-text-muted group-focus-within:text-accent transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    v-model="searchQuery"
                    type="text"
                    id="menu-search"
                    placeholder="Cari kopi, dessert, atau cemilan..."
                    class="search-input w-full pl-12 pr-11 py-4 bg-surface border border-border-theme rounded-2xl text-text-main placeholder-text-muted focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none transition-all duration-200 shadow-sm text-sm"
                >
                <!-- Clear button -->
                <button
                    v-if="searchQuery"
                    @click="clearSearch"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-text-muted hover:text-accent transition-colors duration-200"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <!-- Search loading indicator -->
                <div v-if="searchQuery" class="search-underline absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-accent via-accent/60 to-transparent rounded-full mx-4"></div>
            </div>

            <!-- Sort Dropdown -->
            <div class="relative w-full sm:w-auto sm:min-w-[200px] shrink-0">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                    </svg>
                </div>
                <select
                    v-model="sortBy"
                    id="menu-sort"
                    class="sort-select w-full pl-11 pr-10 py-4 bg-surface border border-border-theme rounded-2xl text-text-main font-semibold text-sm focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none transition-all duration-200 shadow-sm appearance-none cursor-pointer"
                >
                    <option v-for="opt in sortOptions" :key="opt.value" :value="opt.value">
                        Urutkan: {{ opt.label }}
                    </option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-text-muted">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Search Banner -->
        <div v-if="searchQuery" class="search-banner flex items-center gap-2 mb-4 px-4 py-2.5 bg-accent/8 border border-accent/20 rounded-xl text-sm">
            <svg class="w-4 h-4 text-accent shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <span class="text-text-muted">Hasil pencarian untuk: </span>
            <span class="font-bold text-text-main">"{{ searchQuery }}"</span>
            <button @click="clearSearch" class="ml-auto text-text-muted hover:text-accent font-bold text-xs underline transition-colors">Hapus</button>
        </div>

        <!-- Category Filter Pills -->
        <div class="flex gap-2 overflow-x-auto pb-2 custom-scrollbar">
            <Link
                href="/menu"
                :class="[
                    'category-pill px-5 py-2.5 rounded-2xl font-bold text-sm transition-all duration-200 whitespace-nowrap border shrink-0',
                    !filters?.category || typeof filters?.category !== 'string'
                        ? 'bg-accent border-accent text-white shadow-lg shadow-accent/25'
                        : 'bg-surface border-border-theme text-text-muted hover:bg-surface-hover hover:border-accent/40 hover:text-text-main'
                ]"
            >
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Semua Menu
                </span>
            </Link>

            <Link
                v-for="cat in categories"
                :key="cat.id"
                :href="`/menu?category=${cat.slug}&search=${searchQuery}&sort=${sortBy}`"
                :class="[
                    'category-pill px-5 py-2.5 rounded-2xl font-bold text-sm transition-all duration-200 whitespace-nowrap border shrink-0',
                    filters?.category === cat.slug
                        ? 'bg-accent border-accent text-white shadow-lg shadow-accent/25'
                        : 'bg-surface border-border-theme text-text-muted hover:bg-surface-hover hover:border-accent/40 hover:text-text-main'
                ]"
            >
                {{ cat.name }}
            </Link>
        </div>
    </div>
</template>

<style scoped>
.filter-container {
    position: relative;
}

.search-input {
    transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
}

.sort-select {
    transition: border-color 0.2s, box-shadow 0.2s;
}

.category-pill {
    transition: background-color 0.2s, border-color 0.2s, color 0.2s, transform 0.15s, box-shadow 0.2s;
}
.category-pill:hover {
    transform: translateY(-1px);
}
.category-pill:active {
    transform: translateY(0);
}

.search-banner {
    animation: slideDown 0.2s ease-out;
}

.search-underline {
    animation: expandWidth 0.3s ease-out;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}

@keyframes expandWidth {
    from { transform: scaleX(0); transform-origin: left; }
    to   { transform: scaleX(1); transform-origin: left; }
}

.custom-scrollbar::-webkit-scrollbar { height: 3px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: var(--border-color, #e2e8f0);
    border-radius: 20px;
}
.custom-scrollbar:hover::-webkit-scrollbar-thumb {
    background-color: var(--color-accent, #94a3b8);
}
</style>
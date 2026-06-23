<!-- resources/js/Pages/Customer/Menu/Index.vue -->
<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'
import MenuFilter from '../../../features/menu/ui/MenuFilter.vue'
import MenuCard from '../../../entities/menu/ui/MenuCard.vue'

const props = defineProps({
    menus: Object,
    categories: Array,
    filters: Object
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
    <CustomerLayout>

        <!-- Hero Section -->
        <div class="relative mb-10 overflow-hidden">
            <div class="hero-bg absolute inset-0 rounded-3xl opacity-60 pointer-events-none"></div>
            <div class="relative z-10 py-10 px-2">
                <div class="flex items-start gap-4">
                    <div class="flex-1">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-accent/10 border border-accent/20 text-accent rounded-full text-xs font-black uppercase tracking-widest mb-4">
                            <span class="w-1.5 h-1.5 bg-accent rounded-full animate-pulse"></span>
                            Katalog Menu
                        </div>
                        <h1 class="text-2xl sm:text-3xl md:text-5xl font-black mb-3 leading-tight tracking-tight" style="color: var(--text-main)">
                            Eksplorasi<br>
                            <span class="text-gradient">Rasa.</span>
                        </h1>
                        <p class="text-text-muted text-base md:text-lg max-w-md leading-relaxed">
                            Tersedia
                            <span class="font-black" style="color: var(--text-main)">{{ menus.total }} menu</span>
                            dari
                            <span class="font-black" style="color: var(--text-main)">{{ categories?.length || 0 }} kategori</span>
                            — temukan sajian yang pas buat mood lo hari ini!
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <MenuFilter :categories="categories" :filters="filters" />

        <!-- Active Filters Display -->
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

        <!-- Result Count Bar -->
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-text-muted">
                Menampilkan
                <span class="font-bold" style="color: var(--text-main)">{{ menus.from || 0 }}–{{ menus.to || 0 }}</span>
                dari
                <span class="font-bold" style="color: var(--text-main)">{{ menus.total }}</span>
                menu
            </p>
            <div v-if="menus.last_page > 1" class="text-xs text-text-muted">
                Hal. <span class="font-bold" style="color: var(--text-main)">{{ menus.current_page }}</span> / {{ menus.last_page }}
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="menus.data.length === 0" class="empty-state text-center py-24 bg-surface border border-border-theme rounded-3xl shadow-sm mb-12">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-surface-hover flex items-center justify-center">
                <svg class="w-10 h-10 text-text-muted opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-2" style="color: var(--text-main)">Waduh, menu nggak ketemu!</h3>
            <p class="text-text-muted mb-6">Coba cari pakai kata kunci lain atau pilih kategori yang berbeda.</p>
            <Link href="/menu" class="inline-flex items-center gap-2 px-6 py-3 bg-accent text-white rounded-2xl font-bold text-sm hover:opacity-90 transition-opacity shadow-lg shadow-accent/25">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Lihat Semua Menu
            </Link>
        </div>

        <!-- Menu Grid -->
        <div v-else class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-5 md:gap-6 mb-10">
            <MenuCard
                v-for="(menu, index) in menus.data"
                :key="menu.id"
                :menu="menu"
                :style="{ animationDelay: `${index * 60}ms` }"
                class="card-appear"
            />
        </div>

        <!-- Pagination -->
        <div v-if="menus.last_page > 1" class="flex items-center justify-center gap-2 mb-12">
            <!-- Prev -->
            <Link
                v-if="menus.prev_page_url"
                :href="menus.prev_page_url"
                class="pagination-btn flex items-center gap-1.5 px-4 py-2.5 bg-surface border border-border-theme text-text-muted hover:border-accent hover:text-accent rounded-xl font-bold text-sm transition-all duration-200 shadow-sm"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                Prev
            </Link>
            <span v-else class="flex items-center gap-1.5 px-4 py-2.5 bg-surface border border-border-theme text-text-muted/40 rounded-xl font-bold text-sm cursor-not-allowed opacity-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                Prev
            </span>

            <!-- Page Numbers -->
            <template v-for="page in menus.last_page" :key="page">
                <Link
                    v-if="Math.abs(page - menus.current_page) <= 2 || page === 1 || page === menus.last_page"
                    :href="`/menu?page=${page}&search=${filters?.search || ''}&category=${filters?.category || ''}&sort=${filters?.sort || 'normal'}`"
                    :class="[
                        'pagination-btn w-10 h-10 flex items-center justify-center rounded-xl font-bold text-sm transition-all duration-200 shadow-sm border',
                        page === menus.current_page
                            ? 'bg-accent border-accent text-white shadow-lg shadow-accent/25'
                            : 'bg-surface border-border-theme text-text-muted hover:border-accent hover:text-accent'
                    ]"
                >
                    {{ page }}
                </Link>
                <span
                    v-else-if="Math.abs(page - menus.current_page) === 3"
                    class="text-text-muted font-bold px-1"
                >...</span>
            </template>

            <!-- Next -->
            <Link
                v-if="menus.next_page_url"
                :href="menus.next_page_url"
                class="pagination-btn flex items-center gap-1.5 px-4 py-2.5 bg-surface border border-border-theme text-text-muted hover:border-accent hover:text-accent rounded-xl font-bold text-sm transition-all duration-200 shadow-sm"
            >
                Next
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </Link>
            <span v-else class="flex items-center gap-1.5 px-4 py-2.5 bg-surface border border-border-theme text-text-muted/40 rounded-xl font-bold text-sm cursor-not-allowed opacity-50">
                Next
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
        </div>

    </CustomerLayout>
</template>

<style scoped>
.text-gradient {
    background: linear-gradient(135deg, var(--color-accent, #8b5cf6), var(--color-accent-hover, #7c3aed));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-bg {
    background: radial-gradient(ellipse 80% 80% at 20% 50%, var(--color-accent, #8b5cf6) 0%, transparent 60%),
                radial-gradient(ellipse 60% 60% at 80% 20%, var(--color-accent, #8b5cf6) 0%, transparent 50%);
    opacity: 0.04;
}

.stat-pill {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.stat-pill:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

.filter-tag {
    animation: tagPop 0.2s ease-out;
}

.pagination-btn {
    transition: transform 0.15s ease, border-color 0.2s, color 0.2s, box-shadow 0.2s;
}
.pagination-btn:hover {
    transform: translateY(-1px);
}

.card-appear {
    animation: cardFadeIn 0.5s ease-out both;
}

@keyframes cardFadeIn {
    from {
        opacity: 0;
        transform: translateY(16px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes tagPop {
    from { opacity: 0; transform: scale(0.85); }
    to   { opacity: 1; transform: scale(1); }
}
</style>
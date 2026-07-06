<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    menus: Object,
    filters: Object
})
</script>
<template>
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
</template>
<style scoped>
.pagination-btn {
    transition: transform 0.15s ease, border-color 0.2s, color 0.2s, box-shadow 0.2s;
}
.pagination-btn:hover {
    transform: translateY(-1px);
}
</style>

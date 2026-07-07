<!-- resources/js/entities/menu/ui/MenuCard.vue -->
<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    menu: Object
})
</script>

<template>
    <Link :href="`/menu/${menu.slug}`" class="menu-card group relative bg-surface border border-border-theme rounded-2xl sm:rounded-3xl overflow-hidden flex flex-col transition-all duration-400 hover:shadow-2xl hover:-translate-y-2 hover:border-accent/40">

        <!-- Image Container -->
        <div class="relative overflow-hidden bg-surface-hover aspect-square sm:aspect-[4/3]">
            <img
                v-if="menu.image"
                :src="menu.image"
                :alt="menu.name"
                class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110"
            >
            <div v-else class="w-full h-full flex flex-col items-center justify-center gap-1 sm:gap-2 text-text-muted">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-[10px] sm:text-xs font-semibold opacity-40">No Image</span>
            </div>

            <!-- Gradient overlay on hover -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-400"></div>

            <!-- Category Badge -->
            <div class="absolute top-2 left-2 sm:top-3 sm:left-3">
                <span class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 bg-black/40 backdrop-blur-md text-white text-[9px] sm:text-[10px] font-black uppercase tracking-widest rounded-full border border-white/20">
                    {{ menu.category?.name || 'Spesial' }}
                </span>
            </div>

            <!-- Quick View Button (appears on hover, hidden on mobile/touch) -->
            <div class="hidden sm:flex absolute inset-x-0 bottom-0 justify-center pb-4 translate-y-full group-hover:translate-y-0 transition-transform duration-400 ease-out">
                <Link :href="`/menu/${menu.slug}`" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-gray-900 rounded-full font-bold text-sm shadow-xl hover:bg-accent hover:text-white transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat Detail
                </span>
            </div>
        </div>

        <!-- Content -->
        <div class="p-3 sm:p-5 flex flex-col flex-1">
            <h4 class="text-sm sm:text-base font-bold leading-tight mb-0.5 sm:mb-1 line-clamp-2" style="color: var(--text-main)">{{ menu.name }}</h4>

            <p v-if="menu.description" class="hidden sm:block text-xs leading-relaxed mb-3 line-clamp-2" style="color: var(--text-muted)">{{ menu.description }}</p>
            <div v-else class="hidden sm:block mb-3"></div>

            <div class="mt-auto flex items-center justify-between gap-2 sm:gap-3">
                <div class="font-extrabold text-sm sm:text-lg" style="color: var(--text-main)">
                    Rp {{ menu.price.toLocaleString('id-ID') }}
                </div>
                <span class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1.5 sm:px-4 sm:py-2 bg-accent/10 hover:bg-accent text-accent hover:text-white border border-accent/30 hover:border-accent rounded-lg sm:rounded-xl text-xs font-bold transition-all duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span class="hidden sm:inline">Pesan</span>
                </span>
            </div>
        </div>
    </Link>
</template>

<style scoped>
.menu-card {
    transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.35s ease, border-color 0.35s ease;
}
</style>
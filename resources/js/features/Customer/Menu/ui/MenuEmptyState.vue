<script setup>
import { Link } from '@inertiajs/vue3'
</script>
<template>
    <div class="empty-state text-center py-24 bg-surface border border-border-theme rounded-3xl shadow-sm mb-12">
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
</template>

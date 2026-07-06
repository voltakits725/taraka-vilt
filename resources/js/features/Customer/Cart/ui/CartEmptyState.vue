<script setup>
import { Link } from '@inertiajs/vue3'
</script>
<template>
    <div class="text-center py-20 bg-surface border border-border-theme rounded-3xl shadow-sm">
        <div class="w-20 h-20 mx-auto bg-surface-hover rounded-full flex items-center justify-center mb-6 text-text-muted">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
        <p class="text-text-main font-black text-xl mb-3">Keranjang lo masih kosong nih.</p>
        <p class="text-text-muted mb-6">Yuk eksplorasi menu dan temukan sajian favorit lo.</p>
        <Link href="/menu" class="inline-flex items-center gap-2 px-8 py-3.5 bg-accent text-white rounded-full font-bold hover:opacity-90 hover:-translate-y-0.5 transition-all shadow-lg shadow-accent/30">
            Lihat Katalog Menu
        </Link>
    </div>
</template>

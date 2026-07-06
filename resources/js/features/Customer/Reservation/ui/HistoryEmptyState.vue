<script setup>
import { Link } from '@inertiajs/vue3'
</script>
<template>
    <div class="text-center py-20 bg-surface rounded-3xl border border-black/5">
        <div class="w-20 h-20 bg-black/5 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-text-muted">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
            </svg>
        </div>
        <h3 class="font-bold text-lg mb-2">Belum Ada Reservasi</h3>
        <p class="text-text-muted mb-6">Kamu belum pernah memesan meja di Cafe Taraka.</p>
        <Link href="/reservasi" class="inline-block px-6 py-3 bg-accent text-white rounded-full font-bold shadow-lg shadow-accent/30 hover:opacity-90 transition-all text-sm">
            Pesan Meja Sekarang
        </Link>
    </div>
</template>

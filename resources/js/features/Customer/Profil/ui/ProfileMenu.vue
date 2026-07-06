<script setup>
import { Link } from '@inertiajs/vue3'
</script>
<template>
    <div class="bg-surface border border-border-theme rounded-3xl shadow-sm overflow-hidden mb-8">
        <div class="p-6 border-b border-border-theme">
            <h3 class="text-lg font-black text-text-main">Pengaturan Akun</h3>
        </div>
        <div class="divide-y divide-border-theme">
            <Link href="/riwayat-pesanan" class="flex items-center gap-4 p-6 hover:bg-surface-hover transition-colors group">
                <div class="w-12 h-12 rounded-2xl bg-accent/10 text-accent flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-text-main">Riwayat Pesanan</h4>
                    <p class="text-sm text-text-muted mt-0.5">Pantau pesanan yang sedang berjalan atau riwayat belanja.</p>
                </div>
                <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </Link>
            
            <Link href="/riwayat-reservasi" class="flex items-center gap-4 p-6 hover:bg-surface-hover transition-colors group">
                <div class="w-12 h-12 rounded-2xl bg-accent/10 text-accent flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-text-main">Riwayat Reservasi</h4>
                    <p class="text-sm text-text-muted mt-0.5">Lihat status booking meja kamu sebelumnya.</p>
                </div>
                <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </Link>
            
            <Link href="/theme" class="flex items-center gap-4 p-6 hover:bg-surface-hover transition-colors group">
                <div class="w-12 h-12 rounded-2xl bg-accent/10 text-accent flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-text-main">Tampilan (Tema)</h4>
                    <p class="text-sm text-text-muted mt-0.5">Ganti suasana warna Cafe Taraka sesuai selera.</p>
                </div>
                <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </Link>
        </div>
    </div>
</template>

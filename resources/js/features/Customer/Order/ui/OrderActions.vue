<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    order: Object,
    isPaying: Boolean
})
defineEmits(['pay'])
</script>
<template>
    <div class="flex flex-col gap-3">
        <button v-if="order.payment_status === 'unpaid'" @click="$emit('pay')" :disabled="isPaying" class="w-full py-4 bg-accent text-white rounded-full font-bold shadow-lg shadow-accent/30 hover:opacity-90 transition-all text-lg">
            {{ isPaying ? 'Membuka Pembayaran...' : 'Lanjutkan Pembayaran' }}
        </button>
        
        <div v-if="order.payment_status === 'paid'" class="flex gap-3">
            <a :href="`/pesanan/${order.id}/bill`" target="_blank" class="flex-1 py-4 bg-emerald-500 text-white rounded-2xl font-bold shadow-lg shadow-emerald-500/30 hover:bg-emerald-600 transition-all text-sm md:text-base text-center flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                PDF
            </a>
            
            <a :href="`/pesanan/${order.id}/bill?format=image`" target="_blank" class="flex-1 py-4 bg-blue-500 text-white rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:bg-blue-600 transition-all text-sm md:text-base text-center flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Gambar
            </a>
        </div>

        <Link href="/menu" class="w-full py-4 bg-surface border border-border-theme text-text-main rounded-full font-bold hover:bg-surface-hover transition-all text-lg text-center mt-2">
            Kembali ke Menu
        </Link>
    </div>
</template>

<script setup>
defineProps({
    subTotal: Number,
    taxAmount: Number,
    grandTotal: Number,
    isProcessing: Boolean,
    cartItemsLength: Number
})
defineEmits(['checkout'])
</script>
<template>
    <div class="bg-surface border border-border-theme p-6 md:p-8 rounded-3xl shadow-sm">
        <h3 class="text-xl font-black text-text-main mb-6">Ringkasan</h3>
        
        <div class="space-y-4 mb-6 text-sm font-bold text-text-muted border-b border-border-theme pb-6">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span class="text-text-main transition-all duration-300">Rp {{ subTotal.toLocaleString('id-ID') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Pajak (10%)</span>
                <span class="text-text-main transition-all duration-300">Rp {{ taxAmount.toLocaleString('id-ID') }}</span>
            </div>
        </div>

        <div class="flex justify-between items-center mb-8">
            <span class="text-lg font-black text-text-main">Total Bayar</span>
            <span class="text-2xl font-black text-accent transition-all duration-300">Rp {{ grandTotal.toLocaleString('id-ID') }}</span>
        </div>

        <button @click="$emit('checkout')" :disabled="cartItemsLength === 0 || isProcessing" 
            class="w-full py-4 md:py-5 bg-accent text-white rounded-full font-bold shadow-xl shadow-accent/30 hover:opacity-90 hover:-translate-y-0.5 transition-all text-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none flex items-center justify-center gap-2">
            <span v-if="isProcessing" class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            </span>
            <span v-else class="flex items-center gap-2">
                Lanjut Bayar
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </span>
        </button>
    </div>
</template>

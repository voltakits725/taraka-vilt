<script setup>
defineProps({
    order: Object
})
</script>
<template>
    <div class="text-center mb-10">
        <div v-if="order.payment_status === 'paid'" class="w-20 h-20 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-emerald-50">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div v-else-if="order.payment_status === 'unpaid'" class="w-20 h-20 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-amber-50">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div v-else class="w-20 h-20 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-red-50">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>

        <h2 class="text-3xl font-black text-text-main">
            {{ order.payment_status === 'paid' ? 'Pembayaran Berhasil!' : 'Status Pesanan' }}
        </h2>
        <p class="text-text-muted mt-2">Order ID: <span class="font-bold text-text-main">{{ order.midtrans_order_id }}</span></p>
    </div>
</template>

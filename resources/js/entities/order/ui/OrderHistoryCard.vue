<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    order: Object
})

const getOrderStatusColor = (status) => {
    const map = {
        'pending': 'bg-amber-100 text-amber-700',
        'processing': 'bg-blue-100 text-blue-700',
        'ready': 'bg-emerald-100 text-emerald-700 animate-pulse',
        'completed': 'bg-gray-100 text-gray-700',
    }
    return map[status] || 'bg-gray-100 text-gray-700'
}

const getOrderStatusText = (status) => {
    const map = {
        'pending': 'Pending',
        'processing': 'Diproses',
        'ready': 'Siap Diambil',
        'completed': 'Selesai',
    }
    return map[status] || status
}

const formattedDate = computed(() => {
    const date = new Date(props.order.created_at)
    return date.toLocaleDateString('id-ID', { 
        day: 'numeric', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    })
})

const totalAmount = computed(() => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    }).format(props.order.total_amount)
})
</script>

<template>
    <div class="bg-surface rounded-2xl p-5 mb-4 shadow-sm border border-black/5 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start mb-3">
            <div>
                <span class="text-xs text-text-muted font-medium">{{ formattedDate }}</span>
                <h3 class="font-bold text-lg text-text-primary mt-1">{{ order.midtrans_order_id }}</h3>
            </div>
            <div :class="['px-3 py-1 rounded-full text-xs font-bold', getOrderStatusColor(order.order_status)]">
                {{ getOrderStatusText(order.order_status) }}
            </div>
        </div>

        <div class="flex flex-col gap-2 mt-4">
            <div class="flex justify-between items-center text-sm">
                <span class="text-text-secondary">Meja:</span>
                <span class="font-semibold">{{ order.table_number }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-text-secondary">Status Pembayaran:</span>
                <span class="font-bold" :class="order.payment_status === 'paid' ? 'text-emerald-600' : 'text-red-500'">
                    {{ order.payment_status.toUpperCase() }}
                </span>
            </div>
            <div class="flex justify-between items-center mt-2 pt-2 border-t border-black/5">
                <span class="font-medium text-text-primary">Total:</span>
                <span class="font-bold text-accent">{{ totalAmount }}</span>
            </div>
        </div>

        <div class="mt-4 flex gap-2">
            <Link :href="`/pesanan/${order.id}`" 
                class="w-full text-center px-4 py-2 bg-black/5 text-text-primary rounded-xl font-semibold hover:bg-black/10 transition-colors text-sm">
                Lihat Detail
            </Link>
        </div>
    </div>
</template>

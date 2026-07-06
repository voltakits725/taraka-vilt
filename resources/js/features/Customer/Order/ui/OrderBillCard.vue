<script setup>
defineProps({
    order: Object
})

const getOrderStatus = (status) => {
    const map = {
        'pending': { text: 'Menunggu', color: 'text-amber-500 bg-amber-50 border-amber-200' },
        'processing': { text: 'Diproses', color: 'text-blue-500 bg-blue-50 border-blue-200' },
        'completed': { text: 'Selesai', color: 'text-gray-500 bg-gray-50 border-gray-200' },
    }
    return map[status] || { text: status, color: 'text-gray-500 bg-gray-50 border-gray-200' }
}

const getPaymentStatus = (status) => {
    const map = {
        'unpaid': { text: 'Belum Bayar', color: 'text-red-500 bg-red-50 border-red-200' },
        'paid': { text: 'Lunas', color: 'text-emerald-500 bg-emerald-50 border-emerald-200' },
        'failed': { text: 'Gagal', color: 'text-red-500 bg-red-50 border-red-200' },
        'expired': { text: 'Kadaluarsa', color: 'text-gray-500 bg-gray-50 border-gray-200' },
    }
    return map[status] || { text: status, color: 'text-gray-500 bg-gray-50 border-gray-200' }
}

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
    })
}

const formatPaymentType = (pt) => {
    if (!pt) return '-'
    if (pt.startsWith('bank_transfer_')) return 'VA ' + pt.replace('bank_transfer_', '').toUpperCase()
    if (pt === 'echannel') return 'VA MANDIRI'
    if (['qris', 'gopay', 'shopeepay'].includes(pt)) return pt.toUpperCase()
    if (pt === 'credit_card') return 'KARTU KREDIT'
    return pt.replace('_', ' ').toUpperCase()
}
</script>
<template>
    <div class="bg-surface border border-border-theme rounded-3xl shadow-sm overflow-hidden mb-8">
        <!-- Info Status -->
        <div class="p-6 md:p-8 border-b border-border-theme bg-surface-hover/50">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                <div>
                    <p class="text-xs text-text-muted uppercase tracking-wider font-bold mb-1">Status Pembayaran</p>
                    <span :class="['px-3 py-1 text-sm font-bold rounded-full border', getPaymentStatus(order.payment_status).color]">
                        {{ getPaymentStatus(order.payment_status).text }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-text-muted uppercase tracking-wider font-bold mb-1">Status Pesanan</p>
                    <span :class="['px-3 py-1 text-sm font-bold rounded-full border', getOrderStatus(order.order_status).color]">
                        {{ getOrderStatus(order.order_status).text }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-text-muted uppercase tracking-wider font-bold mb-1">{{ order.order_type === 'takeaway' ? 'Tipe Pesanan' : 'Nomor Meja' }}</p>
                    <p class="text-lg font-black text-text-main">
                        <span v-if="order.order_type === 'takeaway'" class="text-blue-500 text-base">TAKEAWAY</span>
                        <span v-else>{{ order.table_number }}</span>
                    </p>
                </div>
                <div>
                    <p class="text-xs text-text-muted uppercase tracking-wider font-bold mb-1">Total</p>
                    <p class="text-lg font-black text-accent">Rp {{ parseInt(order.total_amount).toLocaleString('id-ID') }}</p>
                </div>
                <div>
                    <p class="text-xs text-text-muted uppercase tracking-wider font-bold mb-1">Metode Bayar</p>
                    <p class="text-lg font-black text-text-main">{{ formatPaymentType(order.payment_type) }}</p>
                </div>
            </div>
        </div>

        <!-- Daftar Item -->
        <div class="p-6 md:p-8">
            <h3 class="font-black text-lg text-text-main mb-4 border-b border-border-theme pb-4">Detail Pesanan</h3>
            <div class="space-y-4">
                <div v-for="item in order.order_items" :key="item.id" class="flex gap-4 items-center">
                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-surface-hover shrink-0">
                        <img v-if="item.menu.image" crossorigin="anonymous" :src="item.menu.image + '&cors=1'" :alt="item.menu.name" class="w-full h-full object-cover">
                        <div v-else class="w-full h-full flex items-center justify-center text-xs text-text-muted font-bold border border-border-theme/50 rounded-xl">No Img</div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold text-text-main">{{ item.menu.name }}</h4>
                            <span class="font-bold text-text-main">Rp {{ parseInt(item.subtotal).toLocaleString('id-ID') }}</span>
                        </div>
                        <p class="text-sm text-text-muted">{{ item.quantity }}x</p>
                        <p v-if="item.notes" class="text-xs text-text-muted italic mt-0.5">"{{ item.notes }}"</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 bg-surface-hover/30 border-t border-border-theme flex justify-between items-center text-sm">
            <span class="text-text-muted font-medium">{{ formatDate(order.created_at) }}</span>
            <span class="text-text-muted font-bold">{{ order.customer_name }}</span>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    orders: Object
})

const getOrderStatusColor = (status) => {
    const map = {
        'pending': 'bg-amber-100 text-amber-700 border-amber-200',
        'processing': 'bg-blue-100 text-blue-700 border-blue-200',
        'completed': 'bg-gray-100 text-gray-700 border-gray-200',
    }
    return map[status] || 'bg-gray-100 text-gray-700 border-gray-200'
}

const getPaymentStatusColor = (status) => {
    const map = {
        'unpaid': 'bg-red-100 text-red-700 border-red-200',
        'paid': 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'failed': 'bg-red-100 text-red-700 border-red-200',
        'expired': 'bg-gray-100 text-gray-700 border-gray-200',
    }
    return map[status] || 'bg-gray-100 text-gray-700 border-gray-200'
}

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' - ' + date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })
}

const formatPaymentType = (type) => {
    if (!type) return '';
    const labels = {
        'bank_transfer': 'Transfer Bank',
        'bank_transfer_bca': 'Transfer Bank (BCA)',
        'bank_transfer_bni': 'Transfer Bank (BNI)',
        'bank_transfer_bri': 'Transfer Bank (BRI)',
        'bank_transfer_cimb': 'Transfer Bank (CIMB)',
        'bank_transfer_permata': 'Transfer Bank (Permata)',
        'bank_transfer_mandiri': 'Transfer Bank (Mandiri)',
        'qris': 'QRIS',
        'gopay': 'GoPay',
        'ovo': 'OVO',
        'dana': 'DANA',
        'shopeepay': 'ShopeePay',
        'credit_card': 'Kartu Kredit',
        'cstore': 'Convenience Store',
        'echannel': 'Mandiri Bill',
    };
    return labels[type] || type.replace(/_/g, ' ').toUpperCase();
}
</script>

<template>
    <div class="bg-surface border border-border-theme rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-hover text-text-muted text-sm uppercase tracking-wider border-b border-border-theme">
                        <th class="p-4 font-bold">Waktu</th>
                        <th class="p-4 font-bold">Order ID</th>
                        <th class="p-4 font-bold">Customer (Meja)</th>
                        <th class="p-4 font-bold">Total</th>
                        <th class="p-4 font-bold">Status Bayar</th>
                        <th class="p-4 font-bold">Status Pesanan</th>
                        <th class="p-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-theme">
                    <tr v-for="order in orders.data" :key="order.id" class="hover:bg-surface-hover/50 transition-colors">
                        <td class="p-4 text-sm text-text-muted font-medium whitespace-nowrap">
                            {{ formatDate(order.created_at) }}
                        </td>
                        <td class="p-4 font-bold text-text-main whitespace-nowrap">
                            {{ order.midtrans_order_id || `ORD-#${order.id}` }}
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-text-main">{{ order.customer_name }}</div>
                            <div class="text-xs font-bold mt-1" :class="order.order_type === 'dine_in' ? 'text-accent' : 'text-blue-500'">
                                {{ order.order_type === 'dine_in' ? 'DINE-IN' : 'TAKEAWAY' }}
                                <span v-if="order.table_number" class="text-text-muted ml-1">(Meja {{ order.table_number }})</span>
                            </div>
                        </td>
                        <td class="p-4 font-bold text-accent whitespace-nowrap">
                            Rp {{ parseInt(order.total_amount).toLocaleString('id-ID') }}
                        </td>
                        <td class="p-4 whitespace-nowrap">
                            <span :class="['px-2.5 py-1 text-xs font-bold rounded-md border', getPaymentStatusColor(order.payment_status)]">
                                {{ order.payment_status }}
                            </span>
                            <div v-if="order.payment_type" class="text-[10px] text-text-muted uppercase font-bold mt-1">
                                {{ formatPaymentType(order.payment_type) }}
                            </div>
                        </td>
                        <td class="p-4 whitespace-nowrap">
                            <span :class="['px-2.5 py-1 text-xs font-bold rounded-md border', getOrderStatusColor(order.order_status)]">
                                {{ order.order_status }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <Link :href="`/admin/orders/${order.id}`" class="inline-flex items-center justify-center p-2 bg-accent/10 text-accent hover:bg-accent hover:text-white rounded-lg transition-colors font-bold text-sm">
                                Detail
                            </Link>
                        </td>
                    </tr>
                    <tr v-if="orders.data.length === 0">
                        <td colspan="7" class="p-8 text-center text-text-muted font-medium">
                            Belum ada pesanan masuk.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div v-if="orders.links && orders.links.length > 3" class="p-4 border-t border-border-theme flex items-center justify-center gap-1 overflow-x-auto">
            <template v-for="(link, i) in orders.links" :key="i">
                <div v-if="link.url === null" class="px-3 py-1 text-sm text-text-muted border border-border-theme/50 bg-surface-hover/50 rounded-md opacity-50 cursor-not-allowed" v-html="link.label"></div>
                <Link v-else :href="link.url" :class="['px-3 py-1 text-sm border rounded-md font-medium transition-colors', link.active ? 'bg-accent border-accent text-white' : 'border-border-theme bg-surface text-text-main hover:bg-surface-hover']" v-html="link.label" />
            </template>
        </div>
    </div>
</template>

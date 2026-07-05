<script setup>
defineProps({
    order: Object
})

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
    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' - ' + date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
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
    <div class="space-y-6">
        <!-- Info Pelanggan -->
        <div class="bg-surface border border-border-theme rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-black text-text-muted uppercase tracking-wider mb-4">Informasi Pemesan</h3>
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-accent/10 text-accent flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-text-muted font-medium leading-none mb-1">Nama</p>
                        <p class="font-bold text-text-main">{{ order.customer_name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-accent/10 text-accent flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-text-muted font-medium leading-none mb-1">Tipe Pesanan</p>
                        <p class="font-bold text-text-main" :class="order.order_type === 'dine_in' ? 'text-accent' : 'text-blue-500'">
                            {{ order.order_type === 'dine_in' ? 'DINE-IN' : 'TAKEAWAY' }}
                            <span v-if="order.table_number" class="text-text-main ml-1">(Meja {{ order.table_number }})</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-accent/10 text-accent flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-text-muted font-medium leading-none mb-1">Waktu</p>
                        <p class="font-bold text-text-main">{{ formatDate(order.created_at) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Pembayaran -->
        <div class="bg-surface border border-border-theme rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-black text-text-muted uppercase tracking-wider mb-4">Pembayaran</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-border-theme border-dashed">
                    <span class="text-text-muted font-medium">Status</span>
                    <span :class="['px-2 py-0.5 text-xs font-bold rounded-md border', getPaymentStatusColor(order.payment_status)]">
                        {{ order.payment_status.toUpperCase() }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-border-theme border-dashed">
                    <span class="text-text-muted font-medium">Metode</span>
                    <span class="font-bold text-text-main uppercase">{{ order.payment_type ? formatPaymentType(order.payment_type) : '-' }}</span>
                </div>
                <div class="pt-2">
                    <p class="text-sm text-text-muted mb-1">Total Dibayar</p>
                    <p class="text-3xl font-black text-accent">Rp {{ parseInt(order.total_amount).toLocaleString('id-ID') }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

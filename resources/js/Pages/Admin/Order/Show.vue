<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '../../../shared/layouts/AdminLayout.vue'
import Swal from 'sweetalert2'

const props = defineProps({
    order: Object
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

const updateStatus = (newStatus) => {
    router.patch(`/admin/orders/${props.order.id}/status`, { order_status: newStatus }, {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Status berhasil diupdate',
                showConfirmButton: false,
                timer: 2000
            })
        }
    })
}
</script>

<template>
    <Head :title="`Detail Pesanan #${order.id}`" />
    <AdminLayout>
        
        <div class="mb-6">
            <Link href="/admin/orders" class="inline-flex items-center text-sm font-bold text-text-muted hover:text-accent transition-colors mb-4">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Daftar Pesanan
            </Link>
            
            <div class="flex justify-between items-end">
                <div>
                    <h2 class="text-3xl font-extrabold text-text-main leading-tight">
                        Detail <span class="text-accent">Pesanan</span>
                    </h2>
                    <p class="text-text-muted mt-1 font-mono text-sm">{{ order.midtrans_order_id }}</p>
                </div>
                
                <!-- Status Update Dropdown/Buttons -->
                <div class="flex bg-surface border border-border-theme rounded-xl p-1 shadow-sm">
                    <button @click="updateStatus('pending')" :class="['px-4 py-2 text-sm font-bold rounded-lg transition-all', order.order_status === 'pending' ? 'bg-amber-500 text-white shadow-md' : 'text-text-muted hover:bg-surface-hover']">Pending</button>
                    <button @click="updateStatus('processing')" :class="['px-4 py-2 text-sm font-bold rounded-lg transition-all', order.order_status === 'processing' ? 'bg-blue-500 text-white shadow-md' : 'text-text-muted hover:bg-surface-hover']">Diproses</button>
                    <button @click="updateStatus('completed')" :class="['px-4 py-2 text-sm font-bold rounded-lg transition-all', order.order_status === 'completed' ? 'bg-gray-500 text-white shadow-md' : 'text-text-muted hover:bg-surface-hover']">Selesai</button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Utama (Item Pesanan) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-surface border border-border-theme rounded-2xl p-6 shadow-sm">
                    <h3 class="text-lg font-black text-text-main mb-4 border-b border-border-theme pb-4">Daftar Menu</h3>
                    
                    <div class="space-y-4">
                        <div v-for="item in order.order_items" :key="item.id" class="flex gap-4 p-4 border border-border-theme rounded-xl bg-surface-hover/30">
                            <div class="w-20 h-20 rounded-lg overflow-hidden shrink-0 border border-border-theme bg-surface">
                                <img v-if="item.menu.image" :src="item.menu.image" :alt="item.menu.name" class="w-full h-full object-cover">
                                <div v-else class="w-full h-full flex items-center justify-center text-xs text-text-muted font-bold">No Img</div>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-bold text-text-main text-lg">{{ item.menu.name }}</h4>
                                    <span class="font-bold text-accent">Rp {{ parseInt(item.subtotal).toLocaleString('id-ID') }}</span>
                                </div>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-sm font-bold text-text-main bg-surface border border-border-theme px-2 py-0.5 rounded">{{ item.quantity }}x</span>
                                </div>
                                <div v-if="item.notes" class="mt-2 text-sm text-amber-700 bg-amber-50 border border-amber-100 p-2 rounded-lg italic">
                                    "{{ item.notes }}"
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Samping (Info) -->
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
        </div>

    </AdminLayout>
</template>

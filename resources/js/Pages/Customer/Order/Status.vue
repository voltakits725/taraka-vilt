<script setup>
import { onMounted, ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'

const props = defineProps({
    order: Object,
    snapToken: String,
    midtransClientKey: String
})

const isPaying = ref(false)

const payWithMidtrans = () => {
    if (!props.snapToken) {
        alert('Payment token not found!')
        return
    }

    isPaying.value = true
    
    // Gunakan snap window dari CDN
    window.snap.pay(props.snapToken, {
        onSuccess: function (result) {
            console.log('Success', result)
            isPaying.value = false
            router.reload()
        },
        onPending: function (result) {
            console.log('Pending', result)
            isPaying.value = false
            router.reload()
        },
        onError: function (result) {
            console.error('Error', result)
            isPaying.value = false
            router.reload()
        },
        onClose: function () {
            console.log('User closed the popup without finishing the payment')
            isPaying.value = false
            router.reload()
        }
    })
}

// Auto popup Midtrans if order is unpaid
onMounted(() => {
    if (props.order.payment_status === 'unpaid' && props.snapToken) {
        // Delay dikit supaya halamannya render dulu
        setTimeout(() => {
            payWithMidtrans()
        }, 1000)
    }
})

// Format status order
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
    <Head title="Status Pesanan" />
    <CustomerLayout>
        <div class="max-w-3xl mx-auto pb-24">
            
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

            <div ref="billCard" class="bg-surface border border-border-theme rounded-3xl shadow-sm overflow-hidden mb-8">
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
                                <p class="text-sm text-text-muted">{{ item.quantity }}x &bull; {{ item.sugar_level }} sugar</p>
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

            <!-- Tombol Aksi -->
            <div class="flex flex-col gap-3">
                <button v-if="order.payment_status === 'unpaid'" @click="payWithMidtrans" :disabled="isPaying" class="w-full py-4 bg-accent text-white rounded-full font-bold shadow-lg shadow-accent/30 hover:opacity-90 transition-all text-lg">
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

        </div>
    </CustomerLayout>
</template>

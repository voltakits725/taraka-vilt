<script setup>
import { Head, Link } from '@inertiajs/vue3'
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'

defineProps({
    orders: Object
})
</script>

<template>
    <Head title="Riwayat Pesanan - Cafe Taraka" />

    <CustomerLayout>
        <div class="pt-24 pb-28 px-4 md:px-8 max-w-4xl mx-auto min-h-screen">
            <h1 class="text-3xl font-extrabold text-text-primary mb-2">Riwayat Pesanan</h1>
            <p class="text-text-secondary mb-8">Pantau pesanan kamu yang sedang diproses atau yang sudah lalu.</p>

            <div v-if="orders.data.length === 0" class="text-center py-20 bg-surface rounded-3xl border border-black/5">
                <div class="w-20 h-20 bg-black/5 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-text-muted">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-2">Belum Ada Pesanan</h3>
                <p class="text-text-muted mb-6">Kamu belum pernah memesan apapun.</p>
                <Link href="/menu" class="px-6 py-3 bg-accent text-white rounded-full font-bold shadow-lg shadow-accent/30 hover:opacity-90 transition-all">
                    Pesan Sekarang
                </Link>
            </div>

            <div v-else class="bg-surface rounded-2xl shadow-sm border border-black/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-black/5 text-text-secondary text-sm">
                                <th class="py-4 px-6 font-semibold whitespace-nowrap">Order ID</th>
                                <th class="py-4 px-6 font-semibold whitespace-nowrap">Tanggal</th>
                                <th class="py-4 px-6 font-semibold whitespace-nowrap text-center">Tipe / Meja</th>
                                <th class="py-4 px-6 font-semibold whitespace-nowrap">Total</th>
                                <th class="py-4 px-6 font-semibold whitespace-nowrap">Status Pesanan</th>
                                <th class="py-4 px-6 font-semibold whitespace-nowrap">Status Bayar</th>
                                <th class="py-4 px-6 font-semibold whitespace-nowrap text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5 text-sm">
                            <tr v-for="order in orders.data" :key="order.id" class="hover:bg-black/[0.02] transition-colors">
                                <td class="py-4 px-6 font-bold text-text-primary whitespace-nowrap">
                                    {{ order.midtrans_order_id }}
                                </td>
                                <td class="py-4 px-6 text-text-secondary whitespace-nowrap">
                                    {{ new Date(order.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) }}
                                </td>
                                <td class="py-4 px-6 font-semibold text-center">
                                    <span v-if="order.order_type === 'takeaway'" class="text-xs font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-md">TAKEAWAY</span>
                                    <span v-else class="text-text-main">{{ order.table_number }}</span>
                                </td>
                                <td class="py-4 px-6 font-bold text-accent whitespace-nowrap">
                                    {{ new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(order.total_amount) }}
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <span :class="[
                                        'px-3 py-1 rounded-full text-xs font-bold inline-block',
                                        order.order_status === 'pending' ? 'bg-amber-100 text-amber-700' :
                                        order.order_status === 'processing' ? 'bg-blue-100 text-blue-700' :
                                        'bg-gray-100 text-gray-700'
                                    ]">
                                        {{ order.order_status === 'pending' ? 'Pending' : order.order_status === 'processing' ? 'Diproses' : 'Selesai' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <span :class="order.payment_status === 'paid' ? 'text-emerald-600 font-bold' : 'text-red-500 font-bold'">
                                        {{ order.payment_status.toUpperCase() }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <Link :href="`/pesanan/${order.id}`" 
                                        class="inline-block px-4 py-2 bg-black/5 text-text-primary rounded-xl font-semibold hover:bg-black/10 transition-colors text-xs">
                                        Lihat Detail
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination (if more than 10) -->
            <div v-if="orders.links.length > 3" class="mt-8 flex justify-center gap-2">
                <template v-for="(link, p) in orders.links" :key="p">
                    <Link v-if="link.url" :href="link.url" 
                        class="px-4 py-2 rounded-xl font-medium text-sm transition-colors"
                        :class="link.active ? 'bg-accent text-white shadow-md' : 'bg-surface text-text-secondary hover:bg-black/5'"
                        v-html="link.label">
                    </Link>
                    <span v-else class="px-4 py-2 rounded-xl font-medium text-sm text-text-muted bg-surface/50" v-html="link.label"></span>
                </template>
            </div>
        </div>
    </CustomerLayout>
</template>

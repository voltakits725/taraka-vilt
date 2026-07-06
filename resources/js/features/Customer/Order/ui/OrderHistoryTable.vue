<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    orders: Array
})
</script>
<template>
    <div class="bg-surface rounded-2xl shadow-sm border border-black/5 overflow-hidden">
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
                    <tr v-for="order in orders" :key="order.id" class="hover:bg-black/[0.02] transition-colors">
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
</template>

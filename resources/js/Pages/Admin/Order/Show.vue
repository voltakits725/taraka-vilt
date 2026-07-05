<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '../../../shared/layouts/AdminLayout.vue'
import Swal from 'sweetalert2'
import OrderStatusUpdater from '../../../features/Admin/Order/ui/OrderStatusUpdater.vue'
import OrderItemsList from '../../../entities/Admin/Order/ui/OrderItemsList.vue'
import OrderInfo from '../../../entities/Admin/Order/ui/OrderInfo.vue'

const props = defineProps({
    order: Object
})

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
                <OrderStatusUpdater 
                    :status="order.order_status" 
                    @update="updateStatus" 
                />
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Utama (Item Pesanan) -->
            <div class="lg:col-span-2 space-y-6">
                <OrderItemsList :items="order.order_items" />
            </div>

            <!-- Kolom Samping (Info) -->
            <OrderInfo :order="order" />
        </div>

    </AdminLayout>
</template>

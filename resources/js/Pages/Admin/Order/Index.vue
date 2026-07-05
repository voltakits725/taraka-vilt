<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '../../../shared/layouts/AdminLayout.vue'
import OrderTable from '../../../entities/Admin/Order/ui/OrderTable.vue'

defineProps({
    orders: Object
})
</script>

<template>
    <Head title="Pesanan Masuk" />
    <AdminLayout>
        
        <div class="mb-8 border-b border-border-theme pb-6 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-extrabold text-text-main">
                    Pesanan <span class="text-accent">Masuk</span>
                </h2>
                <p class="text-text-muted mt-1">Kelola semua pesanan pelanggan dari aplikasi.</p>
            </div>
        </div>

        <OrderTable :orders="orders" />

    </AdminLayout>
</template>

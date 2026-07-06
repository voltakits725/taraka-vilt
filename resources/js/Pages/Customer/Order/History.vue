<script setup>
import { Head, Link } from '@inertiajs/vue3'
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'
import OrderHistoryEmptyState from '../../../features/Customer/Order/ui/OrderHistoryEmptyState.vue'
import OrderHistoryTable from '../../../features/Customer/Order/ui/OrderHistoryTable.vue'

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

            <OrderHistoryEmptyState v-if="orders.data.length === 0" />
            <OrderHistoryTable v-else :orders="orders.data" />

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

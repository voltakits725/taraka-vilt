<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'
import HistoryEmptyState from '../../../features/Customer/Reservation/ui/HistoryEmptyState.vue'
import HistoryTable from '../../../features/Customer/Reservation/ui/HistoryTable.vue'

import { onMounted } from 'vue'

const props = defineProps({
    reservations: Object,
    midtransClientKey: String
})

onMounted(() => {
    if (props.midtransClientKey && !document.getElementById('midtrans-snap-script')) {
        const script = document.createElement('script')
        script.id = 'midtrans-snap-script'
        script.src = 'https://app.sandbox.midtrans.com/snap/snap.js'
        script.setAttribute('data-client-key', props.midtransClientKey)
        document.head.appendChild(script)
    }
})

const handlePay = (snapToken) => {
    if (!snapToken) return alert('Payment token not found!')
    window.snap.pay(snapToken, {
        onSuccess: () => router.reload(),
        onPending: () => router.reload(),
        onError: () => router.reload(),
        onClose: () => router.reload(),
    })
}
</script>

<template>
    <Head title="Riwayat Reservasi - Cafe Taraka" />

    <CustomerLayout>
        <div class="pt-24 pb-28 px-4 md:px-8 max-w-5xl mx-auto min-h-screen">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-text-primary mb-2">Riwayat Reservasi</h1>
                    <p class="text-text-secondary">Daftar booking meja kamu sejauh ini.</p>
                </div>
                <Link href="/reservasi" class="hidden md:inline-block px-6 py-3 bg-accent text-white rounded-full font-bold shadow-lg shadow-accent/30 hover:opacity-90 transition-all text-sm">
                    Booking Baru
                </Link>
            </div>

            <HistoryEmptyState v-if="reservations.data.length === 0" />

            <HistoryTable v-else :reservations="reservations.data" @pay="handlePay" />

            <!-- Pagination (if more than 10) -->
            <div v-if="reservations.links.length > 3" class="mt-8 flex justify-center gap-2">
                <template v-for="(link, p) in reservations.links" :key="p">
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

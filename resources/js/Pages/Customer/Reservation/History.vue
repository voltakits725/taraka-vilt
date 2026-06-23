<script setup>
import { Head, Link } from '@inertiajs/vue3'
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'

defineProps({
    reservations: Object
})
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

            <div v-if="reservations.data.length === 0" class="text-center py-20 bg-surface rounded-3xl border border-black/5">
                <div class="w-20 h-20 bg-black/5 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-text-muted">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-2">Belum Ada Reservasi</h3>
                <p class="text-text-muted mb-6">Kamu belum pernah memesan meja di Cafe Taraka.</p>
                <Link href="/reservasi" class="inline-block px-6 py-3 bg-accent text-white rounded-full font-bold shadow-lg shadow-accent/30 hover:opacity-90 transition-all text-sm">
                    Pesan Meja Sekarang
                </Link>
            </div>

            <div v-else class="bg-surface rounded-2xl shadow-sm border border-black/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-black/5 text-text-secondary text-sm">
                                <th class="py-4 px-6 font-semibold whitespace-nowrap">Meja</th>
                                <th class="py-4 px-6 font-semibold whitespace-nowrap">Tanggal & Jam</th>
                                <th class="py-4 px-6 font-semibold whitespace-nowrap">Jumlah Orang</th>
                                <th class="py-4 px-6 font-semibold whitespace-nowrap">Status</th>
                                <th class="py-4 px-6 font-semibold whitespace-nowrap">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5 text-sm">
                            <tr v-for="res in reservations.data" :key="res.id" class="hover:bg-black/[0.02] transition-colors">
                                <td class="py-4 px-6 font-black text-text-primary text-xl whitespace-nowrap">
                                    {{ res.table_number }}
                                </td>
                                <td class="py-4 px-6 text-text-secondary whitespace-nowrap">
                                    <div class="font-bold text-text-primary">{{ new Date(res.reservation_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}</div>
                                    <div class="text-xs">{{ res.reservation_time.substring(0, 5) }}</div>
                                </td>
                                <td class="py-4 px-6 font-semibold text-center">
                                    {{ res.guest_count }}
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <span :class="[
                                        'px-3 py-1 rounded-full text-xs font-bold inline-block',
                                        res.status === 'pending' ? 'bg-amber-100 text-amber-700' :
                                        res.status === 'confirmed' ? 'bg-blue-100 text-blue-700' :
                                        res.status === 'completed' ? 'bg-emerald-100 text-emerald-700' :
                                        'bg-red-100 text-red-700'
                                    ]">
                                        {{ res.status.toUpperCase() }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-text-muted text-xs max-w-[200px] truncate">
                                    {{ res.notes || '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

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

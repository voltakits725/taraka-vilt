<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '../../../shared/layouts/AdminLayout.vue'

defineProps({
    reservations: Object
})

const form = useForm({
    status: ''
})

const updateStatus = (reservation, newStatus) => {
    form.status = newStatus
    form.patch(`/admin/reservations/${reservation.id}/status`, {
        preserveScroll: true
    })
}
</script>

<template>
    <Head title="Manajemen Reservasi Meja" />

    <AdminLayout>
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Reservasi Meja</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola request booking meja dari pelanggan.</p>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="py-3 px-6 font-semibold">Pelanggan</th>
                            <th class="py-3 px-6 font-semibold">Meja</th>
                            <th class="py-3 px-6 font-semibold">Tanggal & Jam</th>
                            <th class="py-3 px-6 font-semibold text-center">Pax</th>
                            <th class="py-3 px-6 font-semibold">Catatan</th>
                            <th class="py-3 px-6 font-semibold">Status</th>
                            <th class="py-3 px-6 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="reservations.data.length === 0">
                            <td colspan="7" class="py-10 text-center text-gray-500">
                                Belum ada data reservasi meja.
                            </td>
                        </tr>
                        <tr v-for="res in reservations.data" :key="res.id" class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-900">{{ res.user.name }}</div>
                                <div class="text-gray-500 text-xs">{{ res.user.email }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 text-orange-600 font-bold rounded-lg border border-orange-200">
                                    {{ res.table_number }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-900">{{ new Date(res.reservation_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}</div>
                                <div class="text-gray-500 text-xs">{{ res.reservation_time.substring(0, 5) }} WIB</div>
                            </td>
                            <td class="py-4 px-6 text-center font-semibold">
                                {{ res.guest_count }} org
                            </td>
                            <td class="py-4 px-6 max-w-[200px] truncate">
                                <span v-if="res.notes" class="text-xs text-gray-500 italic" :title="res.notes">"{{ res.notes }}"</span>
                                <span v-else class="text-gray-300">-</span>
                            </td>
                            <td class="py-4 px-6">
                                <span :class="[
                                    'px-2.5 py-1 rounded-full text-xs font-bold inline-block border',
                                    res.status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200' :
                                    res.status === 'confirmed' ? 'bg-blue-50 text-blue-700 border-blue-200' :
                                    res.status === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' :
                                    'bg-red-50 text-red-700 border-red-200'
                                ]">
                                    {{ res.status.toUpperCase() }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div v-if="res.status === 'pending'" class="flex justify-end gap-2">
                                    <button @click="updateStatus(res, 'cancelled')" class="px-3 py-1.5 bg-white border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 rounded-lg text-xs font-bold transition-colors shadow-sm">
                                        Tolak
                                    </button>
                                    <button @click="updateStatus(res, 'confirmed')" class="px-3 py-1.5 bg-blue-600 text-white hover:bg-blue-700 rounded-lg text-xs font-bold transition-colors shadow-sm">
                                        Terima
                                    </button>
                                </div>
                                <div v-else-if="res.status === 'confirmed'" class="flex justify-end">
                                    <button @click="updateStatus(res, 'completed')" class="px-3 py-1.5 bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg text-xs font-bold transition-colors shadow-sm">
                                        Tandai Selesai
                                    </button>
                                </div>
                                <div v-else>
                                    <span class="text-xs text-gray-400 font-medium italic">Tidak ada aksi</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div v-if="reservations.links.length > 3" class="p-4 border-t border-gray-200 bg-gray-50 flex justify-center gap-1">
                <template v-for="(link, p) in reservations.links" :key="p">
                    <Link v-if="link.url" :href="link.url" 
                        class="px-3 py-1 rounded-md text-sm transition-colors border"
                        :class="link.active ? 'bg-orange-500 text-white border-orange-500 font-bold' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-100'"
                        v-html="link.label">
                    </Link>
                    <span v-else class="px-3 py-1 rounded-md text-sm text-gray-400 bg-gray-100 border border-gray-200" v-html="link.label"></span>
                </template>
            </div>
        </div>
    </AdminLayout>
</template>

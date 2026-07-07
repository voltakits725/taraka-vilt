<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    reservations: Object
})

defineEmits(['updateStatus'])
</script>

<template>
    <div class="bg-surface rounded-xl shadow-sm border border-border-theme overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-surface-hover text-text-muted">
                    <tr>
                        <th class="py-3 px-6 font-semibold">Pelanggan</th>
                        <th class="py-3 px-6 font-semibold">Meja</th>
                        <th class="py-3 px-6 font-semibold">Tanggal & Jam</th>
                        <th class="py-3 px-6 font-semibold text-center">Pax</th>
                        <th class="py-3 px-6 font-semibold">Catatan</th>
                        <th class="py-3 px-6 font-semibold">Status Reservasi</th>
                        <th class="py-3 px-6 font-semibold">Pembayaran</th>
                        <th class="py-3 px-6 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-theme">
                    <tr v-if="reservations.data.length === 0">
                        <td colspan="8" class="py-10 text-center text-text-muted">
                            Belum ada data reservasi meja.
                        </td>
                    </tr>
                    <tr v-for="res in reservations.data" :key="res.id" class="hover:bg-surface-hover transition-colors">
                        <td class="py-4 px-6">
                            <div class="font-bold text-text-main">{{ res.user.name }}</div>
                            <div class="text-text-muted text-xs">{{ res.user.email }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 text-orange-600 font-bold rounded-lg border border-orange-200">
                                {{ res.table_number }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-text-main">{{ new Date(res.reservation_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}</div>
                            <div class="text-text-muted text-xs">{{ res.reservation_time.substring(0, 5) }} WIB</div>
                        </td>
                        <td class="py-4 px-6 text-center font-semibold">
                            {{ res.guest_count }} org
                        </td>
                        <td class="py-4 px-6 max-w-[200px] truncate">
                            <span v-if="res.notes" class="text-xs text-text-muted italic" :title="res.notes">"{{ res.notes }}"</span>
                            <span v-else class="text-text-muted/50">-</span>
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
                        <td class="py-4 px-6">
                            <span v-if="res.payment_status === 'unpaid'" class="text-red-600 font-bold bg-red-50 px-2 py-1 rounded-md text-[10px]">BLM DP</span>
                            <span v-else-if="res.payment_status === 'paid'" class="text-emerald-600 font-bold bg-emerald-50 px-2 py-1 rounded-md text-[10px]">LUNAS</span>
                            <span v-else class="text-text-muted font-bold text-[10px]">{{ res.payment_status?.toUpperCase() || '-' }}</span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div v-if="res.status === 'pending'" class="flex justify-end gap-2">
                                <button @click="$emit('updateStatus', res, 'cancelled')" class="px-3 py-1.5 bg-white border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 rounded-lg text-xs font-bold transition-colors shadow-sm">
                                    Tolak
                                </button>
                                <button @click="$emit('updateStatus', res, 'confirmed')" class="px-3 py-1.5 bg-blue-600 text-white hover:bg-blue-700 rounded-lg text-xs font-bold transition-colors shadow-sm">
                                    Terima
                                </button>
                            </div>
                            <div v-else-if="res.status === 'confirmed'" class="flex justify-end">
                                <button @click="$emit('updateStatus', res, 'completed')" class="px-3 py-1.5 bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg text-xs font-bold transition-colors shadow-sm">
                                    Tandai Selesai
                                </button>
                            </div>
                            <div v-else>
                                <span class="text-xs text-text-muted font-medium italic">Tidak ada aksi</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div v-if="reservations.links.length > 3" class="p-4 border-t border-border-theme bg-surface-hover flex justify-center gap-1">
            <template v-for="(link, p) in reservations.links" :key="p">
                <Link v-if="link.url" :href="link.url" 
                    class="px-3 py-1 rounded-md text-sm transition-colors border"
                    :class="link.active ? 'bg-accent text-white border-accent font-bold' : 'bg-surface text-text-main border-border-theme hover:bg-surface-hover'"
                    v-html="link.label">
                </Link>
                <span v-else class="px-3 py-1 rounded-md text-sm text-text-muted bg-surface-hover border border-border-theme" v-html="link.label"></span>
            </template>
        </div>
    </div>
</template>

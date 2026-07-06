<script setup>
defineProps({
    reservations: Array
})
</script>
<template>
    <div class="bg-surface rounded-2xl shadow-sm border border-black/5 overflow-hidden">
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
                    <tr v-for="res in reservations" :key="res.id" class="hover:bg-black/[0.02] transition-colors">
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
</template>

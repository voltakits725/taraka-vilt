<script setup>
defineProps({
    tables: Array
})

defineEmits(['edit', 'delete'])
</script>

<template>
    <div class="bg-surface border border-border-theme rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-text-muted">
                <thead class="text-xs uppercase bg-surface-hover text-text-main">
                    <tr>
                        <th class="px-6 py-4 font-bold border-b border-border-theme">Nomor Meja</th>
                        <th class="px-6 py-4 font-bold border-b border-border-theme">Kapasitas</th>
                        <th class="px-6 py-4 font-bold border-b border-border-theme">Status</th>
                        <th class="px-6 py-4 font-bold border-b border-border-theme text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="table in tables" :key="table.id" class="border-b border-border-theme hover:bg-surface-hover/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-text-main text-base">Meja {{ table.number }}</td>
                        <td class="px-6 py-4">{{ table.capacity }} Orang</td>
                        <td class="px-6 py-4">
                            <span :class="table.status === 'active' ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20'" class="px-3 py-1 rounded-full text-xs font-bold border">
                                {{ table.status === 'active' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <button @click="$emit('edit', table)" class="text-blue-500 font-bold hover:underline transition-colors">Edit</button>
                            <button @click="$emit('delete', table.id)" class="text-red-500 font-bold hover:underline transition-colors">Hapus</button>
                        </td>
                    </tr>
                    <tr v-if="!tables.length">
                        <td colspan="4" class="px-6 py-8 text-center text-text-muted italic">Belum ada data meja.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

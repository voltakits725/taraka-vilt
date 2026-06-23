<!-- resources/js/entities/category/ui/CategoryTable.vue -->
<script setup>
defineProps({
    categories: Array
})

defineEmits(['edit', 'delete'])
</script>

<template>
    <div class="bg-surface rounded-2xl shadow-sm border border-border-theme overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-max">
                <thead>
                    <tr class="bg-surface-hover border-b border-border-theme text-text-muted text-sm uppercase tracking-wider">
                        <th class="py-4 px-6 font-bold w-20">No</th>
                        <th class="py-4 px-6 font-bold">Nama Kategori</th>
                        <th class="py-4 px-6 font-bold">Slug</th>
                        <th class="py-4 px-6 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- State Kosong -->
                    <tr v-if="categories.length === 0">
                        <td colspan="4" class="py-12 text-center text-text-muted italic">Belum ada data kategori.</td>
                    </tr>
                    
                    <!-- Looping Data Kategori -->
                    <tr v-for="(category, index) in categories" :key="category.id" class="border-b border-border-theme hover:bg-surface-hover transition-colors">
                        
                        <!-- Nomor pakai Badge Bulat warna Accent -->
                        <td class="py-4 px-6 text-text-muted font-bold">
                            <div class="w-8 h-8 rounded-full bg-accent text-white flex items-center justify-center font-bold text-sm shadow-md shadow-accent/30">
                                {{ index + 1 }}
                            </div>
                        </td>
                        
                        <!-- Nama Kategori -->
                        <td class="py-4 px-6 font-bold text-text-main text-lg">{{ category.name }}</td>
                        
                        <!-- Slug -->
                        <td class="py-4 px-6">
                            <span class="inline-block px-3 py-1 bg-surface-hover text-text-muted border border-border-theme rounded-lg font-mono text-sm shadow-sm">
                                {{ category.slug }}
                            </span>
                        </td>
                        
                        <!-- Aksi -->
                        <td class="py-4 px-6 flex justify-end gap-2">
                            <button @click="$emit('edit', category)" class="px-3 py-1.5 bg-amber-500/10 text-amber-500 hover:bg-amber-500/20 rounded-lg text-sm font-bold transition-colors">
                                Edit
                            </button>
                            <button @click="$emit('delete', category.id)" class="px-3 py-1.5 bg-red-500/10 text-red-500 hover:bg-red-500/20 rounded-lg text-sm font-bold transition-colors">
                                Hapus
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
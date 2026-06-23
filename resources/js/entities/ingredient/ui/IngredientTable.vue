<!-- resources/js/entities/ingredient/ui/IngredientTable.vue -->
<script setup>
defineProps({
    ingredients: Object
})

defineEmits(['edit', 'delete'])
</script>

<template>
    <div class="bg-surface rounded-2xl shadow-sm border border-border-theme overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-max">
                <thead>
                    <tr class="bg-surface-hover border-b border-border-theme text-text-muted text-sm uppercase tracking-wider">
                        <th class="py-4 px-6 font-bold w-20">No</th>
                        <th class="py-4 px-6 font-bold">Nama Bahan</th>
                        <th class="py-4 px-6 font-bold">Status Alergen</th>
                        <th class="py-4 px-6 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- State Kosong -->
                    <tr v-if="ingredients.data.length === 0">
                        <td colspan="4" class="py-12 text-center text-text-muted italic">Belum ada master data bahan.</td>
                    </tr>
                    
                    <!-- Looping Data Bahan -->
                    <tr v-for="(item, index) in ingredients.data" :key="item.id" class="border-b border-border-theme hover:bg-surface-hover transition-colors">
                        <!-- Nomor pakai bulatan -->
                        <td class="py-4 px-6 text-text-muted font-bold">
                            <div class="w-8 h-8 rounded-full bg-accent text-white flex items-center justify-center font-bold text-sm shadow-md shadow-accent/30">
                                {{ (ingredients.current_page - 1) * ingredients.per_page + index + 1 }}
                            </div>
                        </td>
                        
                        <!-- Nama Bahan -->
                        <td class="py-4 px-6 font-bold text-text-main text-lg">{{ item.name }}</td>
                        
                        <!-- Status Alergen -->
                        <td class="py-4 px-6">
                            <div v-if="item.is_allergen" class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-500/10 text-red-500 rounded-full text-xs font-bold border border-red-500/20">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Ya ({{ item.allergen_type }})
                            </div>
                            <span v-else class="px-3 py-1 bg-surface-hover text-text-muted border border-border-theme rounded-full text-xs font-semibold shadow-sm">Aman</span>
                        </td>
                        
                        <!-- Aksi -->
                        <td class="py-4 px-6 flex justify-end gap-2">
                            <button @click="$emit('edit', item)" class="px-3 py-1.5 bg-amber-500/10 text-amber-500 hover:bg-amber-500/20 rounded-lg text-sm font-bold transition-colors">
                                Edit
                            </button>
                            <button @click="$emit('delete', item.id)" class="px-3 py-1.5 bg-red-500/10 text-red-500 hover:bg-red-500/20 rounded-lg text-sm font-bold transition-colors">
                                Hapus
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
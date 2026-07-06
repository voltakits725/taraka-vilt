<script setup>
defineProps({
    tables: Array,
    isFetching: Boolean,
    bookedTables: Array,
    occupiedTables: Array,
    selectedTable: Number
})

defineEmits(['select-table'])
</script>
<template>
    <div class="relative">
        <div v-if="isFetching" class="absolute inset-0 bg-surface/50 backdrop-blur-sm z-10 flex items-center justify-center rounded-3xl">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-accent"></div>
        </div>

        <div class="grid grid-cols-3 md:grid-cols-5 gap-4 md:gap-6">
            <button 
                v-for="table in tables" 
                :key="table.id" 
                type="button" 
                @click="$emit('select-table', table.number)"
                :disabled="bookedTables.includes(table.number) || occupiedTables.includes(table.number)"
                :class="[
                    'relative p-6 rounded-3xl border-2 transition-all duration-300 text-center group overflow-hidden',
                    occupiedTables.includes(table.number)
                        ? 'bg-orange-500/10 border-orange-500/30 opacity-80 cursor-not-allowed'
                        : bookedTables.includes(table.number)
                            ? 'bg-surface/50 border-border-theme/50 opacity-60 cursor-not-allowed'
                            : selectedTable === table.number
                                ? 'bg-accent/5 border-accent shadow-lg shadow-accent/20 scale-105'
                                : 'bg-surface border-border-theme hover:border-accent/50 hover:-translate-y-1 hover:shadow-xl hover:shadow-accent/10'
                ]"
            >
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-accent/5 rounded-full blur-2xl group-hover:bg-accent/20 transition-colors"></div>
                <span :class="[
                    'block text-3xl font-black mb-2 transition-colors',
                    selectedTable === table.number ? 'text-accent' : 'text-text-primary'
                ]">
                    {{ table.number }}
                </span>
                <span :class="[
                    'block text-[10px] font-bold tracking-widest uppercase transition-colors',
                    occupiedTables.includes(table.number)
                        ? 'text-orange-500'
                        : bookedTables.includes(table.number)
                            ? 'text-red-500/80'
                            : selectedTable === table.number
                                ? 'text-accent'
                                : 'text-text-muted'
                ]">
                    {{ occupiedTables.includes(table.number) ? 'Terpakai' : (bookedTables.includes(table.number) ? 'Dipesan' : 'Tersedia') }}
                </span>
            </button>
        </div>
    </div>
</template>

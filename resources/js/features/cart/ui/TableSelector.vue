<script setup>
import { ref } from 'vue'

const emit = defineEmits(['update:table'])
const selectedTable = ref('')

// Total meja kafe lo
const totalTables = 15

const selectTable = (n) => {
    selectedTable.value = n
    emit('update:table', selectedTable.value)
}
</script>

<template>
    <div class="bg-surface border border-border-theme p-6 rounded-3xl shadow-sm mb-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-full bg-accent/10 text-accent flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-lg font-black text-text-main leading-tight">Lokasi Meja</h3>
                <p class="text-xs text-text-muted mt-0.5">Pilih meja tempat lo duduk sekarang.</p>
            </div>
        </div>

        <div class="grid grid-cols-5 gap-2 md:gap-3">
            <button
                v-for="n in totalTables"
                :key="n"
                @click="selectTable(n)"
                :class="[
                    'py-2.5 rounded-xl font-extrabold text-sm transition-all border-2 flex items-center justify-center',
                    selectedTable === n
                        ? 'bg-accent border-accent text-white shadow-md shadow-accent/30 scale-105'
                        : 'bg-surface border-border-theme text-text-muted hover:border-accent/50 hover:text-text-main'
                ]"
            >
                {{ n < 10 ? '0' + n : n }}
            </button>
        </div>
    </div>
</template>
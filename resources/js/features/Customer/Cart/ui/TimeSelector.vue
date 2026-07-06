<script setup>
const selectedTime = defineModel()
defineProps({
    availableTimes: Array
})
defineEmits(['reset-table'])
</script>
<template>
    <div class="bg-surface border border-border-theme p-6 rounded-3xl shadow-sm mb-6">
        <h3 class="text-lg font-black text-text-main mb-1">Jam Reservasi</h3>
        <p class="text-xs text-text-muted mb-4">Pilih jam untuk mengecek ketersediaan meja.</p>
        <div class="grid grid-cols-4 gap-2">
            <button v-for="time in availableTimes" :key="time"
                @click="selectedTime = time; $emit('reset-table')"
                :class="['py-2 rounded-xl text-sm font-bold border-2 transition-all', selectedTime === time ? 'bg-accent border-accent text-white shadow-md' : 'bg-surface border-border-theme text-text-muted hover:border-accent/50 hover:text-text-main']">
                {{ time }}
            </button>
        </div>
    </div>
</template>

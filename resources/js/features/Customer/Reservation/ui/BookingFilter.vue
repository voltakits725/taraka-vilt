<script setup>
const date = defineModel('date')
const time = defineModel('time')
defineProps({
    today: String,
    timeSlots: Array
})
</script>
<template>
    <div class="bg-surface rounded-3xl p-6 shadow-sm border border-black/5 mb-8 flex flex-col md:flex-row gap-4 items-center justify-center">
        <div class="w-full md:w-auto">
            <label class="block text-xs font-bold uppercase text-text-muted mb-2">Tanggal</label>
            <input type="date" v-model="date" :min="today" class="w-full md:w-48 bg-black/5 border-none rounded-xl px-4 py-3 font-bold text-text-primary focus:ring-accent transition-all cursor-pointer">
        </div>
        
        <div class="w-full md:w-auto">
            <label class="block text-xs font-bold uppercase text-text-muted mb-2">Jam (Buka 15:00)</label>
            <select v-model="time" class="w-full md:w-48 bg-black/5 border-none rounded-xl px-4 py-3 font-bold text-text-primary focus:ring-accent transition-all cursor-pointer">
                <option v-for="t in timeSlots" :key="t" :value="t" class="bg-surface text-text-primary">{{ t }}</option>
            </select>
        </div>
    </div>
</template>

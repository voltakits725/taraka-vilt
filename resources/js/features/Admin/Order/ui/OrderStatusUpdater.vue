<script setup>
defineProps({
    status: String
})
defineEmits(['update'])
</script>
<template>
    <div class="flex bg-surface border border-border-theme rounded-xl p-1 shadow-sm">
        <button @click="$emit('update', 'pending')" :class="['px-4 py-2 text-sm font-bold rounded-lg transition-all', status === 'pending' ? 'bg-amber-500 text-white shadow-md' : 'text-text-muted hover:bg-surface-hover']">Pending</button>
        <button @click="$emit('update', 'processing')" :class="['px-4 py-2 text-sm font-bold rounded-lg transition-all', status === 'processing' ? 'bg-blue-500 text-white shadow-md' : 'text-text-muted hover:bg-surface-hover']">Diproses</button>
        <button @click="$emit('update', 'completed')" :class="['px-4 py-2 text-sm font-bold rounded-lg transition-all', status === 'completed' ? 'bg-gray-500 text-white shadow-md' : 'text-text-muted hover:bg-surface-hover']">Selesai</button>
    </div>
</template>

<script setup>
const orderType = defineModel()
</script>
<template>
    <div class="bg-surface border border-border-theme p-2 rounded-2xl shadow-sm mb-6 flex">
        <button @click="orderType = 'dine_in'" :class="['flex-1 py-3 text-sm font-bold rounded-xl transition-all', orderType === 'dine_in' ? 'bg-accent text-white shadow-md' : 'text-text-muted hover:text-text-main']">Dine In</button>
        <button @click="orderType = 'takeaway'" :class="['flex-1 py-3 text-sm font-bold rounded-xl transition-all', orderType === 'takeaway' ? 'bg-accent text-white shadow-md' : 'text-text-muted hover:text-text-main']">Take Away</button>
    </div>
</template>

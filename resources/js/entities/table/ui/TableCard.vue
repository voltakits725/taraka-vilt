<script setup>
defineProps({
    number: {
        type: Number,
        required: true
    },
    isAvailable: {
        type: Boolean,
        default: true
    },
    isSelected: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <button 
        :disabled="!isAvailable"
        class="relative w-full aspect-square rounded-2xl flex flex-col items-center justify-center transition-all duration-300 border-2"
        :class="[
            isAvailable 
                ? isSelected 
                    ? 'bg-accent/10 border-accent text-accent scale-95' 
                    : 'bg-surface border-black/5 text-text-primary hover:border-accent/50 hover:shadow-md cursor-pointer'
                : 'bg-black/5 border-transparent text-text-muted cursor-not-allowed opacity-60'
        ]"
    >
        <span class="text-3xl font-black mb-1">{{ number }}</span>
        <span class="text-xs font-bold uppercase tracking-wider">
            {{ isAvailable ? 'Tersedia' : 'Dipesan' }}
        </span>
        
        <div v-if="isSelected" class="absolute top-2 right-2 w-4 h-4 bg-accent rounded-full flex items-center justify-center">
            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
        </div>
    </button>
</template>

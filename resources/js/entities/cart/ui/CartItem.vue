<script setup>
defineProps({
    item: Object
})

const emit = defineEmits(['increase', 'decrease', 'remove'])
</script>

<template>
    <div class="flex gap-4 p-4 md:p-5 bg-surface border border-border-theme rounded-3xl shadow-sm relative transition-all duration-300 hover:border-accent/30">
        
        <div class="w-20 h-20 md:w-24 md:h-24 shrink-0 rounded-2xl overflow-hidden bg-surface-hover border border-border-theme/50">
            <img v-if="item.image" :src="item.image" :alt="item.name" class="w-full h-full object-cover">
            <div v-else class="w-full h-full flex items-center justify-center text-[10px] text-text-muted font-bold">No Img</div>
        </div>
        
        <div class="flex flex-col flex-1 justify-center py-1">
            <h4 class="text-lg font-black text-text-main leading-tight mb-1 pr-8">{{ item.name }}</h4>
            <div class="text-sm text-text-muted font-medium mb-3">
                {{ item.sugar }} Sugar
                <span v-if="item.note" class="block italic text-xs mt-1">"{{ item.note }}"</span>
            </div>
            
            <div class="flex items-center justify-between mt-auto">
                <div class="font-extrabold text-accent text-lg">
                    Rp {{ (item.price * item.qty).toLocaleString('id-ID') }}
                </div>
                
                <div class="flex items-center bg-surface-hover border border-border-theme rounded-full p-1 shadow-sm">
                    <button @click="emit('decrease')" class="w-8 h-8 rounded-full bg-surface shadow-sm flex items-center justify-center text-text-main hover:text-accent transition-colors font-bold text-xl">-</button>
                    <span class="w-10 text-center font-black text-text-main text-base">{{ item.qty }}</span>
                    <button @click="emit('increase')" class="w-8 h-8 rounded-full bg-surface shadow-sm flex items-center justify-center text-text-main hover:text-accent transition-colors font-bold text-xl">+</button>
                </div>
            </div>
        </div>

        <button @click="emit('remove')" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-500 bg-surface-hover hover:bg-red-50 rounded-full transition-colors shadow-sm border border-transparent hover:border-red-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
        
    </div>
</template>
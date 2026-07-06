<script setup>
defineProps({
    menu: Object
})
defineEmits(['open-ai'])
</script>
<template>
    <div class="mb-6 pb-6 border-b border-border-theme">
        <div class="inline-flex items-center gap-2 px-3 py-1 bg-accent/10 text-accent rounded-full text-xs font-black uppercase tracking-widest mb-3 border border-accent/20">
            {{ menu.category?.name || 'Spesial' }}
        </div>
        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-text-main mb-3 leading-tight tracking-tight">{{ menu.name }}</h1>
        <div class="text-2xl sm:text-3xl font-extrabold text-text-main drop-shadow-sm">Rp {{ menu.price.toLocaleString('id-ID') }}</div>
        <p v-if="menu.description" class="text-text-muted font-medium text-base leading-relaxed mt-4">{{ menu.description }}</p>
        
        <button @click="$emit('open-ai')" class="mt-5 inline-flex w-full sm:w-auto items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-500/10 to-pink-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20 rounded-xl text-sm font-bold hover:shadow-lg hover:-translate-y-0.5 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            Tanya AI (Bahan & Alergen)
        </button>
    </div>
</template>

<script setup>
defineProps({
    user: Object
})
</script>
<template>
    <div class="bg-surface border border-border-theme p-8 rounded-3xl shadow-sm text-center mb-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-32 bg-accent/10"></div>
        
        <div class="relative">
            <div class="w-32 h-32 bg-surface border-4 border-surface shadow-xl rounded-full mx-auto mb-4 flex items-center justify-center text-accent text-5xl font-black uppercase overflow-hidden">
                <img v-if="user.avatar" :src="user.avatar" class="w-full h-full object-cover">
                <span v-else>{{ user.name.charAt(0) }}</span>
            </div>
            
            <h2 class="text-2xl font-black text-text-main mb-1">{{ user.name }}</h2>
            <p class="text-text-muted mb-4">{{ user.email }}</p>
            
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-accent/10 text-accent rounded-full text-sm font-bold border border-accent/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Customer Member
            </div>
        </div>
    </div>
</template>

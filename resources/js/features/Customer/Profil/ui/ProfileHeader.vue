<script setup>
defineProps({
    user: Object
})
const emit = defineEmits(['edit-profile'])
</script>
<template>
    <div class="bg-surface border border-border-theme p-8 rounded-3xl shadow-sm text-center mb-8 relative overflow-hidden group">
        <div class="absolute top-0 left-0 w-full h-32 bg-accent/10"></div>
        
        <button 
            @click="emit('edit-profile')"
            class="absolute top-4 right-4 z-10 w-10 h-10 bg-surface/80 backdrop-blur border border-border-theme rounded-full flex items-center justify-center text-text-muted hover:text-accent hover:border-accent/30 transition-all shadow-sm opacity-0 group-hover:opacity-100"
            title="Edit Profil"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
        </button>

        <div class="relative">
            <div class="w-32 h-32 bg-surface border-4 border-surface shadow-xl rounded-full mx-auto mb-4 flex items-center justify-center text-accent text-5xl font-black uppercase overflow-hidden">
                <img v-if="user.avatar" :src="user.avatar" class="w-full h-full object-cover">
                <span v-else>{{ user.name.charAt(0) }}</span>
            </div>
            
            <h2 class="text-2xl font-black text-text-main mb-1">{{ user.name }}</h2>
            <p class="text-text-muted mb-1">{{ user.email }}</p>
            <p v-if="user.phone" class="text-text-muted mb-4 font-medium flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                {{ user.phone }}
            </p>
            <p v-else class="text-text-muted mb-4 font-medium text-sm italic">Nomor WA belum diatur</p>
            
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-accent/10 text-accent rounded-full text-sm font-bold border border-accent/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Customer Member
            </div>
        </div>
    </div>
</template>

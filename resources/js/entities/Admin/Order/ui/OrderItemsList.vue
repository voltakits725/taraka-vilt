<script setup>
defineProps({
    items: Array
})
</script>
<template>
    <div class="bg-surface border border-border-theme rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-black text-text-main mb-4 border-b border-border-theme pb-4">Daftar Menu</h3>
        
        <div class="space-y-4">
            <div v-for="item in items" :key="item.id" class="flex gap-4 p-4 border border-border-theme rounded-xl bg-surface-hover/30">
                <div class="w-20 h-20 rounded-lg overflow-hidden shrink-0 border border-border-theme bg-surface">
                    <img v-if="item.menu.image" :src="item.menu.image" :alt="item.menu.name" class="w-full h-full object-cover">
                    <div v-else class="w-full h-full flex items-center justify-center text-xs text-text-muted font-bold">No Img</div>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <h4 class="font-bold text-text-main text-lg">{{ item.menu.name }}</h4>
                        <span class="font-bold text-accent">Rp {{ parseInt(item.subtotal).toLocaleString('id-ID') }}</span>
                    </div>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-sm font-bold text-text-main bg-surface border border-border-theme px-2 py-0.5 rounded">{{ item.quantity }}x</span>
                    </div>
                    <div v-if="item.notes" class="mt-2 text-sm text-amber-700 bg-amber-50 border border-amber-100 p-2 rounded-lg italic">
                        "{{ item.notes }}"
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

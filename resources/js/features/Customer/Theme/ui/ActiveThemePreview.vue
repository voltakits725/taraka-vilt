<script setup>
defineProps({
    currentTheme: Object
})
</script>
<template>
    <div class="mb-8 p-6 rounded-2xl border border-border-theme bg-surface flex items-center gap-5">
        <div class="w-14 h-14 rounded-2xl shadow-lg flex-shrink-0 flex items-center justify-center text-2xl"
             :style="{ backgroundColor: currentTheme.colors.surfaceHover, border: `2px solid ${currentTheme.colors.border}` }">
            {{ currentTheme.emoji || '🎨' }}
        </div>
        <div class="flex-1 min-w-0">
            <div class="text-xs text-text-muted font-bold uppercase tracking-widest mb-1">Tema Aktif</div>
            <div class="text-text-main font-black text-xl">{{ currentTheme.name }}</div>
            <div class="text-text-muted text-sm mt-0.5">{{ currentTheme.description }}</div>
        </div>
        <div class="flex gap-2 flex-shrink-0">
            <div v-for="(color, key) in { accent: currentTheme.colors.accent, surface: currentTheme.colors.surface, base: currentTheme.colors.base }"
                 :key="key"
                 class="w-7 h-7 rounded-full shadow-md ring-2 ring-white/10"
                 :style="{ backgroundColor: color }">
            </div>
        </div>
    </div>
</template>

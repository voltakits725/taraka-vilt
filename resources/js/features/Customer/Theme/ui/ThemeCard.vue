<script setup>
defineProps({
    theme: Object,
    currentTheme: Object
})
defineEmits(['set-theme'])
</script>
<template>
    <button
        @click="$emit('set-theme', theme.id)"
        class="group relative text-left cursor-pointer rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl border-2 outline-none focus:ring-2 focus:ring-offset-2"
        :style="{
            backgroundColor: theme.colors.surface,
            borderColor: currentTheme.id === theme.id ? theme.colors.accent : theme.colors.border,
            boxShadow: currentTheme.id === theme.id ? `0 0 0 1px ${theme.colors.accent}` : 'none',
            '--tw-ring-color': theme.colors.accent,
        }"
    >
        <!-- Preview Bar Top -->
        <div class="h-16 relative overflow-hidden"
             :style="{ backgroundColor: theme.colors.base }">
            <!-- Color Swatches -->
            <div class="absolute bottom-3 left-4 flex gap-2">
                <div class="w-5 h-5 rounded-full shadow-sm" :style="{ backgroundColor: theme.colors.accent }"></div>
                <div class="w-5 h-5 rounded-full shadow-sm ring-1 ring-white/10" :style="{ backgroundColor: theme.colors.surface }"></div>
                <div class="w-5 h-5 rounded-full shadow-sm ring-1 ring-white/10" :style="{ backgroundColor: theme.colors.surfaceHover }"></div>
            </div>

            <!-- Active Badge -->
            <div v-if="currentTheme.id === theme.id"
                 class="absolute top-3 right-3 flex items-center gap-1.5 px-2.5 py-1 rounded-full text-white text-[10px] font-bold uppercase tracking-wider"
                 :style="{ backgroundColor: theme.colors.accent }">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Aktif
            </div>

            <!-- Decorative mini elements -->
            <div class="absolute top-3 left-4 flex gap-1.5">
                <div class="w-16 h-2 rounded-full opacity-20" :style="{ backgroundColor: theme.colors.textMuted }"></div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-5 border-t" :style="{ borderColor: theme.colors.border }">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <div class="text-sm font-black mb-1" :style="{ color: theme.colors.textMain }">
                        {{ theme.emoji }} {{ theme.name }}
                    </div>
                    <p class="text-xs leading-relaxed" :style="{ color: theme.colors.textMuted }">{{ theme.description }}</p>
                </div>

                <!-- Hover Chevron -->
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                     :style="{ color: theme.colors.accent }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </div>
    </button>
</template>

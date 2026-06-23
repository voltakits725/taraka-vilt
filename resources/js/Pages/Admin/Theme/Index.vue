<script setup>
import AdminLayout from '../../../shared/layouts/AdminLayout.vue'
import { useTheme } from '../../../shared/composables/useTheme'

const { themes, currentTheme, setTheme } = useTheme()
</script>

<template>
    <AdminLayout>
        <div class="mb-8 border-b border-border-theme pb-6">
            <h2 class="text-3xl font-extrabold text-text-main mb-2">Pengaturan Tampilan</h2>
            <p class="text-text-muted">Pilih suasana kerja yang paling nyaman buat mata lu bro.</p>
        </div>

        <div class="bg-surface border border-border-theme p-6 md:p-8 rounded-2xl shadow-sm">
            <h3 class="text-lg font-bold text-text-main mb-6">Pilih Tema Dashboard</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="theme in themes" :key="theme.id"
                    @click="setTheme(theme.id)"
                    class="relative cursor-pointer rounded-2xl overflow-hidden transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg border-2"
                    :style="{ 
                        backgroundColor: theme.colors.surface, 
                        borderColor: currentTheme.id === theme.id ? theme.colors.accent : theme.colors.border 
                    }"
                >
                    <div class="p-6 pb-4 flex justify-between items-center border-b border-white/5" :style="{ borderColor: theme.colors.border }">
                        <div class="w-16 h-2 rounded-full" :style="{ backgroundColor: theme.colors.textMuted, opacity: 0.3 }"></div>
                        <div class="w-6 h-6 rounded-full shadow-sm" :style="{ backgroundColor: theme.colors.accent }"></div>
                    </div>
                    
                    <div class="p-6 pt-4">
                        <h4 class="text-xl font-bold mb-1" :style="{ color: theme.colors.textMain }">{{ theme.name }}</h4>
                        <p class="text-xs leading-relaxed" :style="{ color: theme.colors.textMuted }">{{ theme.description }}</p>
                    </div>

                    <div v-if="currentTheme.id === theme.id" 
                         class="absolute bottom-6 right-6 text-white font-bold text-sm bg-black/20 px-3 py-1 rounded-full backdrop-blur-sm"
                         :style="{ color: theme.colors.accent }">
                        Aktif ✓
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
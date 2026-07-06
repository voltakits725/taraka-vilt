<script setup>
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'
import { useTheme } from '../../../shared/composables/useTheme.js'

import ActiveThemePreview from '../../../features/Customer/Theme/ui/ActiveThemePreview.vue'
import ThemeCard from '../../../features/Customer/Theme/ui/ThemeCard.vue'

const { themes, currentTheme, setTheme } = useTheme()
</script>

<template>
    <CustomerLayout>
        <div class="max-w-5xl mx-auto">

            <!-- Header -->
            <div class="mb-10 text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-accent/10 border border-accent/20 text-accent text-xs font-bold uppercase tracking-widest mb-4">
                    🎨 Kostumisasi
                </div>
                <h2 class="text-3xl md:text-4xl font-black text-text-main mb-2">Pengaturan Tampilan</h2>
                <p class="text-text-muted">Sesuaikan suasana warna Cafe Taraka sesuai selera lo.</p>
            </div>

            <ActiveThemePreview :currentTheme="currentTheme" />

            <!-- Theme Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <ThemeCard 
                    v-for="theme in themes" 
                    :key="theme.id" 
                    :theme="theme" 
                    :currentTheme="currentTheme" 
                    @set-theme="setTheme"
                />
            </div>

            <!-- Info Note -->
            <p class="mt-8 text-center text-text-muted text-sm font-medium">
                💡 Tema yang dipilih akan tersimpan otomatis di browser lo.
            </p>

        </div>
    </CustomerLayout>
</template>
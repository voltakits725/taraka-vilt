<!-- resources/js/features/theme/ui/ThemeSelector.vue -->
<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useTheme } from '../../../shared/composables/useTheme'

const { themes, currentTheme, setTheme } = useTheme()
const isOpen = ref(false)

// Biar dropdown nutup kalau diklik di luar kotak
const closeDropdown = (e) => {
    if (!e.target.closest('.theme-selector')) isOpen.value = false
}

onMounted(() => document.addEventListener('click', closeDropdown))
onUnmounted(() => document.removeEventListener('click', closeDropdown))
</script>

<template>
    <div class="relative theme-selector">
        <!-- Tombol Bulat -->
        <button @click="isOpen = !isOpen" 
            class="w-8 h-8 rounded-full border-2 border-white shadow-sm ring-2 ring-slate-100 focus:outline-none transition-transform hover:scale-110"
            :style="{ backgroundColor: currentTheme.hex }"
            title="Ganti Tema Warna">
        </button>

        <!-- Dropdown Menu -->
        <div v-if="isOpen" class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 animate-fade-in">
            <div class="px-4 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">
                Pilih Tema Skin
            </div>
            <button v-for="theme in themes" :key="theme.id" @click="setTheme(theme.id); isOpen = false"
                class="w-full flex items-center gap-3 px-4 py-2 hover:bg-slate-50 transition-colors">
                <span class="w-4 h-4 rounded-full shadow-inner" :style="{ backgroundColor: theme.hex }"></span>
                <span class="text-sm font-semibold text-slate-700" :class="{ 'text-slate-900 font-extrabold': currentTheme.id === theme.id }">
                    {{ theme.name }}
                </span>
                <!-- Checkmark buat tema yang lagi aktif -->
                <svg v-if="currentTheme.id === theme.id" class="w-4 h-4 ml-auto text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </button>
        </div>
    </div>
</template>

<style scoped>
.animate-fade-in { animation: fadeIn 0.15s ease-out; }
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
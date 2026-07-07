<!-- resources/js/shared/layouts/CustomerLayout.vue -->
<script setup>
import { ref, onMounted, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useTheme } from '../composables/useTheme'
import AOS from 'aos'
import 'aos/dist/aos.css'

// Import Widgets yang udah kita pisah
import TopNavbar from '../../widgets/navbar/ui/TopNavbar.vue'

const { initTheme } = useTheme()
const page = usePage()

const isScrolled = ref(false)
const isHome = computed(() => page.url === '/')

onMounted(() => {
    initTheme()
    
    AOS.init({ mirror: true, once: false, duration: 800, offset: 100 })

    window.addEventListener('scroll', () => {
        isScrolled.value = window.scrollY > 50
    })
})
</script>

<template>
    <div class="min-h-screen bg-base font-sans transition-colors duration-500 flex flex-col relative" style="color: var(--text-main)">
        
        <!-- Panggil Widget TopNavbar -->
        <TopNavbar :is-home="isHome" :is-scrolled="isScrolled" />

        <!-- Area Konten Utama -->
        <main :class="['flex-1 w-full mt-20 transition-colors duration-500', isHome ? '' : 'max-w-7xl mx-auto px-6 md:px-12 py-8']">
            <slot />
        </main>
        
        <!-- Footer -->
        <footer class="py-8 border-t border-border-theme mt-auto">
            <div class="flex flex-col items-center gap-2">
                <span class="text-text-main leading-none" style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 1.5rem; font-weight: 600; letter-spacing: 0.05em;">
                    Taraka<span class="text-accent" style="font-weight: 300;">.</span>
                </span>
                <p class="text-text-muted text-xs font-medium">&copy; 2026 Cafe Taraka. All rights reserved.</p>
            </div>
        </footer>
        
    </div>
</template>

<style>
@supports (padding-bottom: env(safe-area-inset-bottom)) {
    .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
}
</style>
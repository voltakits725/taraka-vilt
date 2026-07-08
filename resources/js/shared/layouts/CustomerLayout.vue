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
        
        <footer class="py-12 border-t border-border-theme mt-auto bg-surface-hover/30">
            <div class="max-w-7xl mx-auto px-6 md:px-12 flex flex-col items-center text-center gap-4">
                <span class="text-text-main leading-none" style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 1.75rem; font-weight: 700; letter-spacing: 0.05em;">
                    Taraka<span class="text-accent" style="font-weight: 300;">.</span>
                </span>
                
                <div class="max-w-md space-y-2">
                    <p class="text-text-secondary text-sm leading-relaxed">
                        Jl. Klungkung No.1, RW.Perumnas 3, Bencongan Indah, Kecamatan Kelapa Dua, Kabupaten Tangerang, Banten 15810
                    </p>
                    <a 
                        href="https://www.google.com/maps/place/Kopi+Taraka+Tangerang/@-6.2127735,106.5962449,17z/data=!4m6!3m5!1s0x2e69ff06706616a7:0xff59ce49c87af96f!8m2!3d-6.2127578!4d106.5963394!16s%2Fg%2F11qqcmdnsq?entry=ttu&g_ep=EgoyMDI2MDcwNS4wIKXMDSoASAFQAw%3D%3D" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 text-accent hover:text-accent-hover text-sm font-bold transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Lihat di Google Maps
                    </a>
                </div>

                <div class="w-full h-px bg-border-theme my-4"></div>

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
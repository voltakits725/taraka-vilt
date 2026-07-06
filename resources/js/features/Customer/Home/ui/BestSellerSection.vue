<script setup>
import { Link } from '@inertiajs/vue3'
import MenuCard from '../../../../entities/Customer/Menu/ui/MenuCard.vue'

defineProps({
    previewMenus: Array
})
</script>

<template>
    <section class="py-20 md:py-32 border-t border-border-theme">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-14">
            <div>
                <div data-aos="fade-right"
                     class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-accent/10 border border-accent/20 text-accent text-xs font-bold uppercase tracking-widest mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
                    Best Seller Hari Ini
                </div>
                <h2 data-aos="fade-right" data-aos-delay="80"
                    class="text-4xl md:text-5xl font-black text-text-main leading-tight">
                    Pilihan Favorit
                </h2>
                <p data-aos="fade-right" data-aos-delay="160"
                   class="text-text-muted text-lg font-medium mt-3">
                    Menu andalan yang selalu ludes dipesan tiap harinya.
                </p>
            </div>

            <Link data-aos="fade-left" href="/menu"
                  class="hidden md:inline-flex items-center gap-2 px-6 py-3 rounded-full border-2 border-accent/30 bg-accent/5 text-accent font-bold text-sm hover:bg-accent hover:text-white hover:border-accent transition-all duration-300">
                Eksplor Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </Link>
        </div>

        <!-- Menu Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6">
            <MenuCard v-for="(menu, index) in previewMenus" :key="menu.id" :menu="menu" :style="`transition-delay: ${index * 80}ms`" />
        </div>

        <!-- Mobile CTA -->
        <div class="mt-10 text-center md:hidden">
            <Link href="/menu" class="inline-flex w-full justify-center px-8 py-4 border-2 border-accent/30 text-accent rounded-full font-bold hover:bg-accent hover:text-white transition-all duration-300">
                Lihat Semua Menu →
            </Link>
        </div>
    </section>
</template>

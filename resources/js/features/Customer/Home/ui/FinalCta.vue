<script setup>
import { Link } from '@inertiajs/vue3'
</script>
<template>
    <section class="pb-24 pt-10 border-t border-border-theme">
        <div data-aos="fade-up"
             class="relative rounded-3xl overflow-hidden">
            <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=90"
                 alt="Taraka Cafe CTA"
                 class="absolute inset-0 w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-r from-accent/40 via-accent/20 to-transparent"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8 p-10 md:p-16">
                <div class="text-center md:text-left">
                    <h3 class="text-3xl md:text-4xl font-black text-text-main mb-3">
                        Siap Pesan Sekarang? ☕
                    </h3>
                    <p class="text-text-muted text-lg font-medium">
                        Kunjungi katalog menu kami dan temukan minuman favoritmu.
                    </p>
                </div>

                <div class="flex gap-4 flex-shrink-0">
                    <Link href="/menu"
                          class="px-8 py-4 bg-accent text-white rounded-full font-bold text-lg hover:scale-105 transition-transform duration-300 shadow-xl shadow-accent/30 whitespace-nowrap">
                        Lihat Menu →
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <section class="relative w-full h-screen flex items-end justify-start overflow-hidden bg-black">
        <!-- Background Image dengan Ken Burns -->
        <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=90"
             alt="Cafe Taraka — Premium Atmosphere"
             class="absolute inset-0 w-full h-full object-cover opacity-70 hero-kenburns">

        <!-- Gradient Overlay Multi-layer -->
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/20 to-transparent"></div>

        <!-- Film Grain Texture -->
        <div class="absolute inset-0 noise-overlay opacity-[0.03] pointer-events-none"></div>

        <!-- Konten Hero — Bottom Left aligned -->
        <div class="relative z-10 px-8 md:px-16 pb-24 md:pb-28 max-w-5xl">
            <!-- Badge Label -->
            <div data-aos="fade-right"
                 class="inline-flex items-center gap-2.5 px-5 py-2 mb-8 rounded-full border border-white/20 bg-white/5 backdrop-blur-md text-xs text-white/80 font-semibold uppercase tracking-[0.2em]">
                <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                Now Open · Kopi, Santai & Ceria
            </div>

            <!-- Judul Utama -->
            <h1 data-aos="fade-right" data-aos-delay="80"
                class="text-5xl sm:text-6xl md:text-8xl font-black text-white mb-6 leading-[0.9] tracking-tight hero-title">
                Setiap Tegukan<br>
                <span class="hero-accent-text">Punya Cerita.</span>
            </h1>

            <!-- Deskripsi -->
            <p data-aos="fade-right" data-aos-delay="180"
               class="text-base md:text-xl text-white/60 font-medium mb-10 max-w-xl leading-relaxed">
                Pesan langsung dari meja, bayar dari HP, dan nikmati pengalaman ngafe yang benar-benar cashless & antre-free.
            </p>

            <!-- CTA Buttons -->
            <div data-aos="fade-right" data-aos-delay="260" class="flex flex-wrap gap-4 items-center">
                <Link href="/menu"
                      class="group relative overflow-hidden px-8 py-4 bg-accent text-white rounded-full font-bold text-base md:text-lg shadow-2xl hover:shadow-accent/40 transition-all duration-300 hover:scale-105 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253"/>
                    </svg>
                    Lihat Menu
                    <div class="absolute inset-0 bg-white/10 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-500 skew-x-12"></div>
                </Link>

                <Link href="/ai-chat"
                      class="px-8 py-4 bg-white/10 text-white border border-white/25 backdrop-blur-md rounded-full font-bold text-base md:text-lg hover:bg-white/20 transition-all duration-300 hover:scale-105 flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Tanya Taraka AI
                </Link>
            </div>
        </div>

        <!-- Stats Float Bar -->
        <div data-aos="fade-up" data-aos-delay="400"
             class="absolute bottom-0 right-0 hidden md:flex items-stretch divide-x divide-white/10">
            <div class="px-8 py-6 bg-white/5 backdrop-blur-xl border-t border-l border-white/10 text-center">
                <div class="text-2xl font-black text-white">50+</div>
                <div class="text-xs text-white/50 font-medium mt-1 uppercase tracking-widest">Menu Pilihan</div>
            </div>
            <div class="px-8 py-6 bg-white/5 backdrop-blur-xl border-t border-white/10 text-center">
                <div class="text-2xl font-black text-white">4.9★</div>
                <div class="text-xs text-white/50 font-medium mt-1 uppercase tracking-widest">Rating</div>
            </div>
            <div class="px-8 py-6 bg-white/5 backdrop-blur-xl border-t border-white/10 text-center">
                <div class="text-2xl font-black text-white">100%</div>
                <div class="text-xs text-white/50 font-medium mt-1 uppercase tracking-widest">Cashless</div>
            </div>
        </div>

        <!-- Scroll Hint -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-white/30 md:hidden">
            <span class="text-[10px] uppercase tracking-[0.25em] font-bold">Scroll</span>
            <div class="w-px h-12 bg-gradient-to-b from-white/30 to-transparent scroll-line"></div>
        </div>
    </section>
</template>

<style scoped>
/* Ken Burns Hero */
.hero-kenburns {
    animation: kenburns 20s ease-out forwards;
}

@keyframes kenburns {
    0% { transform: scale(1) translate(0, 0); }
    100% { transform: scale(1.08) translate(-1%, -1%); }
}

/* Hero Accent Text Gradient */
.hero-accent-text {
    background: linear-gradient(135deg, var(--color-accent), color-mix(in srgb, var(--color-accent) 60%, white));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Scroll line animation */
.scroll-line {
    animation: scroll-reveal 2s ease-in-out infinite;
}

@keyframes scroll-reveal {
    0%, 100% { opacity: 0.3; transform: scaleY(1); transform-origin: top; }
    50% { opacity: 0.8; }
}

/* Hero title font rendering */
.hero-title {
    text-rendering: optimizeLegibility;
    -webkit-font-smoothing: antialiased;
}

/* Noise overlay for film grain */
.noise-overlay {
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='1'/%3E%3C/svg%3E");
    background-size: 256px 256px;
}
</style>

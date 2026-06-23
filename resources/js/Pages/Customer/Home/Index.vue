<!-- resources/js/Pages/Customer/Home/Index.vue -->
<script setup>
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    previewMenus: Array
})
</script>

<template>
    <CustomerLayout>

        <!-- ==========================================
             1. HERO SECTION — Full Cinematic
             ========================================== -->
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

        <!-- Wrapper Konten Bawah -->
        <div class="max-w-7xl mx-auto px-6 md:px-12 w-full">

            <!-- ==========================================
                 2. STRIP MARQUEE — Vibes bar
                 ========================================== -->
            <div class="py-6 md:py-10 overflow-hidden border-b border-border-theme">
                <div class="flex gap-12 marquee-track whitespace-nowrap">
                    <template v-for="n in 3" :key="n">
                        <span v-for="item in ['☕ Kopi Premium', '🧋 Minuman Kekinian', '🍰 Dessert Spesial', '🤖 AI Assistant', '💳 Bayar Cashless', '🛋️ Cozy Ambience', '⚡ Antre-Free', '🌿 Bahan Segar']"
                              :key="item + n"
                              class="text-sm font-bold text-text-muted uppercase tracking-widest inline-block">
                            {{ item }}
                        </span>
                    </template>
                </div>
            </div>

            <!-- ==========================================
                 3. CARA PESAN — How it Works
                 ========================================== -->
            <section class="py-20 md:py-32">
                <div class="grid md:grid-cols-2 gap-12 md:gap-20 items-center">

                    <!-- Kiri: Teks -->
                    <div>
                        <div data-aos="fade-right"
                             class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-accent/10 border border-accent/20 text-accent text-xs font-bold uppercase tracking-widest mb-6">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
                            Cara Kerja
                        </div>
                        <h2 data-aos="fade-right" data-aos-delay="80"
                            class="text-4xl md:text-5xl font-black text-text-main leading-tight mb-6">
                            Pesan Semudah <br>
                            <span class="text-accent">Tap Jari.</span>
                        </h2>
                        <p data-aos="fade-right" data-aos-delay="160"
                           class="text-text-muted text-lg leading-relaxed font-medium">
                            Nggak perlu antri, nggak perlu repot. Semua bisa diselesaikan dari tempat duduk lo dalam hitungan detik.
                        </p>
                    </div>

                    <!-- Kanan: Steps -->
                    <div class="space-y-4">
                        <!-- Step 1 -->
                        <div data-aos="fade-left" data-aos-delay="100"
                             class="group flex items-start gap-6 p-6 rounded-2xl bg-surface border border-border-theme hover:border-accent/40 hover:shadow-xl hover:shadow-accent/5 transition-all duration-300">
                            <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-accent/10 border border-accent/20 flex items-center justify-center text-accent group-hover:bg-accent group-hover:text-white group-hover:scale-110 transition-all duration-300">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-xl font-black text-text-main">1. Pilih Menu</h3>
                                    <span class="text-xs text-accent/60 font-bold uppercase tracking-wider">Step 1</span>
                                </div>
                                <p class="text-text-muted text-sm leading-relaxed font-medium">Jelajahi katalog lengkap atau minta saran rekomendasi dari Taraka AI yang pintar.</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div data-aos="fade-left" data-aos-delay="200"
                             class="group flex items-start gap-6 p-6 rounded-2xl bg-surface border border-border-theme hover:border-accent/40 hover:shadow-xl hover:shadow-accent/5 transition-all duration-300">
                            <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-accent/10 border border-accent/20 flex items-center justify-center text-accent group-hover:bg-accent group-hover:text-white group-hover:scale-110 transition-all duration-300">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-xl font-black text-text-main">2. Pesan & Bayar</h3>
                                    <span class="text-xs text-accent/60 font-bold uppercase tracking-wider">Step 2</span>
                                </div>
                                <p class="text-text-muted text-sm leading-relaxed font-medium">Konfirmasi pesanan & selesaikan pembayaran via dompet digital atau m-banking. Aman & instan.</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div data-aos="fade-left" data-aos-delay="300"
                             class="group flex items-start gap-6 p-6 rounded-2xl bg-surface border border-border-theme hover:border-accent/40 hover:shadow-xl hover:shadow-accent/5 transition-all duration-300">
                            <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-accent/10 border border-accent/20 flex items-center justify-center text-accent group-hover:bg-accent group-hover:text-white group-hover:scale-110 transition-all duration-300">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-xl font-black text-text-main">3. Duduk & Nikmati</h3>
                                    <span class="text-xs text-accent/60 font-bold uppercase tracking-wider">Step 3</span>
                                </div>
                                <p class="text-text-muted text-sm leading-relaxed font-medium">Santai di kursi lo. Barista kami yang bakal anterin pesanan langsung ke meja.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==========================================
                 4. MENU ANDALAN — Bestseller Grid
                 ========================================== -->
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
                    <div v-for="(menu, index) in previewMenus" :key="menu.id"
                         data-aos="fade-up" :data-aos-delay="index * 80"
                         class="group bg-surface border border-border-theme rounded-3xl overflow-hidden hover:shadow-2xl hover:shadow-accent/10 hover:-translate-y-2 hover:border-accent/30 transition-all duration-400 flex flex-col cursor-pointer">

                        <!-- Image Wrapper -->
                        <div class="relative overflow-hidden aspect-[4/3] bg-surface-hover">
                            <img v-if="menu.image"
                                 :src="menu.image"
                                 :alt="menu.name"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div v-else
                                 class="w-full h-full flex flex-col items-center justify-center text-text-muted/40">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-xs font-bold">No Image</span>
                            </div>

                            <!-- Category Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="px-3 py-1 rounded-full bg-black/50 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider">
                                    {{ menu.category?.name || 'Spesial' }}
                                </span>
                            </div>

                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-accent/0 group-hover:bg-accent/5 transition-all duration-300"></div>
                        </div>

                        <!-- Info -->
                        <div class="p-5 flex flex-col flex-1">
                            <h4 class="text-lg font-black text-text-main mb-1 leading-snug group-hover:text-accent transition-colors duration-200">{{ menu.name }}</h4>
                            <div class="text-accent font-extrabold text-xl mt-auto mb-4">
                                Rp {{ menu.price.toLocaleString('id-ID') }}
                            </div>

                            <button class="w-full py-3 rounded-xl border-2 border-border-theme text-text-muted font-bold text-sm group-hover:bg-accent group-hover:border-accent group-hover:text-white transition-all duration-300 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile CTA -->
                <div class="mt-10 text-center md:hidden">
                    <Link href="/menu" class="inline-flex w-full justify-center px-8 py-4 border-2 border-accent/30 text-accent rounded-full font-bold hover:bg-accent hover:text-white transition-all duration-300">
                        Lihat Semua Menu →
                    </Link>
                </div>
            </section>

            <!-- ==========================================
                 5. AMBIENT PHOTO BREAK — Full Width Immersive
                 ========================================== -->
            <section class="py-6 md:py-10 border-t border-border-theme">
                <div data-aos="fade-up"
                     class="relative w-full h-64 md:h-96 rounded-3xl overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1559925393-8be0ec4767c8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=90"
                         alt="Suasana Cafe Taraka"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/20 flex items-center">
                        <div class="px-8 md:px-12">
                            <div class="text-accent text-xs font-bold uppercase tracking-widest mb-3">📍 Temukan Kami</div>
                            <h3 class="text-3xl md:text-4xl font-black text-white leading-tight mb-2">
                                Cafe Taraka<br>
                                <span class="text-white/60 text-2xl font-medium">Your Third Place</span>
                            </h3>
                            <p class="text-white/50 text-sm max-w-sm mt-4 leading-relaxed">Tempat ketiga favorit lo antara rumah dan kantor. Duduk santai, kerja produktif, atau sekadar nongkrong.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==========================================
                 6. FITUR TARAKA AI — Smart Banner
                 ========================================== -->
            <section class="py-20 md:py-32 border-t border-border-theme">
                <div data-aos="fade-up"
                     class="relative rounded-[32px] overflow-hidden border border-border-theme">

                    <!-- BG Pattern -->
                    <div class="absolute inset-0 bg-surface"></div>
                    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-accent/8 blur-[100px] pointer-events-none"></div>
                    <div class="absolute -bottom-12 -left-12 w-64 h-64 rounded-full bg-accent/5 blur-[60px] pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-12 p-8 md:p-16">

                        <!-- Kiri: Ilustrasi AI -->
                        <div class="flex-shrink-0 w-full md:w-auto flex justify-center">
                            <div class="relative">
                                <!-- Outer Ring -->
                                <div class="w-56 h-56 md:w-72 md:h-72 rounded-full border border-accent/10 flex items-center justify-center">
                                    <div class="w-44 h-44 md:w-60 md:h-60 rounded-full border border-accent/20 flex items-center justify-center">
                                        <!-- Inner Circle -->
                                        <div class="w-36 h-36 md:w-48 md:h-48 rounded-full bg-accent/10 border-2 border-accent/30 flex items-center justify-center relative">
                                            <!-- Pulse Rings -->
                                            <div class="absolute inset-0 rounded-full border border-accent/40 animate-ping opacity-30"></div>
                                            <div class="absolute inset-2 rounded-full border border-accent/20 animate-ping opacity-20" style="animation-delay: 0.5s"></div>

                                            <!-- AI Icon -->
                                            <svg class="w-16 h-16 md:w-20 md:h-20 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Floating Chat Bubbles -->
                                <div class="absolute -top-4 -right-4 px-3 py-2 bg-surface-hover border border-border-theme rounded-2xl rounded-tr-sm shadow-xl text-xs font-medium text-text-main animate-float">
                                    "Rekomendasiin kopi buat mood gue dong 🤔"
                                </div>
                                <div class="absolute -bottom-4 -left-4 px-3 py-2 bg-accent text-white rounded-2xl rounded-bl-sm shadow-xl text-xs font-medium animate-float-delay">
                                    "Cobain Vanilla Latte ☕ pas banget!"
                                </div>
                            </div>
                        </div>

                        <!-- Kanan: Teks -->
                        <div class="flex-1 space-y-6 text-center md:text-left">
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-accent/10 border border-accent/20 text-accent text-xs font-bold uppercase tracking-widest">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                AI-Powered
                            </div>

                            <h3 class="text-4xl md:text-5xl font-black text-text-main leading-tight">
                                Bingung Mau<br>
                                Pesan Apa? <span class="text-accent">AI Lo Siap.</span>
                            </h3>

                            <p class="text-text-muted text-lg leading-relaxed font-medium max-w-md mx-auto md:mx-0">
                                Cerita ke <strong class="text-accent">Taraka AI</strong> soal mood, preferensi, atau diet lo. Dia bakal kasih rekomendasi menu yang paling cocok — kayak punya barista pribadi.
                            </p>

                            <!-- Feature Pills -->
                            <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                                <span class="px-4 py-2 bg-surface-hover border border-border-theme rounded-full text-sm font-semibold text-text-muted">🎯 Personal Recommendations</span>
                                <span class="px-4 py-2 bg-surface-hover border border-border-theme rounded-full text-sm font-semibold text-text-muted">🌿 Diet Friendly</span>
                                <span class="px-4 py-2 bg-surface-hover border border-border-theme rounded-full text-sm font-semibold text-text-muted">⚡ Instant Response</span>
                            </div>

                            <div class="pt-2 flex justify-center md:justify-start">
                                <Link href="/ai-chat"
                                      class="group inline-flex items-center gap-3 px-8 py-4 bg-accent text-white rounded-full font-bold text-lg hover:scale-105 transition-transform duration-300 shadow-xl shadow-accent/30">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Mulai Chat Sekarang
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==========================================
                 7. TESTIMONIAL STRIP
                 ========================================== -->
            <section class="py-10 md:py-16 border-t border-border-theme">
                <div class="text-center mb-12">
                    <div data-aos="fade-up"
                         class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-accent/10 border border-accent/20 text-accent text-xs font-bold uppercase tracking-widest mb-4">
                        ⭐ Kata Mereka
                    </div>
                    <h2 data-aos="fade-up" data-aos-delay="80"
                        class="text-3xl md:text-4xl font-black text-text-main">
                        Ribuan Pelanggan <span class="text-accent">Suka</span>
                    </h2>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <!-- Testimoni 1 -->
                    <div data-aos="fade-up" data-aos-delay="100"
                         class="p-6 bg-surface border border-border-theme rounded-2xl hover:border-accent/30 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center gap-1 text-amber-400 mb-4 text-lg">★★★★★</div>
                        <p class="text-text-muted text-sm leading-relaxed font-medium mb-6">
                            "Pesan kopi dari meja gampang banget! Nggak perlu antre dan bayarnya bisa lewat QRIS. Mantap banget inovasinya."
                        </p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center text-accent font-black text-sm">R</div>
                            <div>
                                <div class="text-text-main font-bold text-sm">Reza F.</div>
                                <div class="text-text-muted text-xs">Pelanggan Setia</div>
                            </div>
                        </div>
                    </div>

                    <!-- Testimoni 2 -->
                    <div data-aos="fade-up" data-aos-delay="200"
                         class="p-6 bg-surface border border-border-theme rounded-2xl hover:border-accent/30 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center gap-1 text-amber-400 mb-4 text-lg">★★★★★</div>
                        <p class="text-text-muted text-sm leading-relaxed font-medium mb-6">
                            "AI-nya keren banget, direkomendasiin Cold Brew dan pas banget sama mood gue yang butuh caffeine tinggi 😂. Pasti balik lagi!"
                        </p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center text-accent font-black text-sm">S</div>
                            <div>
                                <div class="text-text-main font-bold text-sm">Sinta D.</div>
                                <div class="text-text-muted text-xs">Freelancer</div>
                            </div>
                        </div>
                    </div>

                    <!-- Testimoni 3 -->
                    <div data-aos="fade-up" data-aos-delay="300"
                         class="p-6 bg-surface border border-border-theme rounded-2xl hover:border-accent/30 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center gap-1 text-amber-400 mb-4 text-lg">★★★★★</div>
                        <p class="text-text-muted text-sm leading-relaxed font-medium mb-6">
                            "Suasana cafenya enak banget buat kerja. Ditambah aplikasi pesan sendiri yang ringkas, jadi makin betah lama-lama di sini."
                        </p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center text-accent font-black text-sm">A</div>
                            <div>
                                <div class="text-text-main font-bold text-sm">Ahmad K.</div>
                                <div class="text-text-muted text-xs">Remote Worker</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==========================================
                 8. FINAL CTA BANNER
                 ========================================== -->
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

        </div>
    </CustomerLayout>
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

/* Marquee */
.marquee-track {
    animation: marquee 30s linear infinite;
}

@keyframes marquee {
    from { transform: translateX(0); }
    to { transform: translateX(-33.333%); }
}

/* Floating bubbles */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-8px); }
}

.animate-float {
    animation: float 4s ease-in-out infinite;
}

.animate-float-delay {
    animation: float 4s ease-in-out 1.5s infinite;
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
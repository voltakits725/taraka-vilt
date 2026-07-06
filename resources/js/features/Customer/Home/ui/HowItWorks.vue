<script setup>
</script>

<template>
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
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
<template>
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
</template>
<style scoped>
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
</style>

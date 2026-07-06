<script setup>
defineProps({
    totalMenus: Number,
    totalCategories: Number
})
</script>
<template>
    <div class="relative mb-10 overflow-hidden">
        <div class="hero-bg absolute inset-0 rounded-3xl opacity-60 pointer-events-none"></div>
        <div class="relative z-10 py-10 px-2">
            <div class="flex items-start gap-4">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-accent/10 border border-accent/20 text-accent rounded-full text-xs font-black uppercase tracking-widest mb-4">
                        <span class="w-1.5 h-1.5 bg-accent rounded-full animate-pulse"></span>
                        Katalog Menu
                    </div>
                    <h1 class="text-2xl sm:text-3xl md:text-5xl font-black mb-3 leading-tight tracking-tight" style="color: var(--text-main)">
                        Eksplorasi<br>
                        <span class="text-gradient">Rasa.</span>
                    </h1>
                    <p class="text-text-muted text-base md:text-lg max-w-md leading-relaxed">
                        Tersedia
                        <span class="font-black" style="color: var(--text-main)">{{ totalMenus || 0 }} menu</span>
                        dari
                        <span class="font-black" style="color: var(--text-main)">{{ totalCategories || 0 }} kategori</span>
                        — temukan sajian yang pas buat mood lo hari ini!
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.text-gradient {
    background: linear-gradient(135deg, var(--color-accent, #8b5cf6), var(--color-accent-hover, #7c3aed));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.hero-bg {
    background: radial-gradient(ellipse 80% 80% at 20% 50%, var(--color-accent, #8b5cf6) 0%, transparent 60%),
                radial-gradient(ellipse 60% 60% at 80% 20%, var(--color-accent, #8b5cf6) 0%, transparent 50%);
    opacity: 0.04;
}
</style>

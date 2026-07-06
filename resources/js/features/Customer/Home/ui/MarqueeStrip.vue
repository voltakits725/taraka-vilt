<script setup>
</script>

<template>
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
</template>

<style scoped>
/* Marquee */
.marquee-track {
    animation: marquee 30s linear infinite;
}

@keyframes marquee {
    from { transform: translateX(0); }
    to { transform: translateX(-33.333%); }
}
</style>

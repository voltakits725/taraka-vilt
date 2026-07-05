<script setup>
defineProps({
    metrics: Object
})
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Total Pesanan -->
        <div class="bg-surface rounded-2xl shadow-sm p-6 border-t-4 border-accent relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-accent opacity-10 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10">
                <h3 class="text-sm font-bold text-text-muted uppercase tracking-wider mb-2">Total Pesanan</h3>
                <div class="flex items-end gap-3">
                    <span class="text-5xl font-extrabold text-accent">{{ metrics?.totalOrders || 0 }}</span>
                    <span class="text-sm font-semibold text-text-muted mb-1">Pesanan Sukses</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Pendapatan -->
        <div class="bg-surface rounded-2xl shadow-sm p-6 border border-border-theme transition-colors relative overflow-hidden group hover:shadow-lg">
            <div class="absolute -left-6 -bottom-6 w-24 h-24 bg-text-muted opacity-5 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10">
                <h3 class="text-sm font-bold text-text-muted uppercase tracking-wider mb-2">Pendapatan</h3>
                <div class="flex items-end gap-2">
                    <span class="text-2xl font-bold text-text-main opacity-75 mb-1">Rp</span>
                    <span class="text-4xl md:text-5xl font-extrabold text-text-main">{{ (metrics?.totalRevenue || 0).toLocaleString('id-ID') }}</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Menu Aktif -->
        <div class="rounded-2xl shadow-md p-6 bg-accent text-white relative overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-4 -bottom-4 w-32 h-32 border-4 border-white/10 rounded-full"></div>
            <div class="absolute right-8 bottom-8 w-16 h-16 border-4 border-white/10 rounded-full"></div>
            <div class="relative z-10">
                <h3 class="text-sm font-bold text-white/80 uppercase tracking-wider mb-2">Total Menu yang Tersedia</h3>
                <div class="flex items-end gap-3">
                    <span class="text-5xl font-extrabold">{{ metrics?.activeMenus || 0 }}</span>
                    <span class="text-sm font-semibold text-white/80 mb-1">Menu</span>
                </div>
            </div>
        </div>
    </div>
</template>

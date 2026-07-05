<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { Pie } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement)

const props = defineProps({
    analysis: Object,
    triggerUpdate: Number
})

const getThemeColors = () => {
    const s = getComputedStyle(document.documentElement)
    return {
        bg:     s.getPropertyValue('--bg-surface').trim() || '#ffffff',
        text:   s.getPropertyValue('--text-main').trim() || '#1f2937',
        border: s.getPropertyValue('--border-color').trim() || '#e5e7eb',
    }
}

const pieOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
            labels: { padding: 20, usePointStyle: true }
        },
        tooltip: {
            callbacks: {
                label: function(context) {
                    let label = context.label || '';
                    if (label) label += ': ';
                    if (context.parsed !== null) label += context.parsed + ' terjual';
                    return label;
                }
            }
        }
    }
})

const getPieColors = (count) => {
    const baseColors = ['#f97316', '#eab308', '#3b82f6', '#10b981', '#8b5cf6', '#ec4899', '#14b8a6']
    return baseColors.slice(0, count)
}

const menuPieData = computed(() => {
    return {
        labels: props.analysis?.topMenus.map(m => m.name) || [],
        datasets: [{
            data: props.analysis?.topMenus.map(m => m.total_sold) || [],
            backgroundColor: getPieColors(props.analysis?.topMenus.length || 0),
            borderWidth: 0
        }]
    }
})

const categoryPieData = computed(() => {
    return {
        labels: props.analysis?.topCategories.map(c => c.name) || [],
        datasets: [{
            data: props.analysis?.topCategories.map(c => c.total_sold) || [],
            backgroundColor: getPieColors(props.analysis?.topCategories.length || 0),
            borderWidth: 0
        }]
    }
})

const updatePieColors = () => {
    const colors = getThemeColors()
    pieOptions.value.plugins.legend.labels.color = colors.text
    pieOptions.value.plugins.tooltip.backgroundColor = colors.bg
    pieOptions.value.plugins.tooltip.titleColor = colors.text
    pieOptions.value.plugins.tooltip.bodyColor = colors.text
    pieOptions.value.plugins.tooltip.borderColor = colors.border
    pieOptions.value.plugins.tooltip.borderWidth = 1
}

onMounted(() => {
    updatePieColors()
})

watch(() => props.triggerUpdate, () => {
    updatePieColors()
})
</script>

<template>
    <div>
        <!-- Pie Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Pie Kategori -->
            <div class="bg-surface border border-border-theme rounded-3xl p-6 md:p-8 shadow-sm">
                <h3 class="text-xl font-extrabold text-text-main mb-6">Kategori Menu Terlaris</h3>
                <div class="w-full h-[300px]">
                    <Pie v-if="categoryPieData.labels.length > 0" :data="categoryPieData" :options="pieOptions" />
                    <div v-else class="w-full h-full flex items-center justify-center text-text-muted font-bold">
                        Belum ada data penjualan.
                    </div>
                </div>
            </div>

            <!-- Pie Menu -->
            <div class="bg-surface border border-border-theme rounded-3xl p-6 md:p-8 shadow-sm">
                <h3 class="text-xl font-extrabold text-text-main mb-6">5 Menu Paling Banyak Dibeli</h3>
                <div class="w-full h-[300px]">
                    <Pie v-if="menuPieData.labels.length > 0" :data="menuPieData" :options="pieOptions" />
                    <div v-else class="w-full h-full flex items-center justify-center text-text-muted font-bold">
                        Belum ada data penjualan.
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Table Kategori -->
            <div class="bg-surface border border-border-theme rounded-3xl p-6 md:p-8 shadow-sm overflow-hidden">
                <h3 class="text-xl font-extrabold text-text-main mb-6">Top 5 Kategori</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-border-theme text-text-muted uppercase text-xs tracking-wider">
                                <th class="py-3 px-4 font-bold">Kategori</th>
                                <th class="py-3 px-4 font-bold text-right">Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(cat, index) in analysis?.topCategories" :key="index" class="border-b border-border-theme hover:bg-surface-hover transition-colors">
                                <td class="py-4 px-4 font-bold text-text-main">{{ cat.name }}</td>
                                <td class="py-4 px-4 text-right font-semibold text-accent">{{ cat.total_sold }} porsi</td>
                            </tr>
                            <tr v-if="!analysis?.topCategories?.length">
                                <td colspan="2" class="py-6 text-center text-text-muted font-medium">Tidak ada data.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table Menu -->
            <div class="bg-surface border border-border-theme rounded-3xl p-6 md:p-8 shadow-sm overflow-hidden">
                <h3 class="text-xl font-extrabold text-text-main mb-6">Top 5 Menu</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-border-theme text-text-muted uppercase text-xs tracking-wider">
                                <th class="py-3 px-4 font-bold">Menu</th>
                                <th class="py-3 px-4 font-bold text-right">Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(menu, index) in analysis?.topMenus" :key="index" class="border-b border-border-theme hover:bg-surface-hover transition-colors">
                                <td class="py-4 px-4 font-bold text-text-main">{{ menu.name }}</td>
                                <td class="py-4 px-4 text-right font-semibold text-accent">{{ menu.total_sold }} porsi</td>
                            </tr>
                            <tr v-if="!analysis?.topMenus?.length">
                                <td colspan="2" class="py-6 text-center text-text-muted font-medium">Tidak ada data.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

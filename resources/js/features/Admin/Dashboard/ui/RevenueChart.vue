<script setup>
import { ref, onMounted, watch } from 'vue'
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const props = defineProps({
    chart: Object,
    triggerUpdate: Number
})

const getThemeColors = () => {
    const s = getComputedStyle(document.documentElement)
    return {
        bg:     s.getPropertyValue('--bg-surface').trim() || '#ffffff',
        text:   s.getPropertyValue('--text-main').trim() || '#1f2937',
        muted:  s.getPropertyValue('--text-muted').trim() || '#6b7280',
        accent: s.getPropertyValue('--color-accent').trim() || '#f97316',
        border: s.getPropertyValue('--border-color').trim() || '#e5e7eb',
    }
}

const chartDataObj = ref({
    labels: [],
    datasets: []
})

const chartOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    animation: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            callbacks: {
                label: function(context) {
                    let label = context.dataset.label || '';
                    if (label) label += ': ';
                    if (context.parsed.y !== null) {
                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                    }
                    return label;
                }
            }
        }
    },
    scales: {
        x: { grid: {}, ticks: {} },
        y: {
            beginAtZero: true,
            ticks: {
                callback: function(value) {
                    if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'Jt';
                    if (value >= 1000) return 'Rp ' + (value / 1000) + 'Rb';
                    return 'Rp ' + value;
                }
            }
        }
    }
})

const updateChartColors = () => {
    const colors = getThemeColors()
    
    chartDataObj.value = {
        labels: props.chart?.labels || [],
        datasets: [{
            label: 'Pendapatan',
            backgroundColor: colors.accent,
            borderRadius: 6,
            data: props.chart?.data || []
        }]
    }

    chartOptions.value.scales.y.grid = { color: colors.border }
    chartOptions.value.scales.x.grid = { display: false }
    chartOptions.value.scales.y.ticks.color = colors.muted
    chartOptions.value.scales.x.ticks.color = colors.muted
    chartOptions.value.plugins.tooltip.backgroundColor = colors.bg
    chartOptions.value.plugins.tooltip.titleColor = colors.text
    chartOptions.value.plugins.tooltip.bodyColor = colors.text
    chartOptions.value.plugins.tooltip.borderColor = colors.border
    chartOptions.value.plugins.tooltip.borderWidth = 1
}

onMounted(() => {
    updateChartColors()
})

watch(() => props.chart, () => {
    updateChartColors()
}, { deep: true })

watch(() => props.triggerUpdate, () => {
    updateChartColors()
})
</script>

<template>
    <div class="bg-surface border border-border-theme rounded-3xl p-6 md:p-8 shadow-sm print:shadow-none print:border-none print:p-0">
        <h3 class="text-xl font-extrabold text-text-main mb-6">Grafik Pendapatan</h3>
        <div class="w-full h-[300px] md:h-[400px]">
            <Bar v-if="chartDataObj.labels.length > 0" :data="chartDataObj" :options="chartOptions" />
            <div v-else class="w-full h-full flex items-center justify-center text-text-muted font-bold text-lg">
                Tidak ada data pendapatan pada rentang tanggal ini.
            </div>
        </div>
    </div>
</template>

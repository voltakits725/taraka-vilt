<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '../../shared/layouts/AdminLayout.vue'
import { Bar, Pie, Doughnut } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement)

const props = defineProps({
    metrics: Object,
    chart: Object,
    analysis: Object,
    payment: Object,
    filters: Object
})

const activeTab = ref(1) // 1 = Overview, 2 = Analisis Penjualan

const selectedFilter = ref(props.filters.current)
const customStartDate = ref(props.filters.start_date)
const customEndDate = ref(props.filters.end_date)

const printDashboard = () => {
    window.print()
}

const exportPdfUrl = computed(() => {
    let url = '/admin/dashboard/export?filter=' + selectedFilter.value
    if (selectedFilter.value === 'custom') {
        url += '&start_date=' + customStartDate.value + '&end_date=' + customEndDate.value
    }
    return url
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
        x: {
            grid: {},
            ticks: {}
        },
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
    
    // Gradient or single color
    chartDataObj.value = {
        labels: props.chart.labels,
        datasets: [{
            label: 'Pendapatan',
            backgroundColor: colors.accent,
            borderRadius: 6,
            data: props.chart.data
        }]
    }

    // Update axes colors
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

// Pie Chart Configs
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

// Payment Doughnut data
const statusColors = ['#10b981', '#f97316', '#ef4444']

const paymentStatusChartData = computed(() => {
    const p = props.payment
    return {
        labels: ['Lunas (Paid)', 'Menunggu (Pending)', 'Kadaluarsa/Batal'],
        datasets: [{
            data: [p?.paid ?? 0, p?.pending ?? 0, p?.expired ?? 0],
            backgroundColor: statusColors,
            borderWidth: 0,
            hoverOffset: 8
        }]
    }
})

const paymentMethodChartData = computed(() => {
    const methods = props.payment?.methods ?? []
    const methodColors = ['#3b82f6', '#f97316', '#10b981', '#8b5cf6', '#eab308', '#ec4899']
    return {
        labels: methods.map(m => m.label),
        datasets: [{
            data: methods.map(m => m.total),
            backgroundColor: methodColors.slice(0, methods.length),
            borderWidth: 0,
            hoverOffset: 8
        }]
    }
})

const successRate = computed(() => {
    const p = props.payment
    if (!p?.total) return 0
    return Math.round((p.paid / p.total) * 100)
})

const doughnutOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    cutout: '65%',
    plugins: {
        legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true } },
        tooltip: {
            callbacks: {
                label: (ctx) => ` ${ctx.label}: ${ctx.parsed} transaksi`
            }
        }
    }
})

onMounted(() => {
    updateChartColors()
    updatePieColors()
    
    // Listen for dark mode toggle if implemented
    const observer = new MutationObserver(() => {
        updateChartColors()
        updatePieColors()
    })
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme', 'class'] })
})

const applyFilter = () => {
    let params = { filter: selectedFilter.value }
    if (selectedFilter.value === 'custom') {
        params.start_date = customStartDate.value
        params.end_date = customEndDate.value
    }
    
    router.get('/admin/dashboard', params, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            updateChartColors()
            updatePieColors()
        }
    })
}

watch(selectedFilter, (newVal) => {
    if (newVal !== 'custom') applyFilter()
})
</script>

<template>
    <AdminLayout>
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-border-theme pb-6">
            <div>
                <h2 class="text-3xl font-extrabold text-text-main">
                    Overview <span class="text-accent">Dashboard</span>
                </h2>
                <p class="text-text-muted mt-1">Pantau performa Cafe Taraka.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-end sm:items-center gap-3">
                <div class="flex items-center gap-2">
                    <button @click="printDashboard" class="bg-surface text-text-main border border-border-theme hover:bg-surface-hover px-4 py-2 rounded-xl font-bold transition-all flex items-center gap-2 shadow-sm print:hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Layar
                    </button>
                    <a :href="exportPdfUrl" target="_blank" class="bg-accent text-white hover:opacity-90 px-4 py-2 rounded-xl font-bold transition-all flex items-center gap-2 shadow-sm print:hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh Laporan (PDF)
                    </a>
                </div>
                <div class="flex flex-wrap items-center gap-3 print:hidden mt-2 sm:mt-0">
                    <select v-model="selectedFilter" class="bg-surface text-text-main border border-border-theme rounded-xl px-4 py-2 font-bold focus:ring-2 focus:ring-accent outline-none">
                        <option value="daily">Hari Ini</option>
                        <option value="weekly">7 Hari Terakhir</option>
                        <option value="monthly">30 Hari Terakhir</option>
                        <option value="yearly">1 Tahun Terakhir</option>
                        <option value="custom">Pilih Tanggal</option>
                    </select>
                    
                    <div v-if="selectedFilter === 'custom'" class="flex items-center gap-2">
                        <input type="date" v-model="customStartDate" class="bg-surface text-text-main border border-border-theme rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-accent outline-none">
                        <span class="text-text-muted">-</span>
                        <input type="date" v-model="customEndDate" class="bg-surface text-text-main border border-border-theme rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-accent outline-none">
                        <button @click="applyFilter" class="bg-accent text-white px-4 py-2 rounded-xl font-bold hover:opacity-90 transition-all">Terapkan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex border-b border-border-theme mb-8 overflow-x-auto print:hidden">
            <button @click="activeTab = 1" :class="activeTab === 1 ? 'border-accent text-accent' : 'border-transparent text-text-muted hover:text-text-main hover:border-border-theme'" class="border-b-2 font-bold px-6 py-3 whitespace-nowrap transition-colors">
                Overview Pendapatan
            </button>
            <button @click="activeTab = 2" :class="activeTab === 2 ? 'border-accent text-accent' : 'border-transparent text-text-muted hover:text-text-main hover:border-border-theme'" class="border-b-2 font-bold px-6 py-3 whitespace-nowrap transition-colors">
                Analisis Penjualan
            </button>
            <button @click="activeTab = 3" :class="activeTab === 3 ? 'border-accent text-accent' : 'border-transparent text-text-muted hover:text-text-main hover:border-border-theme'" class="border-b-2 font-bold px-6 py-3 whitespace-nowrap transition-colors">
                Analisis Transaksi
            </button>
        </div>

        <!-- Tab 1: Overview Pendapatan -->
        <div v-show="activeTab === 1">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            <!-- Card 1: Total Pesanan -->
            <div class="bg-surface rounded-2xl shadow-sm p-6 border-t-4 border-accent relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-accent opacity-10 rounded-full group-hover:scale-110 transition-transform"></div>
                <div class="relative z-10">
                    <h3 class="text-sm font-bold text-text-muted uppercase tracking-wider mb-2">Total Pesanan</h3>
                    <div class="flex items-end gap-3">
                        <span class="text-5xl font-extrabold text-accent">{{ metrics.totalOrders }}</span>
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
                        <span class="text-4xl md:text-5xl font-extrabold text-text-main">{{ metrics.totalRevenue.toLocaleString('id-ID') }}</span>
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
                        <span class="text-5xl font-extrabold">{{ metrics.activeMenus }}</span>
                        <span class="text-sm font-semibold text-white/80 mb-1">Menu</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Chart Section -->
        <div class="bg-surface border border-border-theme rounded-3xl p-6 md:p-8 shadow-sm print:shadow-none print:border-none print:p-0">
            <h3 class="text-xl font-extrabold text-text-main mb-6">Grafik Pendapatan</h3>
            <div class="w-full h-[300px] md:h-[400px]">
                <Bar v-if="chartDataObj.labels.length > 0" :data="chartDataObj" :options="chartOptions" />
                <div v-else class="w-full h-full flex items-center justify-center text-text-muted font-bold text-lg">
                    Tidak ada data pendapatan pada rentang tanggal ini.
                </div>
            </div>
        </div>
        </div> <!-- End Tab 1 -->

        <!-- Tab 2: Analisis Penjualan -->
        <div v-show="activeTab === 2">
            
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

        </div> <!-- End Tab 2 -->

        <!-- Tab 3: Analisis Transaksi & Pembayaran -->
        <div v-show="activeTab === 3">
            
            <!-- Summary Metric Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <!-- Total Transaksi -->
                <div class="bg-surface border border-border-theme rounded-2xl p-5 shadow-sm">
                    <p class="text-xs font-bold text-text-muted uppercase tracking-wider mb-2">Total Transaksi</p>
                    <p class="text-4xl font-extrabold text-text-main">{{ payment?.total ?? 0 }}</p>
                </div>
                <!-- Lunas -->
                <div class="bg-surface border border-emerald-500/30 rounded-2xl p-5 shadow-sm">
                    <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider mb-2">Lunas (Paid)</p>
                    <p class="text-4xl font-extrabold text-emerald-500">{{ payment?.paid ?? 0 }}</p>
                </div>
                <!-- Pending -->
                <div class="bg-surface border border-orange-500/30 rounded-2xl p-5 shadow-sm">
                    <p class="text-xs font-bold text-orange-500 uppercase tracking-wider mb-2">Pending</p>
                    <p class="text-4xl font-extrabold text-orange-500">{{ payment?.pending ?? 0 }}</p>
                </div>
                <!-- Kadaluarsa/Batal -->
                <div class="bg-surface border border-red-500/30 rounded-2xl p-5 shadow-sm">
                    <p class="text-xs font-bold text-red-500 uppercase tracking-wider mb-2">Batal/Expired</p>
                    <p class="text-4xl font-extrabold text-red-500">{{ payment?.expired ?? 0 }}</p>
                </div>
            </div>

            <!-- Success Rate & Avg Order Value -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Success Rate -->
                <div class="bg-surface border border-border-theme rounded-3xl p-6 md:p-8 shadow-sm flex flex-col items-center justify-center gap-4">
                    <h3 class="text-xl font-extrabold text-text-main self-start">Tingkat Keberhasilan</h3>
                    <div class="relative w-40 h-40 flex items-center justify-center">
                        <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="42" fill="none" stroke-width="10" class="stroke-border-theme opacity-20"/>
                            <circle cx="50" cy="50" r="42" fill="none" stroke-width="10" stroke="#10b981" stroke-linecap="round"
                                :stroke-dasharray="`${2 * Math.PI * 42}`"
                                :stroke-dashoffset="`${2 * Math.PI * 42 * (1 - successRate / 100)}`"
                                style="transition: stroke-dashoffset 1s ease;"/>
                        </svg>
                        <div class="text-center">
                            <span class="text-4xl font-extrabold text-emerald-500">{{ successRate }}%</span>
                        </div>
                    </div>
                    <p class="text-sm text-text-muted text-center font-medium">Persentase pesanan yang berhasil dibayar dari total transaksi masuk.</p>
                </div>
                <!-- Metode Pembayaran Doughnut -->
                <div class="bg-surface border border-border-theme rounded-3xl p-6 md:p-8 shadow-sm">
                    <h3 class="text-xl font-extrabold text-text-main mb-6">Metode Pembayaran Favorit</h3>
                    <div class="w-full h-[260px]">
                        <Doughnut v-if="paymentMethodChartData.labels.length > 0" :data="paymentMethodChartData" :options="doughnutOptions" />
                        <div v-else class="w-full h-full flex items-center justify-center text-text-muted font-bold">
                            Belum ada data metode pembayaran.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Doughnut Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Status Pembayaran Doughnut -->
                <div class="bg-surface border border-border-theme rounded-3xl p-6 md:p-8 shadow-sm">
                    <h3 class="text-xl font-extrabold text-text-main mb-6">Status Pembayaran</h3>
                    <div class="w-full h-[260px]">
                        <Doughnut :data="paymentStatusChartData" :options="doughnutOptions" />
                    </div>
                </div>


            </div>

        </div> <!-- End Tab 3 -->

    </AdminLayout>
</template>

<style>
@media print {
    /* Reset layout for printing */
    html, body, #app {
        height: auto !important;
        overflow: visible !important;
        background-color: white !important;
    }
    
    /* Target the flex container inside AdminLayout */
    #app > div {
        height: auto !important;
        overflow: visible !important;
        display: block !important;
    }

    /* Target the main content area */
    main {
        height: auto !important;
        overflow: visible !important;
        padding: 0 !important;
        margin: 0 !important;
        background-color: white !important;
    }

    /* Hide sidebar and header */
    aside, header {
        display: none !important;
    }

    /* Hide print-specific elements */
    .print\:hidden {
        display: none !important;
    }

    /* Prevent page breaks inside the chart */
    canvas {
        page-break-inside: avoid;
        max-width: 100% !important;
    }
}
</style>
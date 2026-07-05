<script setup>
import { ref, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '../../shared/layouts/AdminLayout.vue'

import DashboardFilter from '../../features/Admin/Dashboard/ui/DashboardFilter.vue'
import MetricCards from '../../entities/Admin/Dashboard/ui/MetricCards.vue'
import RevenueChart from '../../features/Admin/Dashboard/ui/RevenueChart.vue'
import SalesAnalysis from '../../features/Admin/Dashboard/ui/SalesAnalysis.vue'
import TransactionAnalysis from '../../features/Admin/Dashboard/ui/TransactionAnalysis.vue'

const props = defineProps({
    metrics: Object,
    chart: Object,
    analysis: Object,
    payment: Object,
    filters: Object
})

const activeTab = ref(1) // 1 = Overview, 2 = Analisis Penjualan, 3 = Analisis Transaksi
const themeTrigger = ref(0)

onMounted(() => {
    // Listen for dark mode toggle if implemented to re-render charts
    const observer = new MutationObserver(() => {
        themeTrigger.value++
    })
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme', 'class'] })
})
</script>

<template>
    <Head title="Dashboard" />
    <AdminLayout>
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-border-theme pb-6">
            <div>
                <h2 class="text-3xl font-extrabold text-text-main">
                    Overview <span class="text-accent">Dashboard</span>
                </h2>
                <p class="text-text-muted mt-1">Pantau performa Cafe Taraka.</p>
            </div>
            
            <DashboardFilter :filters="filters" @filter-applied="themeTrigger++" />
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
            <MetricCards :metrics="metrics" />
            <RevenueChart :chart="chart" :triggerUpdate="themeTrigger" />
        </div>

        <!-- Tab 2: Analisis Penjualan -->
        <div v-show="activeTab === 2">
            <SalesAnalysis :analysis="analysis" :triggerUpdate="themeTrigger" />
        </div>

        <!-- Tab 3: Analisis Transaksi & Pembayaran -->
        <div v-show="activeTab === 3">
            <TransactionAnalysis :payment="payment" />
        </div>

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
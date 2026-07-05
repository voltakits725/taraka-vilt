<script setup>
import { ref, computed } from 'vue'
import { Doughnut } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement)

const props = defineProps({
    payment: Object
})

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
</script>

<template>
    <div>
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
    </div>
</template>

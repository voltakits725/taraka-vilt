<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    filters: Object
})

const selectedFilter = ref(props.filters?.current || 'daily')
const customStartDate = ref(props.filters?.start_date || '')
const customEndDate = ref(props.filters?.end_date || '')

const emit = defineEmits(['filter-applied'])

const exportPdfUrl = computed(() => {
    let url = '/admin/dashboard/export?filter=' + selectedFilter.value
    if (selectedFilter.value === 'custom') {
        url += '&start_date=' + customStartDate.value + '&end_date=' + customEndDate.value
    }
    return url
})

const printDashboard = () => {
    window.print()
}

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
            emit('filter-applied')
        }
    })
}

watch(selectedFilter, (newVal) => {
    if (newVal !== 'custom') applyFilter()
})
</script>

<template>
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
</template>

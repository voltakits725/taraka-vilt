<script setup>
import { ref, onMounted, watch } from 'vue'
import { Head } from '@inertiajs/vue3'
import axios from 'axios'
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'
import TableCard from '../../../entities/table/ui/TableCard.vue'
import BookingModal from '../../../features/reservation/ui/BookingModal.vue'

// Setup initial date and time
const props = defineProps({
    tables: {
        type: Array,
        default: () => []
    }
})

const today = new Date().toISOString().split('T')[0]
const date = ref(today)
const time = ref('15:00') // Default buka jam 15:00

// Ambil list jam buka setiap 2 jam (15:00, 17:00, 19:00, 21:00)
const timeSlots = []
for (let i = 15; i <= 21; i += 2) {
    timeSlots.push(`${i}:00`)
}

const bookedTables = ref([])
const occupiedTables = ref([])
const isFetching = ref(false)

const checkAvailability = async () => {
    if (!date.value || !time.value) return
    
    isFetching.value = true
    try {
        const response = await axios.post('/reservasi/check', {
            date: date.value,
            time: time.value
        })
        bookedTables.value = response.data.booked_tables
        occupiedTables.value = response.data.occupied_tables
    } catch (error) {
        console.error('Error checking availability:', error)
    } finally {
        isFetching.value = false
    }
}

// Watch for date/time changes
watch([date, time], () => {
    checkAvailability()
})

onMounted(() => {
    checkAvailability()
})

const isModalOpen = ref(false)
const selectedTable = ref(null)

const openBookingModal = (tableNum) => {
    if (bookedTables.value.includes(tableNum) || occupiedTables.value.includes(tableNum)) return // Cegah klik meja yg dipesan
    
    selectedTable.value = tableNum
    isModalOpen.value = true
}
</script>

<template>
    <Head title="Booking Meja - Cafe Taraka" />

    <CustomerLayout>
        <div class="pt-24 pb-28 px-4 md:px-8 max-w-5xl mx-auto min-h-screen">
            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-black text-text-primary mb-3">Pesan Tempat</h1>
                <p class="text-text-secondary">Pilih tanggal, jam, dan meja yang ingin kamu tempati.</p>
            </div>

            <!-- Filter Tanggal & Jam -->
            <div class="bg-surface rounded-3xl p-6 shadow-sm border border-black/5 mb-8 flex flex-col md:flex-row gap-4 items-center justify-center">
                <div class="w-full md:w-auto">
                    <label class="block text-xs font-bold uppercase text-text-muted mb-2">Tanggal</label>
                    <input type="date" v-model="date" :min="today" class="w-full md:w-48 bg-black/5 border-none rounded-xl px-4 py-3 font-bold text-text-primary focus:ring-accent transition-all cursor-pointer">
                </div>
                
                <div class="w-full md:w-auto">
                    <label class="block text-xs font-bold uppercase text-text-muted mb-2">Jam (Buka 15:00)</label>
                    <select v-model="time" class="w-full md:w-48 bg-black/5 border-none rounded-xl px-4 py-3 font-bold text-text-primary focus:ring-accent transition-all cursor-pointer">
                        <option v-for="t in timeSlots" :key="t" :value="t" class="bg-surface text-text-primary">{{ t }}</option>
                    </select>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="flex justify-center gap-6 mb-8 text-sm font-semibold">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-surface border-2 border-black/10"></div>
                    <span class="text-text-secondary">Tersedia</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-black/10 border-2 border-transparent"></div>
                    <span class="text-text-secondary">Dipesan</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-orange-500/10 border-2 border-orange-500/30"></div>
                    <span class="text-text-secondary">Terpakai</span>
                </div>
            </div>

            <!-- Grid Meja -->
            <div class="relative">
                <div v-if="isFetching" class="absolute inset-0 bg-surface/50 backdrop-blur-sm z-10 flex items-center justify-center rounded-3xl">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-accent"></div>
                </div>

                <div class="grid grid-cols-3 md:grid-cols-5 gap-4 md:gap-6">
                    <button 
                        v-for="table in tables" 
                        :key="table.id" 
                        type="button" 
                        @click="openBookingModal(table.number)"
                        :disabled="bookedTables.includes(table.number) || occupiedTables.includes(table.number)"
                        :class="[
                            'relative p-6 rounded-3xl border-2 transition-all duration-300 text-center group overflow-hidden',
                            occupiedTables.includes(table.number)
                                ? 'bg-orange-500/10 border-orange-500/30 opacity-80 cursor-not-allowed'
                                : bookedTables.includes(table.number)
                                    ? 'bg-surface/50 border-border-theme/50 opacity-60 cursor-not-allowed'
                                    : selectedTable === table.number
                                        ? 'bg-accent/5 border-accent shadow-lg shadow-accent/20 scale-105'
                                        : 'bg-surface border-border-theme hover:border-accent/50 hover:-translate-y-1 hover:shadow-xl hover:shadow-accent/10'
                        ]"
                    >
                        <div class="absolute -right-4 -top-4 w-16 h-16 bg-accent/5 rounded-full blur-2xl group-hover:bg-accent/20 transition-colors"></div>
                        <span :class="[
                            'block text-3xl font-black mb-2 transition-colors',
                            selectedTable === table.number ? 'text-accent' : 'text-text-primary'
                        ]">
                            {{ table.number }}
                        </span>
                        <span :class="[
                            'block text-[10px] font-bold tracking-widest uppercase transition-colors',
                            occupiedTables.includes(table.number)
                                ? 'text-orange-500'
                                : bookedTables.includes(table.number)
                                    ? 'text-red-500/80'
                                    : selectedTable === table.number
                                        ? 'text-accent'
                                        : 'text-text-muted'
                        ]">
                            {{ occupiedTables.includes(table.number) ? 'Terpakai' : (bookedTables.includes(table.number) ? 'Dipesan' : 'Tersedia') }}
                        </span>
                    </button>
                </div>
            </div>

        </div>

        <BookingModal 
            v-if="isModalOpen"
            :selected-table="selectedTable"
            :date="date"
            :time="time"
            @close="isModalOpen = false"
        />
    </CustomerLayout>
</template>

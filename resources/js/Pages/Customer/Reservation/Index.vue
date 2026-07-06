<script setup>
import { ref, onMounted, watch } from 'vue'
import { Head } from '@inertiajs/vue3'
import axios from 'axios'
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'
import BookingFilter from '../../../features/Customer/Reservation/ui/BookingFilter.vue'
import TableLegend from '../../../features/Customer/Reservation/ui/TableLegend.vue'
import BookingTableGrid from '../../../features/Customer/Reservation/ui/BookingTableGrid.vue'
import BookingModal from '../../../features/Customer/Reservation/ui/BookingModal.vue'

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
            <BookingFilter 
                v-model:date="date"
                v-model:time="time"
                :today="today"
                :timeSlots="timeSlots"
            />

            <!-- Keterangan -->
            <TableLegend />

            <!-- Grid Meja -->
            <BookingTableGrid
                :tables="tables"
                :isFetching="isFetching"
                :bookedTables="bookedTables"
                :occupiedTables="occupiedTables"
                :selectedTable="selectedTable"
                @select-table="openBookingModal"
            />

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

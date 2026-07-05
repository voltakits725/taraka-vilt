<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '../../../shared/layouts/AdminLayout.vue'
import ReservationTable from '../../../entities/Admin/Reservation/ui/ReservationTable.vue'

defineProps({
    reservations: Object
})

const form = useForm({
    status: ''
})

const handleUpdateStatus = (reservation, newStatus) => {
    form.status = newStatus
    form.patch(`/admin/reservations/${reservation.id}/status`, {
        preserveScroll: true
    })
}
</script>

<template>
    <Head title="Manajemen Reservasi Meja" />

    <AdminLayout>
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-text-main">Reservasi Meja</h1>
                <p class="text-text-muted text-sm mt-1">Kelola request booking meja dari pelanggan.</p>
            </div>
        </div>

        <!-- Table Card -->
        <ReservationTable :reservations="reservations" @updateStatus="handleUpdateStatus" />
        
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

defineProps({
    reservations: Array
})

const emit = defineEmits(['pay'])

const isRescheduleModalOpen = ref(false)
const selectedRes = ref(null)

const rescheduleForm = useForm({
    new_date: '',
    new_time: ''
})

const openRescheduleModal = (res) => {
    selectedRes.value = res
    rescheduleForm.new_date = res.reservation_date
    rescheduleForm.new_time = res.reservation_time.substring(0, 5)
    rescheduleForm.clearErrors()
    isRescheduleModalOpen.value = true
}

const submitReschedule = () => {
    rescheduleForm.post(`/reservasi/${selectedRes.value.id}/reschedule`, {
        preserveScroll: true,
        onSuccess: () => {
            isRescheduleModalOpen.value = false
        }
    })
}
</script>
<template>
    <div class="bg-surface rounded-2xl shadow-sm border border-black/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-black/5 text-text-secondary text-sm">
                        <th class="py-4 px-6 font-semibold whitespace-nowrap">Meja</th>
                        <th class="py-4 px-6 font-semibold whitespace-nowrap">Tanggal & Jam</th>
                        <th class="py-4 px-6 font-semibold whitespace-nowrap">Status Booking</th>
                        <th class="py-4 px-6 font-semibold whitespace-nowrap">Status Pembayaran</th>
                        <th class="py-4 px-6 font-semibold whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 text-sm">
                    <tr v-for="res in reservations" :key="res.id" class="hover:bg-black/[0.02] transition-colors">
                        <td class="py-4 px-6 font-black text-text-primary text-xl whitespace-nowrap">
                            {{ res.table_number }}
                            <div class="text-xs font-normal text-text-muted mt-1">{{ res.guest_count }} Orang</div>
                        </td>
                        <td class="py-4 px-6 text-text-secondary whitespace-nowrap">
                            <div class="font-bold text-text-primary">{{ new Date(res.reservation_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}</div>
                            <div class="text-xs">{{ res.reservation_time.substring(0, 5) }}</div>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span :class="[
                                'px-3 py-1 rounded-full text-xs font-bold inline-block',
                                res.status === 'pending' ? 'bg-amber-100 text-amber-700' :
                                res.status === 'confirmed' ? 'bg-blue-100 text-blue-700' :
                                res.status === 'completed' ? 'bg-emerald-100 text-emerald-700' :
                                'bg-red-100 text-red-700'
                            ]">
                                {{ res.status.toUpperCase() }}
                            </span>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span v-if="res.payment_status === 'unpaid'" class="text-red-500 font-bold bg-red-50 px-2 py-1 rounded-md text-xs">BELUM BAYAR</span>
                            <span v-else-if="res.payment_status === 'paid'" class="text-emerald-600 font-bold bg-emerald-50 px-2 py-1 rounded-md text-xs">LUNAS</span>
                            <span v-else class="text-text-muted font-bold text-xs">{{ res.payment_status?.toUpperCase() || '-' }}</span>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <button v-if="res.payment_status === 'unpaid' && res.status !== 'cancelled'" @click="$emit('pay', res.snap_token)"
                                class="px-4 py-2 bg-accent text-white text-xs font-bold rounded-xl hover:opacity-90 shadow-sm transition-all mr-2">
                                Bayar (Rp 20.000)
                            </button>
                            
                            <button v-if="res.status === 'confirmed'" @click="openRescheduleModal(res)"
                                class="px-4 py-2 bg-black/5 text-text-primary text-xs font-bold rounded-xl hover:bg-black/10 transition-all">
                                Ubah Jadwal
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Reschedule -->
    <div v-if="isRescheduleModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-surface rounded-3xl p-6 md:p-8 max-w-md w-full shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-text-main">Ubah Jadwal Meja {{ selectedRes?.table_number }}</h3>
                <button @click="isRescheduleModalOpen = false" class="text-text-muted hover:text-text-main">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form @submit.prevent="submitReschedule" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-text-main mb-1">Tanggal Baru</label>
                    <input type="date" v-model="rescheduleForm.new_date" required :min="new Date().toISOString().split('T')[0]"
                        class="w-full rounded-xl border-border-theme bg-surface-hover px-4 py-3 text-text-main focus:ring-2 focus:ring-accent outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-text-main mb-1">Jam Baru</label>
                    <input type="time" v-model="rescheduleForm.new_time" required min="10:00" max="22:00"
                        class="w-full rounded-xl border-border-theme bg-surface-hover px-4 py-3 text-text-main focus:ring-2 focus:ring-accent outline-none">
                    <p class="text-xs text-text-muted mt-1">Jam operasional: 10:00 - 22:00</p>
                </div>

                <div v-if="rescheduleForm.errors.reschedule" class="p-3 bg-red-50 text-red-500 rounded-xl text-sm font-medium border border-red-200">
                    {{ rescheduleForm.errors.reschedule }}
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="isRescheduleModalOpen = false"
                        class="flex-1 px-4 py-3 rounded-xl font-bold text-text-secondary bg-black/5 hover:bg-black/10 transition-colors">
                        Batal
                    </button>
                    <button type="submit" :disabled="rescheduleForm.processing"
                        class="flex-1 px-4 py-3 rounded-xl font-bold text-white bg-accent hover:opacity-90 shadow-md transition-all disabled:opacity-50">
                        {{ rescheduleForm.processing ? 'Memproses...' : 'Simpan Perubahan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

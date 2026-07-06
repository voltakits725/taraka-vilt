<script setup>
import { ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
    selectedTable: Number,
    date: String,
    time: String
})

const emit = defineEmits(['close'])

const form = useForm({
    table_number: props.selectedTable,
    reservation_date: props.date,
    reservation_time: props.time,
    guest_count: 2,
    notes: ''
})

const submit = () => {
    form.post('/reservasi', {
        onSuccess: () => {
            emit('close')
            Swal.fire({
                title: 'Booking Berhasil!',
                text: 'Meja kamu berhasil di-booking. Tunggu konfirmasi dari Admin ya!',
                imageUrl: '/images/tarakav2.png',
                imageHeight: 80,
                confirmButtonColor: '#4ade80',
                confirmButtonText: 'Lanjut'
            }).then(() => {
                window.location.href = '/riwayat-reservasi'
            })
        }
    })
}
</script>

<template>
    <div class="fixed inset-0 z-[100] flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="emit('close')"></div>
        
        <div class="relative bg-surface w-full max-w-md rounded-3xl shadow-2xl p-6 md:p-8 overflow-hidden" data-aos="zoom-in" data-aos-duration="300">
            <h3 class="text-2xl font-black text-text-primary mb-1">Konfirmasi Booking</h3>
            <p class="text-text-secondary text-sm mb-6">Lengkapi detail untuk mengamankan meja kamu.</p>

            <form @submit.prevent="submit" class="space-y-4">
                <div class="bg-black/5 p-4 rounded-2xl mb-4 grid grid-cols-3 gap-2 text-center divide-x divide-black/10">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-text-muted mb-1">Meja</p>
                        <p class="font-black text-lg text-accent">{{ form.table_number }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-text-muted mb-1">Tanggal</p>
                        <p class="font-bold text-sm text-text-primary">{{ new Date(form.reservation_date).toLocaleDateString('id-ID', {day: 'numeric', month: 'short'}) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-text-muted mb-1">Jam</p>
                        <p class="font-bold text-sm text-text-primary">{{ form.reservation_time }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-text-primary mb-2">Jumlah Orang</label>
                    <div class="flex items-center gap-4 bg-surface border border-black/10 rounded-2xl p-2">
                        <button type="button" @click="form.guest_count > 1 && form.guest_count--" class="w-10 h-10 rounded-xl bg-black/5 hover:bg-black/10 flex items-center justify-center font-bold text-lg transition-colors">-</button>
                        <span class="flex-1 text-center font-black text-xl">{{ form.guest_count }}</span>
                        <button type="button" @click="form.guest_count < 10 && form.guest_count++" class="w-10 h-10 rounded-xl bg-black/5 hover:bg-black/10 flex items-center justify-center font-bold text-lg transition-colors">+</button>
                    </div>
                    <div v-if="form.errors.guest_count" class="text-red-500 text-xs mt-1">{{ form.errors.guest_count }}</div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-text-primary mb-2">Catatan (Opsional)</label>
                    <textarea v-model="form.notes" rows="3" class="w-full bg-surface border border-black/10 rounded-2xl p-3 focus:border-accent focus:ring focus:ring-accent/20 transition-all text-sm resize-none" placeholder="Misal: Tolong siapkan baby chair..."></textarea>
                    <div v-if="form.errors.notes" class="text-red-500 text-xs mt-1">{{ form.errors.notes }}</div>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="emit('close')" class="flex-1 py-3 bg-black/5 text-text-primary rounded-xl font-bold hover:bg-black/10 transition-colors">Batal</button>
                    <button type="submit" :disabled="form.processing" class="flex-[2] py-3 bg-accent text-white rounded-xl font-bold shadow-lg shadow-accent/30 hover:opacity-90 transition-opacity disabled:opacity-50">
                        {{ form.processing ? 'Memproses...' : 'Konfirmasi Booking' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import AdminLayout from '../../../shared/layouts/AdminLayout.vue'

defineProps({
    tables: Array
})

const isModalOpen = ref(false)
const isEditing = ref(false)

const form = useForm({
    id: null,
    number: '',
    capacity: 4,
    status: 'active'
})

const openModal = (table = null) => {
    if (table) {
        isEditing.value = true
        form.id = table.id
        form.number = table.number
        form.capacity = table.capacity
        form.status = table.status
    } else {
        isEditing.value = false
        form.reset()
    }
    isModalOpen.value = true
}

const closeModal = () => {
    isModalOpen.value = false
    form.reset()
    form.clearErrors()
}

const submit = () => {
    if (isEditing.value) {
        form.put(`/admin/tables/${form.id}`, {
            onSuccess: () => closeModal()
        })
    } else {
        form.post('/admin/tables', {
            onSuccess: () => closeModal()
        })
    }
}

const deleteTable = (id) => {
    if (confirm('Yakin mau hapus meja ini?')) {
        router.delete(`/admin/tables/${id}`)
    }
}
</script>

<template>
    <AdminLayout>
        <div class="flex justify-between items-end mb-8 border-b border-border-theme pb-6">
            <div>
                <h2 class="text-3xl font-extrabold text-text-main">Manajemen Meja</h2>
                <p class="text-text-muted mt-1">Kelola data meja untuk reservasi atau pesanan dine-in.</p>
            </div>
            <button @click="openModal()" class="bg-accent hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-bold shadow-md shadow-accent/20 transition-all transform hover:-translate-y-0.5">
                + Tambah Meja
            </button>
        </div>

        <div class="bg-surface border border-border-theme rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-text-muted">
                    <thead class="text-xs uppercase bg-surface-hover text-text-main">
                        <tr>
                            <th class="px-6 py-4 font-bold border-b border-border-theme">Nomor Meja</th>
                            <th class="px-6 py-4 font-bold border-b border-border-theme">Kapasitas</th>
                            <th class="px-6 py-4 font-bold border-b border-border-theme">Status</th>
                            <th class="px-6 py-4 font-bold border-b border-border-theme text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="table in tables" :key="table.id" class="border-b border-border-theme hover:bg-surface-hover/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-text-main text-base">Meja {{ table.number }}</td>
                            <td class="px-6 py-4">{{ table.capacity }} Orang</td>
                            <td class="px-6 py-4">
                                <span :class="table.status === 'active' ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20'" class="px-3 py-1 rounded-full text-xs font-bold border">
                                    {{ table.status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <button @click="openModal(table)" class="text-blue-500 font-bold hover:underline transition-colors">Edit</button>
                                <button @click="deleteTable(table.id)" class="text-red-500 font-bold hover:underline transition-colors">Hapus</button>
                            </td>
                        </tr>
                        <tr v-if="!tables.length">
                            <td colspan="4" class="px-6 py-8 text-center text-text-muted italic">Belum ada data meja.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Form -->
        <div v-if="isModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <div class="bg-surface border border-border-theme w-full max-w-md rounded-3xl shadow-xl overflow-hidden animate-fade-in-up">
                <div class="p-6 border-b border-border-theme flex justify-between items-center">
                    <h3 class="text-xl font-bold text-text-main">{{ isEditing ? 'Edit Meja' : 'Tambah Meja Baru' }}</h3>
                    <button @click="closeModal" class="text-text-muted hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form @submit.prevent="submit" class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-text-main mb-2">Nomor Meja</label>
                        <input v-model="form.number" type="number" min="1" required class="w-full bg-base border border-border-theme rounded-xl px-4 py-3 text-text-main focus:outline-none focus:border-accent transition-colors" placeholder="Misal: 1" />
                        <div v-if="form.errors.number" class="text-red-500 text-sm mt-1">{{ form.errors.number }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-text-main mb-2">Kapasitas (Orang)</label>
                        <input v-model="form.capacity" type="number" min="1" required class="w-full bg-base border border-border-theme rounded-xl px-4 py-3 text-text-main focus:outline-none focus:border-accent transition-colors" placeholder="Misal: 4" />
                        <div v-if="form.errors.capacity" class="text-red-500 text-sm mt-1">{{ form.errors.capacity }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-text-main mb-2">Status</label>
                        <select v-model="form.status" class="w-full bg-base border border-border-theme rounded-xl px-4 py-3 text-text-main focus:outline-none focus:border-accent transition-colors appearance-none">
                            <option value="active" class="bg-base text-text-main">Aktif</option>
                            <option value="inactive" class="bg-base text-text-main">Nonaktif</option>
                        </select>
                        <div v-if="form.errors.status" class="text-red-500 text-sm mt-1">{{ form.errors.status }}</div>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="closeModal" class="flex-1 py-3 bg-surface hover:bg-surface-hover border border-border-theme text-text-main rounded-xl font-bold transition-colors">Batal</button>
                        <button type="submit" :disabled="form.processing" class="flex-1 py-3 bg-accent hover:opacity-90 text-white rounded-xl font-bold shadow-md shadow-accent/20 transition-all disabled:opacity-50">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.animate-fade-in-up {
    animation: fadeInUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
</style>

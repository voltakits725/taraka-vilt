<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '../../../shared/layouts/AdminLayout.vue';
import EmployeeFormModal from '../../../features/Employee/ui/EmployeeFormModal.vue';
import DeleteEmployeeModal from '../../../features/Employee/ui/DeleteEmployeeModal.vue';

const props = defineProps({
    employees: Array
});

const isFormModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const selectedEmployee = ref(null);

const openAddModal = () => {
    selectedEmployee.value = null;
    isFormModalOpen.value = true;
};

const openEditModal = (employee) => {
    selectedEmployee.value = employee;
    isFormModalOpen.value = true;
};

const openDeleteModal = (employee) => {
    selectedEmployee.value = employee;
    isDeleteModalOpen.value = true;
};

const formatRupiah = (number) => {
    if (!number) return '-';
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
};
</script>

<template>
    <Head title="Manajemen Karyawan" />
    <AdminLayout>
        <div class="max-w-6xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-surface p-6 rounded-2xl border border-border-theme shadow-sm">
                <div>
                    <h1 class="text-2xl font-bold text-text-main font-serif tracking-wide">Manajemen Karyawan</h1>
                    <p class="text-sm text-text-muted mt-1">Kelola data Admin dan Barista Cafe Taraka</p>
                </div>
                <button @click="openAddModal" class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent hover:bg-accent-hover text-white text-sm font-bold rounded-xl shadow-lg shadow-accent/30 transition-all active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Karyawan
                </button>
            </div>

            <!-- Tabel Karyawan -->
            <div class="bg-surface rounded-2xl border border-border-theme overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-text-muted uppercase bg-surface-hover border-b border-border-theme">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Nama / Email</th>
                                <th class="px-6 py-4 font-semibold">Jabatan</th>
                                <th class="px-6 py-4 font-semibold">No. WhatsApp</th>
                                <th class="px-6 py-4 font-semibold">Gaji Pokok</th>
                                <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-theme">
                            <tr v-for="emp in employees" :key="emp.id" class="hover:bg-surface-hover/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold bg-accent/10 text-accent shrink-0">
                                            {{ emp.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-text-main">{{ emp.name }}</div>
                                            <div class="text-xs text-text-muted mt-0.5">{{ emp.email }}</div>
                                            <div class="text-[10px] text-text-muted mt-0.5 capitalize flex items-center gap-1">
                                                <svg v-if="emp.gender === 'male'" class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <svg v-if="emp.gender === 'female'" class="w-3 h-3 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                {{ emp.gender === 'male' ? 'Laki-laki' : (emp.gender === 'female' ? 'Perempuan' : '-') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full"
                                        :class="emp.role === 'admin' ? 'bg-blue-500/10 text-blue-500 border border-blue-500/20' : 'bg-orange-500/10 text-orange-500 border border-orange-500/20'">
                                        {{ emp.role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-text-secondary font-medium">
                                    {{ emp.phone || '-' }}
                                </td>
                                <td class="px-6 py-4 text-text-secondary font-bold">
                                    {{ formatRupiah(emp.salary) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openEditModal(emp)" class="p-2 text-blue-500 hover:bg-blue-500/10 rounded-xl transition-colors" title="Edit Data">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button @click="openDeleteModal(emp)" class="p-2 text-red-500 hover:bg-red-500/10 rounded-xl transition-colors" title="Hapus Akun">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="employees.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-text-muted">
                                    <svg class="w-12 h-12 mx-auto text-border-theme mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Belum ada data karyawan (Admin/Barista).
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <EmployeeFormModal 
            :show="isFormModalOpen" 
            :employee="selectedEmployee" 
            @close="isFormModalOpen = false" 
        />
        
        <DeleteEmployeeModal 
            :show="isDeleteModalOpen" 
            :employee="selectedEmployee" 
            @close="isDeleteModalOpen = false" 
        />
    </AdminLayout>
</template>

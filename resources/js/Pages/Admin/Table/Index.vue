<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '../../../shared/layouts/AdminLayout.vue'
import TableList from '../../../entities/Admin/Table/ui/TableList.vue'
import TableModal from '../../../features/Admin/Table/ui/TableModal.vue'

defineProps({
    tables: Array
})

const isModalOpen = ref(false)
const isEditing = ref(false)
const selectedTable = ref(null)

const openModal = (table = null) => {
    if (table) {
        isEditing.value = true
        selectedTable.value = table
    } else {
        isEditing.value = false
        selectedTable.value = null
    }
    isModalOpen.value = true
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

        <TableList 
            :tables="tables" 
            @edit="openModal" 
            @delete="deleteTable" 
        />

        <!-- Modal Form -->
        <TableModal 
            :show="isModalOpen" 
            :is-editing="isEditing" 
            :table="selectedTable" 
            @close="isModalOpen = false" 
        />
    </AdminLayout>
</template>

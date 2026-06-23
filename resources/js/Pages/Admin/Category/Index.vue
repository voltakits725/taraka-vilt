<!-- resources/js/Pages/Admin/Category/Index.vue -->
<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '../../../shared/layouts/AdminLayout.vue'
import CategoryModal from '../../../features/category/ui/CategoryModal.vue'
import CategoryTable from '../../../entities/category/ui/CategoryTable.vue'

defineProps({
    categories: Array
})

const isModalOpen = ref(false)
const selectedCategory = ref(null)

const openModal = (category = null) => {
    selectedCategory.value = category
    isModalOpen.value = true
}

const deleteCategory = (id) => {
    if (confirm('Yakin mau hapus kategori ini? Menu yang ada di kategori ini juga bisa terpengaruh lho.')) {
        router.delete(`/admin/categories/${id}`)
    }
}
</script>

<template>
    <AdminLayout>
        <!-- Bagian Header -->
        <div class="flex justify-between items-end mb-8 border-b border-border-theme pb-6">
            <div>
                <h2 class="text-3xl font-extrabold text-text-main">Kategori Menu</h2>
                <p class="text-text-muted mt-1">Kelola kategori untuk mempermudah pelanggan mencari menu.</p>
            </div>
            <button @click="openModal()" class="bg-accent hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-bold shadow-md shadow-accent/20 transition-all transform hover:-translate-y-0.5">
                + Tambah Kategori
            </button>
        </div>

        <!-- Render Tabel Kategori -->
        <div class="mb-8">
            <CategoryTable 
                :categories="categories"
                @edit="openModal"
                @delete="deleteCategory"
            />
        </div>

        <!-- Render Modal -->
        <CategoryModal 
            :show="isModalOpen" 
            :category="selectedCategory" 
            @close="isModalOpen = false" 
        />
    </AdminLayout>
</template>
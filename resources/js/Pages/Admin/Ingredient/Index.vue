<!-- resources/js/Pages/Admin/Ingredient/Index.vue -->
<script setup>
import { ref, watch } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '../../../shared/layouts/AdminLayout.vue'
import IngredientTable from '../../../entities/Admin/Ingredient/ui/IngredientTable.vue'
import IngredientModal from '../../../features/Admin/Ingredient/ui/IngredientModal.vue'

defineProps({
    ingredients: Object
})

const page = usePage()
const isModalOpen = ref(false)
const selectedIngredient = ref(null)

watch(() => page.props.flash.message, (msg) => {
    if (msg) {
        Swal.fire({
            icon: 'success', title: 'Berhasil!', text: msg, toast: true,
            position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true,
        })
    }
}, { immediate: true })

const openModal = (item = null) => {
    selectedIngredient.value = item
    isModalOpen.value = true
}

const handleDelete = (id) => {
    Swal.fire({
        title: 'Yakin hapus bahan ini?',
        text: "Data yang dihapus nggak bisa dikembalikan!",
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8', confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/ingredients/${id}`)
        }
    })
}
</script>

<template>
    <AdminLayout>
        <div class="flex justify-between items-end mb-8 border-b border-border-theme pb-6">
            <div>
                <h2 class="text-3xl font-extrabold text-text-main">Master Data Bahan</h2>
                <p class="text-text-muted mt-1">Kelola data bahan makanan dan informasi alergen.</p>
            </div>
            <button @click="openModal()" class="bg-accent hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-bold shadow-md shadow-accent/20 transition-all transform hover:-translate-y-0.5">
                + Tambah Bahan
            </button>
        </div>

        <!-- Render Tabel Bahan -->
        <div class="mb-8">
            <IngredientTable 
                :ingredients="ingredients" 
                @edit="openModal" 
                @delete="handleDelete" 
            />
        </div>

        <!-- Paginasi -->
        <div class="flex justify-center gap-2" v-if="ingredients.links.length > 3">
            <template v-for="(link, k) in ingredients.links" :key="k">
                <div v-if="link.url === null" class="px-4 py-2 border border-border-theme text-text-muted opacity-50 rounded-lg bg-surface font-semibold" v-html="link.label" />
                <Link v-else :href="link.url" class="px-4 py-2 border rounded-lg transition-all font-semibold"
                    :class="link.active ? 'bg-accent text-white border-accent shadow-md shadow-accent/20' : 'bg-surface text-text-muted border-border-theme hover:border-accent hover:text-accent'"
                    v-html="link.label" />
            </template>
        </div>

        <IngredientModal 
            :show="isModalOpen" 
            :ingredient="selectedIngredient" 
            @close="isModalOpen = false" 
        />
    </AdminLayout>
</template>
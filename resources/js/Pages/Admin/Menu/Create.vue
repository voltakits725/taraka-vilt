<script setup>
import { Link } from '@inertiajs/vue3'
import AdminLayout from '../../../shared/layouts/AdminLayout.vue'
import MenuForm from '../../../features/menu/ui/MenuForm.vue'

defineProps({ categories: Array, ingredients: Array })
</script>

<template>
    <AdminLayout>
        <div class="mb-6 flex items-center gap-4">
            <Link href="/admin/menus" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-blue-600 transition-colors">&larr;</Link>
            <div>
                <h2 class="text-3xl font-bold text-slate-800">Tambah Menu Baru</h2>
                <p class="text-slate-500 mt-1">Masukkan detail menu dan komposisi bahan.</p>
            </div>
        </div>
        <MenuForm :categories="categories" :ingredients="ingredients" />
    </AdminLayout>
</template>
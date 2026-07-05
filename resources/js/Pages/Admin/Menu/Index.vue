<!-- resources/js/Pages/Admin/Menu/Index.vue -->
<script setup>
import { watch } from 'vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '../../../shared/layouts/AdminLayout.vue'
import MenuTable from '../../../entities/Admin/Menu/ui/MenuTable.vue'

const props = defineProps({
    menus: Object, 
    categories: Array,
    filters: Object
})

const page = usePage()

watch(() => page.props.flash.message, (msg) => {
    if (msg) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: msg,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        })
    }
}, { immediate: true })

const deleteMenu = (slug) => {
    Swal.fire({
        title: 'Yakin hapus menu ini?',
        text: "Data dan gambarnya akan ikut terhapus lho!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/menus/${slug}`)
        }
    })
}
</script>

<template>
    <AdminLayout>
        <!-- Header Section -->
        <div class="flex justify-between items-end mb-8 border-b border-border-theme pb-6">
            <div>
                <h2 class="text-3xl font-extrabold text-text-main">Master Menu</h2>
                <p class="text-text-muted mt-1">Kelola daftar menu Cafe Taraka.</p>
            </div>
            <Link href="/admin/menus/create" class="bg-accent hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-bold shadow-md shadow-accent/20 transition-all transform hover:-translate-y-0.5">
                + Tambah Menu
            </Link>
        </div>

        <!-- Kategori Filter -->
        <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
            <Link href="/admin/menus" class="px-5 py-2 rounded-full font-bold text-sm transition-all whitespace-nowrap border"
                :class="!filters.category ? 'bg-accent border-accent text-white shadow-md shadow-accent/20' : 'bg-surface border-border-theme text-text-muted hover:bg-surface-hover hover:text-text-main'">
                Semua Menu
            </Link>
            <Link v-for="cat in categories" :key="cat.id" 
                :href="`/admin/menus?category=${cat.slug}`"
                class="px-5 py-2 rounded-full font-bold text-sm transition-all whitespace-nowrap border"
                :class="filters.category === cat.slug ? 'bg-accent border-accent text-white shadow-md shadow-accent/20' : 'bg-surface border-border-theme text-text-muted hover:bg-surface-hover hover:text-text-main'">
                {{ cat.name }}
            </Link>
        </div>

        <!-- Render Komponen Entity MenuTable yang udah bersih -->
        <div class="mb-8">
            <MenuTable 
                :menus="menus" 
                @delete="deleteMenu"
            />
        </div>

        <!-- Paginasi -->
        <div class="flex justify-center gap-2" v-if="menus.links.length > 3">
            <template v-for="(link, k) in menus.links" :key="k">
                <div v-if="link.url === null" class="px-4 py-2 border border-border-theme text-text-muted opacity-50 rounded-lg bg-surface font-semibold" v-html="link.label" />
                <Link v-else :href="link.url" class="px-4 py-2 border rounded-lg transition-all font-semibold"
                    :class="link.active ? 'bg-accent text-white border-accent shadow-md shadow-accent/20' : 'bg-surface text-text-muted border-border-theme hover:border-accent hover:text-accent'"
                    v-html="link.label" />
            </template>
        </div>
    </AdminLayout>
</template>
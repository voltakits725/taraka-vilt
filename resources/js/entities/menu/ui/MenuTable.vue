<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    menus: Object
})

defineEmits(['delete'])
</script>

<template>
    <div class="bg-surface rounded-2xl shadow-sm border border-border-theme overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-max">
                <thead>
                    <tr class="bg-surface-hover border-b border-border-theme text-text-muted text-sm uppercase tracking-wider">
                        <th class="py-4 px-6 font-bold w-16">No</th>
                        <th class="py-4 px-6 font-bold w-24">Gambar</th>
                        <th class="py-4 px-6 font-bold">Nama Menu</th>
                        <th class="py-4 px-6 font-bold">Kategori</th>
                        <th class="py-4 px-6 font-bold">Harga</th>
                        <th class="py-4 px-6 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="menus.data.length === 0">
                        <td colspan="6" class="py-12 text-center text-text-muted italic">Belum ada menu di kategori ini.</td>
                    </tr>
                    
                    <tr v-for="(menu, index) in menus.data" :key="menu.id" class="border-b border-border-theme hover:bg-surface-hover transition-colors">
                        <td class="py-4 px-6 text-text-muted font-bold">
                            {{ (menus.current_page - 1) * menus.per_page + index + 1 }}
                        </td>
                        
                        <td class="py-4 px-6">
                            <img v-if="menu.image" :src="menu.image" :alt="menu.name" class="w-12 h-12 rounded-xl object-cover border border-border-theme shadow-sm">
                            <div v-else class="w-12 h-12 rounded-xl bg-surface-hover border border-border-theme flex items-center justify-center text-[10px] text-text-muted font-bold text-center leading-tight">
                                No<br>Image
                            </div>
                        </td>
                        
                        <td class="py-4 px-6 font-bold text-text-main text-lg">{{ menu.name }}</td>
                        
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 bg-surface-hover text-text-muted border border-border-theme rounded-lg text-xs font-semibold shadow-sm">
                                {{ menu.category?.name || 'Tanpa Kategori' }}
                            </span>
                        </td>
                        
                        <td class="py-4 px-6 font-bold text-text-main text-lg">
                            Rp {{ menu.price.toLocaleString('id-ID') }}
                        </td>
                        
                        <td class="py-4 px-6 flex justify-end gap-2">
                            <Link :href="`/admin/menus/${menu.slug}`" class="px-3 py-1.5 bg-accent/10 text-accent hover:bg-accent hover:text-white rounded-lg text-sm font-bold transition-colors">
                                Detail
                            </Link>
                            <Link :href="`/admin/menus/${menu.slug}/edit`" class="px-3 py-1.5 bg-amber-500/10 text-amber-500 hover:bg-amber-500/20 rounded-lg text-sm font-bold transition-colors">
                                Edit
                            </Link>
                            <button @click="$emit('delete', menu.slug)" class="px-3 py-1.5 bg-red-500/10 text-red-500 hover:bg-red-500/20 rounded-lg text-sm font-bold transition-colors">
                                Hapus
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
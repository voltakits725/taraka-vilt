<script setup>
import { Link } from '@inertiajs/vue3'
import AdminLayout from '../../../shared/layouts/AdminLayout.vue'

defineProps({
    menu: Object
})
</script>

<template>
    <AdminLayout>
        <div class="mb-6">
            <Link href="/admin/menus" class="text-blue-600 font-semibold hover:underline flex items-center gap-2">
                &larr; Kembali ke Daftar Menu
            </Link>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden max-w-5xl">
            <div class="flex flex-col md:flex-row">
                
                <div class="w-full md:w-2/5 bg-slate-50 border-r border-slate-100 flex items-center justify-center p-8">
                    <img v-if="menu.image" :src="menu.image" :alt="menu.name" 
                        class="w-full max-w-sm aspect-square object-cover rounded-2xl shadow-md border border-slate-200">
                    
                    <div v-else class="w-full max-w-sm aspect-square rounded-2xl bg-slate-200 flex flex-col items-center justify-center text-slate-400 shadow-inner">
                        <svg class="w-16 h-16 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-semibold">Belum ada gambar</span>
                    </div>
                </div>

                <div class="w-full md:w-3/5 p-8 lg:p-12">
                    <div class="inline-block px-4 py-1.5 bg-blue-100 text-blue-700 rounded-full text-sm font-bold tracking-wide mb-4">
                        {{ menu.category.name }}
                    </div>
                    
                    <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight mb-2">
                        {{ menu.name }}
                    </h1>
                    <p class="text-slate-400 font-mono text-sm mb-8">URL: /{{ menu.slug }}</p>

                    <div class="mb-8">
                        <div class="text-sm text-slate-500 font-bold uppercase tracking-wider mb-1">Harga Menu</div>
                        <div class="text-4xl font-extrabold text-emerald-600">
                            Rp {{ menu.price.toLocaleString('id-ID') }}
                        </div>
                    </div>

                    <div class="pt-8 border-t border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800 mb-3">Deskripsi</h3>
                        <p class="text-slate-600 leading-relaxed bg-slate-50 p-5 rounded-2xl border border-slate-100">
                            {{ menu.description || 'Tidak ada deskripsi untuk menu ini.' }}
                        </p>
                    </div>

                    <div class="pt-8 mt-8 border-t border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Komposisi Menu</h3>
                        
                        <div v-if="menu.ingredients && menu.ingredients.length > 0" class="flex flex-wrap gap-2">
                            <div v-for="ing in menu.ingredients" :key="ing.id" 
                                class="px-4 py-2 rounded-xl text-sm font-semibold border flex items-center gap-2 transition-all"
                                :class="ing.is_allergen ? 'bg-red-50 text-red-700 border-red-200 shadow-sm' : 'bg-slate-50 text-slate-600 border-slate-200'">
                                
                                <svg v-if="ing.is_allergen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                
                                {{ ing.name }}
                                <span v-if="ing.is_allergen" class="opacity-75">({{ ing.allergen_type }})</span>
                            </div>
                        </div>
                        <p v-else class="text-slate-400 italic text-sm">Belum ada data komposisi bahan.</p>
                    </div>
                    
                    </div>
                
            </div>
        </div>
    </AdminLayout>
</template>
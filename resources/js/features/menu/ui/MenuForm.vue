<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    menu: { type: Object, default: null },
    categories: Array,
    ingredients: Array
})

// State untuk Live Preview Gambar
const previewUrl = ref(props.menu?.image || null)

const form = useForm({
    category_id: props.menu?.category_id || '',
    name: props.menu?.name || '',
    price: props.menu?.price || '',
    description: props.menu?.description || '',
    image: null,
    ingredient_ids: props.menu?.ingredients ? props.menu.ingredients.map(i => i.id) : [],
})

// Fungsi handle upload & generate live preview
const handleImageChange = (e) => {
    const file = e.target.files[0]
    form.image = file
    if (file) {
        previewUrl.value = URL.createObjectURL(file) 
    } else {
        previewUrl.value = props.menu?.image || null 
    }
}

const submit = () => {
    if (props.menu) {
        form.transform((data) => ({
            ...data,
            _method: 'put',
        })).post(`/admin/menus/${props.menu.slug}`)
    } else {
        form.post('/admin/menus')
    }
}
</script>

<template>
    <form @submit.prevent="submit" class="bg-surface rounded-2xl shadow-sm border border-border-theme p-8 max-w-4xl transition-colors duration-500">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div>
                <label class="block text-sm font-bold text-text-main mb-3">Foto Menu</label>
                
                <div class="w-full aspect-square bg-base rounded-2xl border-2 border-dashed border-border-theme flex flex-col items-center justify-center overflow-hidden relative mb-4 transition-all hover:bg-surface-hover">
                    <img v-if="previewUrl" :src="previewUrl" class="w-full h-full object-cover absolute inset-0 z-10" />
                    <div v-else class="text-text-muted flex flex-col items-center z-0">
                        <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-sm font-medium">Belum ada gambar</span>
                    </div>
                </div>

                <input type="file" @change="handleImageChange" accept="image/*" 
                    class="w-full text-sm text-text-muted file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-accent/10 file:text-accent hover:file:bg-accent/20 cursor-pointer outline-none transition-colors">
                
                <progress v-if="form.progress" :value="form.progress.percentage" max="100" class="mt-3 w-full h-2 rounded-full overflow-hidden"></progress>
                <span v-if="form.errors.image" class="text-red-500 text-xs mt-1 block">{{ form.errors.image }}</span>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-text-main mb-1">Kategori</label>
                    <select v-model="form.category_id" required class="w-full px-4 py-3 bg-base text-text-main rounded-xl border border-border-theme focus:border-accent outline-none transition-colors">
                        <option value="" disabled>Pilih Kategori...</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>
                    <span v-if="form.errors.category_id" class="text-red-500 text-xs mt-1 block">{{ form.errors.category_id }}</span>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-text-main mb-1">Nama Menu</label>
                    <input v-model="form.name" type="text" required class="w-full px-4 py-3 bg-base text-text-main rounded-xl border border-border-theme focus:border-accent outline-none transition-colors">
                    <span v-if="form.errors.name" class="text-red-500 text-xs mt-1 block">{{ form.errors.name }}</span>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-text-main mb-1">Harga (Rp)</label>
                    <input v-model="form.price" type="number" min="0" required class="w-full px-4 py-3 bg-base text-text-main rounded-xl border border-border-theme focus:border-accent outline-none transition-colors">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-text-main mb-1">Deskripsi</label>
                    <textarea v-model="form.description" rows="3" class="w-full px-4 py-3 bg-base text-text-main rounded-xl border border-border-theme focus:border-accent outline-none transition-colors"></textarea>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-border-theme">
            <label class="block text-sm font-bold text-text-main mb-3">Komposisi Bahan & Alergen</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 p-4 bg-base border border-border-theme rounded-2xl max-h-48 overflow-y-auto">
                
                <label v-for="ing in ingredients" :key="ing.id" class="flex items-center gap-3 cursor-pointer p-2 hover:bg-surface-hover rounded-xl transition-all border border-transparent hover:border-border-theme">
                    <input type="checkbox" :value="ing.id" v-model="form.ingredient_ids" 
                        class="w-4 h-4 rounded border-border-theme focus:ring-accent bg-surface" :style="{ color: 'var(--color-accent)' }">
                    
                    <span class="text-sm text-text-main font-medium">
                        {{ ing.name }}
                        <span v-if="ing.is_allergen" class="ml-1 text-[10px] bg-red-500/10 text-red-500 px-1.5 py-0.5 rounded-md font-bold border border-red-500/20">
                            {{ ing.allergen_type }}
                        </span>
                    </span>
                </label>

            </div>
        </div>

        <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-border-theme">
            <Link href="/admin/menus" class="px-6 py-3 rounded-xl text-text-muted font-bold hover:bg-surface-hover transition-colors">
                Batal
            </Link>
            
            <button type="submit" :disabled="form.processing" class="px-6 py-3 bg-accent text-white hover:opacity-90 rounded-xl font-bold shadow-md shadow-accent/20 disabled:opacity-50 transition-all transform hover:-translate-y-0.5">
                {{ form.processing ? 'Menyimpan...' : (menu ? 'Simpan Perubahan' : 'Simpan Menu Baru') }}
            </button>
        </div>
    </form>
</template>
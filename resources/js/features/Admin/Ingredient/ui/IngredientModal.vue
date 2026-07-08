<!-- resources/js/features/ingredient/ui/IngredientModal.vue -->
<script setup>
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'

const props = defineProps({
    show: Boolean,
    ingredient: Object
})

const emit = defineEmits(['close'])

const form = useForm({
    name: '',
    is_allergen: false,
    allergen_type: '',
    stock: 0,
    unit: 'gram',
})

const allergenKeywords = {
    'Susu / Dairy': ['susu', 'keju', 'krim', 'butter', 'mentega', 'laktosa', 'kasein', 'yoghurt'],
    'Kacang-kacangan': ['kacang', 'almond', 'selai', 'hazelnut', 'mede', 'pistachio', 'walnut'],
    'Telur': ['telur', 'mayones', 'putih telur', 'kuning telur'],
    'Seafood': ['udang', 'kepiting', 'cumi', 'kerang', 'lobster', 'ikan', 'teri', 'ebi', 'seafood'],
    'Gandum / Gluten': ['gandum', 'terigu', 'roti', 'pasta', 'biskuit', 'oat', 'mie', 'macaroni'],
    'Kedelai': ['kedelai', 'tahu', 'tempe', 'kecap', 'edamame', 'soy'],
}

watch(() => props.show, (isOpen) => {
    if (isOpen) {
        if (props.ingredient) {
            form.name = props.ingredient.name
            form.is_allergen = props.ingredient.is_allergen ? true : false
            form.allergen_type = props.ingredient.allergen_type || ''
            form.stock = props.ingredient.stock || 0
            form.unit = props.ingredient.unit || 'gram'
        } else {
            form.reset()
            form.name = ''
            form.is_allergen = false
            form.allergen_type = ''
            form.stock = 0
            form.unit = 'gram'
        }
        form.clearErrors()
    }
})

watch(() => form.name, (newName) => {
    if (props.ingredient) return; 

    if (newName && newName.length > 2) {
        let detectedAllergen = null;
        const lowerName = newName.toLowerCase();

        for (const [type, keywords] of Object.entries(allergenKeywords)) {
            if (keywords.some(keyword => lowerName.includes(keyword))) {
                detectedAllergen = type;
                break;
            }
        }

        if (detectedAllergen) {
            form.is_allergen = true;
            form.allergen_type = detectedAllergen;
        }
    }
})

const submit = () => {
    if (props.ingredient) {
        form.put(`/admin/ingredients/${props.ingredient.id}`, { onSuccess: () => emit('close') })
    } else {
        form.post('/admin/ingredients', { onSuccess: () => { form.reset(); emit('close') } })
    }
}
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <!-- Container Modal dengan warna Surface -->
        <div class="bg-surface rounded-2xl w-full max-w-md shadow-2xl border border-border-theme overflow-hidden">
            <div class="px-6 py-4 border-b border-border-theme flex justify-between items-center bg-surface-hover/50">
                <h3 class="font-bold text-lg text-text-main">{{ ingredient ? 'Edit Bahan' : 'Tambah Bahan Baru' }}</h3>
                <button @click="$emit('close')" class="text-text-muted hover:text-red-500 font-bold text-xl transition-colors">&times;</button>
            </div>
            
            <form @submit.prevent="submit" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-text-main mb-1">Nama Bahan</label>
                    <input v-model="form.name" type="text" placeholder="Contoh: Susu Sapi, Kacang Almond..." required 
                        class="w-full px-4 py-2 rounded-lg bg-base border border-border-theme text-text-main focus:border-accent outline-none transition-colors">
                    <span v-if="form.errors.name" class="text-red-500 text-xs mt-1 block">{{ form.errors.name }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text-main mb-1">Sisa Stok</label>
                        <input v-model="form.stock" type="number" min="0" required 
                            class="w-full px-4 py-2 rounded-lg bg-base border border-border-theme text-text-main focus:border-accent outline-none transition-colors">
                        <span v-if="form.errors.stock" class="text-red-500 text-xs mt-1 block">{{ form.errors.stock }}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text-main mb-1">Satuan</label>
                        <select v-model="form.unit" required 
                            class="w-full px-4 py-2 rounded-lg bg-base border border-border-theme text-text-main focus:border-accent outline-none transition-colors">
                            <option value="gram">Gram (gr)</option>
                            <option value="ml">Mililiter (ml)</option>
                            <option value="pcs">Pcs / Buah</option>
                        </select>
                        <span v-if="form.errors.unit" class="text-red-500 text-xs mt-1 block">{{ form.errors.unit }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3 bg-surface-hover p-4 rounded-xl border border-border-theme transition-colors">
                    <input v-model="form.is_allergen" type="checkbox" id="is_allergen" 
                        class="w-5 h-5 rounded border-border-theme cursor-pointer transition-all focus:ring-accent" :style="{ color: 'var(--color-accent)' }">
                    <label for="is_allergen" class="text-sm font-semibold text-text-main cursor-pointer select-none w-full">
                        Tandai sebagai bahan Alergen
                    </label>
                </div>

                <div v-if="form.is_allergen" class="p-4 bg-red-500/5 rounded-xl border border-red-500/20 transition-all">
                    <label class="block text-sm font-semibold text-red-500 mb-1">Tipe Alergen</label>
                    
                    <input v-model="form.allergen_type" type="text" list="allergen_list" placeholder="Pilih atau ketik tipe alergen..." required 
                        class="w-full px-4 py-2 rounded-lg bg-base border border-red-500/30 text-text-main focus:border-red-500 outline-none transition-colors">
                    
                    <datalist id="allergen_list">
                        <option value="Susu / Dairy"></option>
                        <option value="Telur"></option>
                        <option value="Kacang Tanah"></option>
                        <option value="Kacang-kacangan"></option>
                        <option value="Ikan"></option>
                        <option value="Seafood"></option>
                        <option value="Kedelai"></option>
                        <option value="Gandum / Gluten"></option>
                        <option value="Wijen"></option>
                    </datalist>

                    <span v-if="form.errors.allergen_type" class="text-red-500 text-xs mt-1 block">{{ form.errors.allergen_type }}</span>
                    <p class="text-[11px] text-red-500/80 mt-2 font-medium leading-tight">
                        * Sistem mendeteksi otomatis kata kunci bahan. Anda juga bisa memilih dari daftar penyebab 90% alergi makanan (The Big 9).
                    </p>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-border-theme">
                    <button type="button" @click="$emit('close')" class="px-5 py-2.5 rounded-lg text-text-muted font-semibold hover:bg-surface-hover transition-colors">Batal</button>
                    <button type="submit" :disabled="form.processing" class="px-5 py-2.5 bg-accent text-white hover:opacity-90 rounded-lg font-semibold shadow-md disabled:opacity-50 transition-colors">
                        {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'

const props = defineProps({
    show: Boolean,
    category: Object // Kalau null berarti mode Create, kalau ada isinya berarti mode Edit
})

const emit = defineEmits(['close'])

const form = useForm({
    name: '',
})

// Update isi form tiap kali modal dibuka buat edit
watch(() => props.category, (newVal) => {
    form.name = newVal ? newVal.name : ''
    form.clearErrors()
}, { immediate: true })

const submit = () => {
    if (props.category) {
        form.put(`/admin/categories/${props.category.id}`, {
            onSuccess: () => emit('close')
        })
    } else {
        form.post('/admin/categories', {
            onSuccess: () => {
                form.reset()
                emit('close')
            }
        })
    }
}
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-lg text-slate-800">
                    {{ category ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
                </h3>
                <button @click="$emit('close')" class="text-slate-400 hover:text-red-500 font-bold text-xl">&times;</button>
            </div>
            
            <form @submit.prevent="submit" class="p-6">
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Kategori</label>
                    <input v-model="form.name" type="text" placeholder="Cth: Coffee, Snack..." required
                        class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                    <span v-if="form.errors.name" class="text-red-500 text-xs mt-1 block">{{ form.errors.name }}</span>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="$emit('close')" class="px-5 py-2.5 rounded-lg text-slate-600 font-semibold hover:bg-slate-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" :disabled="form.processing" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-md disabled:opacity-50 transition-colors">
                        {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
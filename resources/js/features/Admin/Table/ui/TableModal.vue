<script setup>
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'

const props = defineProps({
    show: Boolean,
    isEditing: Boolean,
    table: {
        type: Object,
        default: null
    }
})

const emit = defineEmits(['close'])

const form = useForm({
    id: null,
    number: '',
    capacity: 4,
    status: 'active'
})

watch(() => props.show, (newVal) => {
    if (newVal && props.isEditing && props.table) {
        form.id = props.table.id
        form.number = props.table.number
        form.capacity = props.table.capacity
        form.status = props.table.status
    } else if (newVal && !props.isEditing) {
        form.reset()
        form.clearErrors()
    }
})

const submit = () => {
    if (props.isEditing) {
        form.put(`/admin/tables/${form.id}`, {
            onSuccess: () => emit('close')
        })
    } else {
        form.post('/admin/tables', {
            onSuccess: () => emit('close')
        })
    }
}
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-surface border border-border-theme w-full max-w-md rounded-3xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="p-6 border-b border-border-theme flex justify-between items-center">
                <h3 class="text-xl font-bold text-text-main">{{ isEditing ? 'Edit Meja' : 'Tambah Meja Baru' }}</h3>
                <button @click="$emit('close')" class="text-text-muted hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form @submit.prevent="submit" class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-bold text-text-main mb-2">Nomor Meja</label>
                    <input v-model="form.number" type="number" min="1" required class="w-full bg-base border border-border-theme rounded-xl px-4 py-3 text-text-main focus:outline-none focus:border-accent transition-colors" placeholder="Misal: 1" />
                    <div v-if="form.errors.number" class="text-red-500 text-sm mt-1">{{ form.errors.number }}</div>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-text-main mb-2">Kapasitas (Orang)</label>
                    <input v-model="form.capacity" type="number" min="1" required class="w-full bg-base border border-border-theme rounded-xl px-4 py-3 text-text-main focus:outline-none focus:border-accent transition-colors" placeholder="Misal: 4" />
                    <div v-if="form.errors.capacity" class="text-red-500 text-sm mt-1">{{ form.errors.capacity }}</div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-text-main mb-2">Status</label>
                    <select v-model="form.status" class="w-full bg-base border border-border-theme rounded-xl px-4 py-3 text-text-main focus:outline-none focus:border-accent transition-colors appearance-none">
                        <option value="active" class="bg-base text-text-main">Aktif</option>
                        <option value="inactive" class="bg-base text-text-main">Nonaktif</option>
                    </select>
                    <div v-if="form.errors.status" class="text-red-500 text-sm mt-1">{{ form.errors.status }}</div>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="$emit('close')" class="flex-1 py-3 bg-surface hover:bg-surface-hover border border-border-theme text-text-main rounded-xl font-bold transition-colors">Batal</button>
                    <button type="submit" :disabled="form.processing" class="flex-1 py-3 bg-accent hover:opacity-90 text-white rounded-xl font-bold shadow-md shadow-accent/20 transition-all disabled:opacity-50">
                        {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
.animate-fade-in-up {
    animation: fadeInUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
</style>

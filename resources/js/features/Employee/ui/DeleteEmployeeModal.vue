<script setup>
import { useForm } from '@inertiajs/vue3';
import Modal from '../../../shared/components/Modal.vue';

const props = defineProps({
    show: Boolean,
    employee: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close']);
const form = useForm({});

const deleteEmployee = () => {
    if (props.employee) {
        form.delete(`/admin/employees/${props.employee.id}`, {
            onSuccess: () => {
                emit('close');
            }
        });
    }
};
</script>

<template>
    <Modal :show="show" @close="emit('close')" maxWidth="md">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            <h3 class="mb-2 text-lg font-bold text-center text-text-main">Konfirmasi Pemecatan</h3>
            <p class="text-sm text-center text-text-muted mb-6">
                Apakah Anda yakin ingin menghapus akun 
                <span class="font-bold text-text-main">{{ employee?.name }}</span>? 
                Data karyawan ini tidak dapat dikembalikan lagi.
            </p>

            <div class="flex justify-center gap-3">
                <button type="button" @click="emit('close')" class="px-5 py-2.5 text-sm font-medium text-text-main bg-surface-hover hover:bg-border-theme rounded-xl transition-colors">
                    Batal
                </button>
                <button type="button" @click="deleteEmployee" :disabled="form.processing" class="px-5 py-2.5 text-sm font-bold text-white bg-red-500 hover:bg-red-600 rounded-xl shadow-lg shadow-red-500/30 transition-all disabled:opacity-50">
                    {{ form.processing ? 'Menghapus...' : 'Ya, Hapus Akun' }}
                </button>
            </div>
        </div>
    </Modal>
</template>

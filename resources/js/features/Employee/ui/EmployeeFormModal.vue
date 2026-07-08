<script setup>
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';
import Modal from '../../../shared/components/Modal.vue';

const props = defineProps({
    show: Boolean,
    employee: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close']);

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'barista',
    salary: '',
    gender: 'male',
    phone: '',
});

watch(() => props.employee, (newEmployee) => {
    if (newEmployee) {
        form.name = newEmployee.name;
        form.email = newEmployee.email;
        form.password = '';
        form.role = newEmployee.role;
        form.salary = newEmployee.salary || '';
        form.gender = newEmployee.gender || 'male';
        form.phone = newEmployee.phone || '';
    } else {
        form.reset();
    }
}, { immediate: true });

const submit = () => {
    if (props.employee) {
        form.put(`/admin/employees/${props.employee.id}`, {
            onSuccess: () => {
                form.reset();
                emit('close');
            }
        });
    } else {
        form.post('/admin/employees', {
            onSuccess: () => {
                form.reset();
                emit('close');
            }
        });
    }
};
</script>

<template>
    <Modal :show="show" @close="emit('close')" maxWidth="2xl">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-text-main">
                    {{ employee ? 'Edit Data Karyawan' : 'Tambah Karyawan Baru' }}
                </h2>
                <button @click="emit('close')" class="text-text-muted hover:text-text-main transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-text-main mb-1">Nama Lengkap</label>
                        <input v-model="form.name" type="text" class="w-full rounded-xl border border-border-theme bg-surface px-4 py-2 text-text-main focus:ring-2 focus:ring-accent focus:border-accent" required>
                        <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-text-main mb-1">Email</label>
                        <input v-model="form.email" type="email" class="w-full rounded-xl border border-border-theme bg-surface px-4 py-2 text-text-main focus:ring-2 focus:ring-accent focus:border-accent" required>
                        <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
                    </div>

                    <!-- Jabatan -->
                    <div>
                        <label class="block text-sm font-medium text-text-main mb-1">Jabatan (Role)</label>
                        <select v-model="form.role" class="w-full rounded-xl border border-border-theme bg-surface px-4 py-2 text-text-main focus:ring-2 focus:ring-accent focus:border-accent" required>
                            <option value="admin">Admin / Kasir</option>
                            <option value="barista">Barista / Dapur</option>
                        </select>
                        <p v-if="form.errors.role" class="text-red-500 text-xs mt-1">{{ form.errors.role }}</p>
                    </div>

                    <!-- Gaji -->
                    <div>
                        <label class="block text-sm font-medium text-text-main mb-1">Gaji (Rp)</label>
                        <input v-model="form.salary" type="number" min="0" class="w-full rounded-xl border border-border-theme bg-surface px-4 py-2 text-text-main focus:ring-2 focus:ring-accent focus:border-accent">
                        <p v-if="form.errors.salary" class="text-red-500 text-xs mt-1">{{ form.errors.salary }}</p>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-sm font-medium text-text-main mb-1">Jenis Kelamin</label>
                        <select v-model="form.gender" class="w-full rounded-xl border border-border-theme bg-surface px-4 py-2 text-text-main focus:ring-2 focus:ring-accent focus:border-accent">
                            <option value="male">Laki-laki</option>
                            <option value="female">Perempuan</option>
                        </select>
                        <p v-if="form.errors.gender" class="text-red-500 text-xs mt-1">{{ form.errors.gender }}</p>
                    </div>

                    <!-- No WhatsApp -->
                    <div>
                        <label class="block text-sm font-medium text-text-main mb-1">No. WhatsApp</label>
                        <input v-model="form.phone" type="text" class="w-full rounded-xl border border-border-theme bg-surface px-4 py-2 text-text-main focus:ring-2 focus:ring-accent focus:border-accent">
                        <p v-if="form.errors.phone" class="text-red-500 text-xs mt-1">{{ form.errors.phone }}</p>
                    </div>
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-text-main mb-1">
                        Password {{ employee ? '(Isi jika ingin mengubah password)' : '' }}
                    </label>
                    <input v-model="form.password" type="password" class="w-full rounded-xl border border-border-theme bg-surface px-4 py-2 text-text-main focus:ring-2 focus:ring-accent focus:border-accent" :required="!employee">
                    <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
                </div>

                <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-border-theme">
                    <button type="button" @click="emit('close')" class="px-5 py-2.5 text-sm font-medium text-text-muted hover:text-text-main transition-colors">
                        Batal
                    </button>
                    <button type="submit" :disabled="form.processing" class="px-5 py-2.5 text-sm font-bold bg-accent hover:bg-accent-hover text-white rounded-xl shadow-lg shadow-accent/30 transition-all disabled:opacity-50">
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Data' }}
                    </button>
                </div>
            </form>
        </div>
    </Modal>
</template>

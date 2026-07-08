<script setup>
import { ref } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

const props = defineProps({
    show: Boolean
})

const emit = defineEmits(['close'])
const page = usePage()
const user = page.props.auth.user

const form = useForm({
    name: user.name,
    phone: user.phone || '',
    avatar: null
})

const previewUrl = ref(user.avatar)

const handleFileChange = (e) => {
    const file = e.target.files[0]
    if (file) {
        form.avatar = file
        previewUrl.value = URL.createObjectURL(file)
    }
}

const submit = () => {
    form.post('/profil', {
        preserveScroll: true,
        onSuccess: () => {
            emit('close')
        }
    })
}
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="emit('close')"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-surface w-full max-w-md rounded-3xl shadow-xl overflow-hidden animate-fade-in-up">
            
            <div class="p-6 border-b border-border-theme flex items-center justify-between bg-surface-hover">
                <h3 class="text-xl font-bold text-text-main">Edit Profil</h3>
                <button @click="emit('close')" class="w-8 h-8 rounded-full bg-surface border border-border-theme flex items-center justify-center text-text-muted hover:text-accent hover:border-accent/30 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form @submit.prevent="submit" class="p-6 space-y-6">
                <!-- Avatar Upload -->
                <div class="text-center">
                    <div class="relative inline-block group">
                        <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-surface shadow-md bg-accent/10 flex items-center justify-center text-accent text-3xl font-black uppercase">
                            <img v-if="previewUrl" :src="previewUrl" class="w-full h-full object-cover">
                            <span v-else>{{ form.name.charAt(0) }}</span>
                        </div>
                        
                        <label class="absolute inset-0 flex items-center justify-center bg-black/50 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <input type="file" class="hidden" accept="image/*" @change="handleFileChange">
                        </label>
                    </div>
                    <p class="text-xs text-text-muted mt-2">Klik foto untuk mengganti</p>
                    <p v-if="form.errors.avatar" class="text-xs text-red-500 mt-1">{{ form.errors.avatar }}</p>
                </div>

                <!-- Name Input -->
                <div>
                    <label class="block text-sm font-bold text-text-main mb-2">Nama Lengkap</label>
                    <input 
                        v-model="form.name" 
                        type="text" 
                        required
                        class="w-full px-4 py-3 rounded-xl bg-surface-hover border border-border-theme text-text-main placeholder-text-muted focus:border-accent focus:ring-1 focus:ring-accent/30 outline-none transition-all text-sm font-medium"
                    >
                    <p v-if="form.errors.name" class="text-xs text-red-500 mt-1">{{ form.errors.name }}</p>
                </div>

                <!-- Phone Input -->
                <div>
                    <label class="block text-sm font-bold text-text-main mb-2">Nomor WhatsApp</label>
                    <input 
                        v-model="form.phone" 
                        type="tel" 
                        placeholder="Contoh: 081234567890"
                        class="w-full px-4 py-3 rounded-xl bg-surface-hover border border-border-theme text-text-main placeholder-text-muted focus:border-accent focus:ring-1 focus:ring-accent/30 outline-none transition-all text-sm font-medium"
                    >
                    <p v-if="form.errors.phone" class="text-xs text-red-500 mt-1">{{ form.errors.phone }}</p>
                </div>

                <button 
                    type="submit" 
                    :disabled="form.processing"
                    class="w-full py-3.5 bg-accent text-white rounded-xl font-bold text-sm shadow-lg shadow-accent/25 hover:opacity-90 transition-all disabled:opacity-50 flex items-center justify-center gap-2"
                >
                    <svg v-if="form.processing" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span v-if="form.processing">Menyimpan...</span>
                    <span v-else>Simpan Perubahan</span>
                </button>
            </form>
        </div>
    </div>
</template>

<style scoped>
.animate-fade-in-up {
    animation: fadeInUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
</style>

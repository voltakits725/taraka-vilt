<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import { useTheme } from '../../../shared/composables/useTheme'

const { initTheme } = useTheme()

const form = useForm({
    email: '',
    password: '',
})

const showPassword = ref(false)
const isLoaded = ref(false)

onMounted(() => {
    initTheme()
    setTimeout(() => isLoaded.value = true, 100)
})

const submit = () => {
    form.post('/admin/login', {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-base text-text-main p-4 transition-colors duration-500">
        
        <!-- Background Decorations (Optional subtle glow) -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-accent/5 blur-[120px]"></div>
            <div class="absolute top-[60%] -right-[10%] w-[40%] h-[50%] rounded-full bg-accent/10 blur-[100px]"></div>
        </div>

        <div 
            class="w-full max-w-md relative z-10 transition-all duration-700 ease-out"
            :class="isLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
        >
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-6">
                    <img src="/images/tarakav2.png" alt="Taraka Admin" class="h-20 md:h-24 w-auto object-contain drop-shadow-md" />
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight">Admin Portal</h1>
                <p class="text-text-muted mt-2 text-sm font-medium">Masuk untuk mengelola Cafe Taraka</p>
            </div>

            <!-- Error Alert -->
            <div v-if="form.errors.email" class="mb-6 p-4 rounded-2xl border border-red-500/20 bg-red-500/10 backdrop-blur-sm animate-pulse">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-sm font-medium text-red-500">{{ form.errors.email }}</p>
                </div>
            </div>

            <!-- Login Card -->
            <div class="bg-surface border border-border-theme rounded-3xl p-8 shadow-2xl backdrop-blur-xl">
                <form @submit.prevent="submit" class="space-y-6">
                    
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold mb-2">Email Address</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-text-muted group-focus-within:text-accent transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </div>
                            <input 
                                v-model="form.email" 
                                type="email" 
                                required
                                placeholder="admin@taraka.com"
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl bg-base border border-border-theme text-text-main placeholder-text-muted focus:border-accent focus:ring-1 focus:ring-accent/50 outline-none transition-all text-sm font-medium"
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-bold mb-2">Password</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-text-muted group-focus-within:text-accent transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input 
                                v-model="form.password" 
                                :type="showPassword ? 'text' : 'password'" 
                                required
                                placeholder="••••••••"
                                class="w-full pl-12 pr-12 py-3.5 rounded-xl bg-base border border-border-theme text-text-main placeholder-text-muted focus:border-accent focus:ring-1 focus:ring-accent/50 outline-none transition-all text-sm font-medium"
                            >
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-text-muted hover:text-text-main transition-colors outline-none"
                            >
                                <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="w-full py-4 bg-accent text-white rounded-xl font-extrabold text-sm shadow-lg shadow-accent/30 hover:shadow-accent/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none flex items-center justify-center gap-2 mt-4"
                    >
                        <svg v-if="form.processing" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span v-if="form.processing">Authenticating...</span>
                        <span v-else>Masuk Portal</span>
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <p class="text-center text-sm text-text-muted mt-8 font-medium">
                Belum punya akun admin? 
                <Link href="/admin/register" class="text-accent hover:underline font-bold transition-colors">Daftar di sini</Link>
            </p>
        </div>
    </div>
</template>
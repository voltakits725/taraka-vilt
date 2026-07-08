<script setup>
import { useForm, Link, usePage } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import CustomerLayout from '../../shared/layouts/CustomerLayout.vue'

const page = usePage()

const form = useForm({
    email: '',
    password: '',
})

const showPassword = ref(false)
const isLoaded = ref(false)

onMounted(() => {
    setTimeout(() => isLoaded.value = true, 100)
})

const submit = () => {
    form.post('/masuk', {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <CustomerLayout>
        <div class="min-h-[calc(100vh-12rem)] flex items-center justify-center py-8 md:py-12">
            
            <!-- Login Card -->
            <div 
                class="w-full max-w-md transition-all duration-700"
                :class="isLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
            >
                <!-- Card Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-surface-hover mb-5 shadow-lg border border-border-theme">
                        <img src="/images/taraka.png" alt="Taraka" class="w-10 h-10 object-contain" />
                    </div>
                    <h1 class="text-3xl font-extrabold text-text-main tracking-tight" style="font-family: 'Cormorant Garamond', Georgia, serif;">
                        Selamat Datang
                    </h1>
                    <p class="text-text-muted mt-2 text-sm font-medium">
                        Masuk ke akunmu untuk melanjutkan pesanan
                    </p>
                </div>

                <!-- Error Alert -->
                <div v-if="form.errors.email" class="mb-6 p-4 rounded-2xl border border-red-500/20 bg-red-500/10 backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-sm font-medium text-red-400">{{ form.errors.email }}</p>
                    </div>
                </div>

                <!-- Login Form Card -->
                <div class="bg-surface border border-border-theme rounded-3xl p-7 md:p-8 shadow-xl">
                    <form @submit.prevent="submit" class="space-y-5">
                        
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-bold text-text-main mb-2">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <input 
                                    v-model="form.email" 
                                    type="email" 
                                    required
                                    placeholder="nama@email.com"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl bg-surface-hover border border-border-theme text-text-main placeholder-text-muted focus:border-accent focus:ring-1 focus:ring-accent/30 outline-none transition-all text-sm font-medium"
                                >
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-bold text-text-main mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <input 
                                    v-model="form.password" 
                                    :type="showPassword ? 'text' : 'password'" 
                                    required
                                    placeholder="Masukkan password"
                                    class="w-full pl-12 pr-12 py-3.5 rounded-xl bg-surface-hover border border-border-theme text-text-main placeholder-text-muted focus:border-accent focus:ring-1 focus:ring-accent/30 outline-none transition-all text-sm font-medium"
                                >
                                <button 
                                    type="button" 
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-text-muted hover:text-text-main transition-colors"
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
                            class="w-full py-4 bg-accent text-white rounded-xl font-bold text-sm shadow-lg shadow-accent/25 hover:opacity-90 hover:-translate-y-0.5 active:translate-y-0 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none flex items-center justify-center gap-2 mt-2"
                        >
                            <svg v-if="form.processing" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span v-if="form.processing">Memproses...</span>
                            <span v-else>Masuk</span>
                        </button>
                        
                        <div class="flex items-center gap-3 justify-center my-4">
                            <div class="h-px flex-1 bg-border-theme"></div>
                            <span class="text-xs text-text-muted font-medium">atau masuk dengan</span>
                            <div class="h-px flex-1 bg-border-theme"></div>
                        </div>

                        <!-- Google Login Button -->
                        <a 
                            href="/auth/google" 
                            class="w-full py-4 bg-surface text-text-main border border-border-theme rounded-xl font-bold text-sm hover:bg-surface-hover hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-3"
                        >
                            <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            Google
                        </a>
                    </form>
                </div>

                <!-- Footer -->
                <div class="text-center mt-6 space-y-3">
                    <p class="text-sm text-text-muted font-medium">
                        Belum punya akun? 
                        <Link href="/daftar" class="text-accent hover:underline font-bold transition-colors">
                            Daftar sekarang
                        </Link>
                    </p>
                    <div class="flex items-center gap-3 justify-center">
                        <div class="h-px flex-1 bg-border-theme max-w-[60px]"></div>
                        <span class="text-xs text-text-muted font-medium">atau</span>
                        <div class="h-px flex-1 bg-border-theme max-w-[60px]"></div>
                    </div>
                    <Link href="/menu" class="inline-flex items-center gap-2 text-sm text-text-muted hover:text-accent font-bold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Menu
                    </Link>
                </div>

            </div>
        </div>
    </CustomerLayout>
</template>

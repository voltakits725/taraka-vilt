<script setup>
import { Link } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import CustomerLayout from '../../shared/layouts/CustomerLayout.vue'

const isLoaded = ref(false)

onMounted(() => {
    setTimeout(() => isLoaded.value = true, 100)
})
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
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-surface-hover mb-6 shadow-xl border border-border-theme">
                        <img src="/images/taraka.png" alt="Taraka" class="w-12 h-12 object-contain" />
                    </div>
                    <h1 class="text-3xl font-extrabold text-text-main tracking-tight" style="font-family: 'Cormorant Garamond', Georgia, serif;">
                        Selamat Datang
                    </h1>
                    <p class="text-text-muted mt-3 text-sm font-medium">
                        Masuk untuk mulai memesan di Cafe Taraka
                    </p>
                </div>

                <!-- Login Form Card -->
                <div class="bg-surface border border-border-theme rounded-3xl p-7 md:p-8 shadow-xl text-center">
                    
                    <p class="text-sm font-bold text-text-main mb-6">Lanjutkan dengan akun kamu</p>

                    <!-- Google Login Button -->
                    <a 
                        href="/auth/google" 
                        class="w-full py-4 bg-surface text-text-main border border-border-theme rounded-2xl font-bold text-sm shadow-sm hover:bg-surface-hover hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-3"
                    >
                        <svg class="w-6 h-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Lanjutkan dengan Google
                    </a>
                </div>

                <!-- Footer -->
                <div class="text-center mt-8 space-y-4">
                    <Link href="/menu" class="inline-flex items-center gap-2 text-sm text-text-muted hover:text-accent font-bold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Menu
                    </Link>
                </div>

            </div>
        </div>
    </CustomerLayout>
</template>

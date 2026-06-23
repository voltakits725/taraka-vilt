<!-- resources/js/widgets/navbar/ui/TopNavbar.vue -->
<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useTheme } from '../../../shared/composables/useTheme'
import NotificationList from '../../../features/notification/ui/NotificationList.vue'

defineProps({
    isHome: Boolean,
    isScrolled: Boolean
})

const { currentTheme } = useTheme()
const page = usePage()
const showUserMenu = ref(false)

const handleLogout = () => {
    const user = page.props.auth?.user
    if (user?.role === 'admin') {
        router.post('/logout')
    } else {
        router.post('/customer/logout')
    }
}
</script>

<template>
    <header class="h-20 fixed top-0 w-full z-50 flex items-center justify-between px-6 md:px-12 bg-surface border-b border-border-theme shadow-sm transition-colors duration-500">

        <!-- Logo + Brand Name -->
        <Link href="/" class="group flex items-center gap-3 hover:opacity-90 transition-opacity duration-300">

            <!-- Logo Image -->
            <img
                src="/images/taraka.png"
                alt="Taraka Logo"
                class="h-10 w-10 object-contain select-none"
                draggable="false"
            >

            <!-- Brand Name — Cormorant Garamond -->
            <span class="font-brand text-text-main leading-none select-none" style="font-family: 'Cormorant Garamond', Georgia, serif;">
                <span class="text-[28px] md:text-[32px] font-semibold tracking-wide">Taraka</span><span class="text-accent text-[28px] md:text-[32px] font-light">.</span>
            </span>
        </Link>

        <!-- Navigasi Tengah -->
        <nav class="hidden md:flex items-center gap-8 font-bold text-sm">
            <Link href="/" :class="page.url === '/' ? 'text-accent' : 'text-text-muted hover:text-text-main transition-colors'">Beranda</Link>
            <Link href="/menu" :class="page.url.startsWith('/menu') ? 'text-accent' : 'text-text-muted hover:text-text-main transition-colors'">Katalog Menu</Link>
            <Link href="/reservasi" :class="page.url.startsWith('/reservasi') ? 'text-accent' : 'text-text-muted hover:text-text-main transition-colors'">Booking Meja</Link>
            <Link href="/ai-chat" class="flex items-center gap-2 text-text-muted hover:text-accent transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Tanya AI
            </Link>
        </nav>

        <!-- Menu Kanan -->
        <div class="flex items-center gap-4 md:gap-5">
            <div v-if="$page.props.activeTable" class="hidden md:flex items-center gap-2 px-4 py-2 rounded-full border bg-orange-500/10 border-orange-500/30">
                <span class="text-xs font-bold text-orange-600">Meja {{ $page.props.activeTable }}</span>
            </div>

            <Link href="/theme" class="hidden md:flex items-center gap-2 px-4 py-2 rounded-full border bg-surface-hover border-border-theme hover:border-accent transition-colors">
                <div class="w-4 h-4 rounded-full shadow-sm" :style="{ backgroundColor: currentTheme.colors.accent }"></div>
                <span class="text-xs font-bold text-text-muted">Tampilan</span>
            </Link>

            <Link href="/cart" class="relative p-2 text-text-main hover:text-accent transition-colors">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span v-if="$page.props.cartCount > 0" class="absolute top-0 right-0 w-5 h-5 bg-accent text-white flex items-center justify-center text-[10px] font-bold rounded-full border-2 border-surface">
                    {{ $page.props.cartCount }}
                </span>
            </Link>

            <template v-if="$page.props.auth?.user">
                <Link href="/riwayat-pesanan" class="hidden md:flex p-2 text-text-main hover:text-accent transition-colors" title="Riwayat Pesanan">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                </Link>
                
                <NotificationList :userId="$page.props.auth.user.id" />
            </template>

            <!-- Auth: User Menu / Login Button -->
            <template v-if="$page.props.auth?.user">
                <div class="relative">
                    <button 
                        @click="showUserMenu = !showUserMenu"
                        class="hidden md:flex items-center gap-2.5 px-4 py-2 rounded-full border bg-surface-hover border-border-theme hover:border-accent transition-all"
                    >
                        <div class="w-7 h-7 rounded-full bg-accent/20 flex items-center justify-center">
                            <span class="text-xs font-bold text-accent">{{ $page.props.auth.user.name.charAt(0).toUpperCase() }}</span>
                        </div>
                        <span class="text-xs font-bold text-text-main max-w-[100px] truncate">{{ $page.props.auth.user.name }}</span>
                        <svg class="w-3.5 h-3.5 text-text-muted transition-transform" :class="showUserMenu ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <!-- Dropdown -->
                    <Transition
                        enter-active-class="transition duration-200 ease-out"
                        enter-from-class="opacity-0 scale-95 -translate-y-1"
                        enter-to-class="opacity-100 scale-100 translate-y-0"
                        leave-active-class="transition duration-150 ease-in"
                        leave-from-class="opacity-100 scale-100 translate-y-0"
                        leave-to-class="opacity-0 scale-95 -translate-y-1"
                    >
                        <div 
                            v-if="showUserMenu" 
                            class="absolute right-0 top-full mt-2 w-56 bg-surface border border-border-theme rounded-2xl shadow-xl overflow-hidden z-50"
                        >
                            <div class="p-4 border-b border-border-theme">
                                <p class="font-bold text-sm text-text-main truncate">{{ $page.props.auth.user.name }}</p>
                                <p class="text-xs text-text-muted mt-0.5 truncate">{{ $page.props.auth.user.email }}</p>
                            </div>
                            <div class="p-2">
                                <button 
                                    @click="handleLogout" 
                                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-red-400 hover:bg-red-500/10 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Keluar
                                </button>
                            </div>
                        </div>
                    </Transition>

                    <!-- Backdrop to close dropdown -->
                    <div v-if="showUserMenu" @click="showUserMenu = false" class="fixed inset-0 z-40"></div>
                </div>
            </template>

            <!-- Guest: Login Button -->
            <template v-else>
                <Link href="/masuk" class="hidden md:flex items-center gap-2 px-5 py-2.5 rounded-full bg-accent text-white text-xs font-bold hover:opacity-90 hover:-translate-y-0.5 transition-all shadow-md shadow-accent/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    Masuk
                </Link>
            </template>
        </div>
    </header>
</template>
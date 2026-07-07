<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { useTheme } from '../../../shared/composables/useTheme'

defineProps({
    show: Boolean
})

const emit = defineEmits(['close'])

const { currentTheme } = useTheme()
const page = usePage()

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
    <!-- Backdrop -->
    <Transition
        enter-active-class="transition-opacity ease-linear duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity ease-linear duration-300"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="show" @click="$emit('close')" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] md:hidden"></div>
    </Transition>

    <!-- Sidebar Panel -->
    <Transition
        enter-active-class="transition ease-in-out duration-300 transform"
        enter-from-class="-translate-x-full"
        enter-to-class="translate-x-0"
        leave-active-class="transition ease-in-out duration-300 transform"
        leave-from-class="translate-x-0"
        leave-to-class="-translate-x-full"
    >
        <div v-if="show" class="fixed inset-y-0 left-0 w-4/5 max-w-sm bg-surface z-[70] shadow-2xl flex flex-col md:hidden border-r border-border-theme overflow-y-auto">
            
            <!-- Header -->
            <div class="p-6 flex items-center justify-between border-b border-border-theme bg-surface-hover/50">
                <div class="flex items-center gap-3">
                    <img src="/images/taraka.png" alt="Taraka Logo" class="h-8 w-8 object-contain">
                    <span class="font-brand text-text-main text-2xl font-bold tracking-wide" style="font-family: 'Cormorant Garamond', Georgia, serif;">
                        Taraka<span class="text-accent">.</span>
                    </span>
                </div>
                <button @click="$emit('close')" class="p-2 text-text-muted hover:text-text-main transition-colors rounded-full hover:bg-black/5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Profile Area (if logged in) -->
            <div v-if="$page.props.auth?.user" class="p-6 border-b border-border-theme">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-accent/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-lg font-bold text-accent">{{ $page.props.auth.user.name.charAt(0).toUpperCase() }}</span>
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <h3 class="font-bold text-text-main text-base truncate">{{ $page.props.auth.user.name }}</h3>
                        <p class="text-xs text-text-muted truncate">{{ $page.props.auth.user.email }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Links -->
            <div class="flex-1 py-4 px-3 flex flex-col gap-1">
                <Link href="/" @click="$emit('close')" :class="['flex items-center gap-4 px-4 py-3 rounded-xl transition-colors font-bold text-sm', page.url === '/' ? 'bg-accent/10 text-accent' : 'text-text-main hover:bg-surface-hover']">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Beranda
                </Link>
                
                <Link href="/menu" @click="$emit('close')" :class="['flex items-center gap-4 px-4 py-3 rounded-xl transition-colors font-bold text-sm', page.url.startsWith('/menu') ? 'bg-accent/10 text-accent' : 'text-text-main hover:bg-surface-hover']">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253"></path>
                    </svg>
                    Katalog Menu
                </Link>

                <Link href="/reservasi" @click="$emit('close')" :class="['flex items-center gap-4 px-4 py-3 rounded-xl transition-colors font-bold text-sm', page.url.startsWith('/reservasi') ? 'bg-accent/10 text-accent' : 'text-text-main hover:bg-surface-hover']">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    Booking Meja
                </Link>

                <Link href="/ai-chat" @click="$emit('close')" :class="['flex items-center gap-4 px-4 py-3 rounded-xl transition-colors font-bold text-sm', page.url.startsWith('/ai-chat') ? 'bg-accent/10 text-accent' : 'text-text-main hover:bg-surface-hover']">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Tanya AI
                </Link>

                <div class="h-px bg-border-theme my-2 mx-4"></div>

                <Link v-if="$page.props.auth?.user" href="/riwayat-pesanan" @click="$emit('close')" :class="['flex items-center gap-4 px-4 py-3 rounded-xl transition-colors font-bold text-sm', page.url === '/riwayat-pesanan' ? 'bg-accent/10 text-accent' : 'text-text-main hover:bg-surface-hover']">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"></path>
                    </svg>
                    Riwayat Pesanan
                </Link>

                <Link v-if="$page.props.auth?.user" href="/riwayat-reservasi" @click="$emit('close')" :class="['flex items-center gap-4 px-4 py-3 rounded-xl transition-colors font-bold text-sm', page.url === '/riwayat-reservasi' ? 'bg-accent/10 text-accent' : 'text-text-main hover:bg-surface-hover']">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Riwayat Booking
                </Link>

                <Link href="/theme" @click="$emit('close')" :class="['flex items-center gap-4 px-4 py-3 rounded-xl transition-colors font-bold text-sm', page.url.startsWith('/theme') ? 'bg-accent/10 text-accent' : 'text-text-main hover:bg-surface-hover']">
                    <div class="w-5 h-5 flex items-center justify-center">
                        <div class="w-4 h-4 rounded-full border-[2px] border-current" :style="{ backgroundColor: currentTheme.colors.accent }"></div>
                    </div>
                    Ganti Tema
                </Link>

                <Link v-if="$page.props.auth?.user" href="/profil" @click="$emit('close')" :class="['flex items-center gap-4 px-4 py-3 rounded-xl transition-colors font-bold text-sm', page.url.startsWith('/profil') ? 'bg-accent/10 text-accent' : 'text-text-main hover:bg-surface-hover']">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profil Akun
                </Link>
            </div>

            <!-- Footer / Auth Action -->
            <div class="p-4 border-t border-border-theme mt-auto">
                <button v-if="$page.props.auth?.user" @click="handleLogout" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-red-500 hover:bg-red-500/10 transition-colors justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar
                </button>
                <Link v-else href="/masuk" @click="$emit('close')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-white bg-accent hover:opacity-90 transition-all justify-center shadow-lg shadow-accent/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    Masuk Akun
                </Link>
            </div>

        </div>
    </Transition>
</template>

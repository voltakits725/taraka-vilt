<!-- resources/js/widgets/navbar/ui/BottomNavbar.vue -->
<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { useTheme } from '../../../shared/composables/useTheme'

const { currentTheme } = useTheme()
const page = usePage()
</script>

<template>
    <nav class="md:hidden fixed bottom-0 w-full bg-surface border-t border-border-theme flex justify-around items-center py-2 px-2 z-50 shadow-[0_-4px_10px_-1px_rgba(0,0,0,0.05)] transition-colors duration-500 pb-safe">
        
        <Link href="/" class="flex flex-col items-center gap-1 p-2 w-14 transition-colors" :class="page.url === '/' ? 'text-accent' : 'text-text-muted hover:text-text-main'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="text-[10px] font-bold">Beranda</span>
        </Link>

        <Link href="/menu" class="flex flex-col items-center gap-1 p-2 w-14 transition-colors" :class="page.url.startsWith('/menu') ? 'text-accent' : 'text-text-muted hover:text-text-main'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253"></path>
            </svg>
            <span class="text-[10px] font-bold">Menu</span>
        </Link>

        <Link href="/reservasi" class="flex flex-col items-center gap-1 p-2 w-14 transition-colors" :class="page.url.startsWith('/reservasi') ? 'text-accent' : 'text-text-muted hover:text-text-main'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
            </svg>
            <span class="text-[10px] font-bold">Booking</span>
        </Link>

        <Link href="/ai-chat" class="flex flex-col items-center gap-1 p-2 w-14 transition-colors" :class="page.url.startsWith('/ai-chat') ? 'text-accent' : 'text-text-muted hover:text-text-main'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <span class="text-[10px] font-bold">Tanya AI</span>
        </Link>



        <!-- Akun: Guest → Login, Auth → User Initial -->
        <Link 
            :href="$page.props.auth?.user ? '/profil' : '/masuk'" 
            class="flex flex-col items-center gap-1 p-2 w-14 transition-colors"
            :class="(page.url.startsWith('/masuk') || page.url.startsWith('/daftar') || page.url.startsWith('/profil')) ? 'text-accent' : 'text-text-muted hover:text-text-main'"
        >
            <template v-if="$page.props.auth?.user">
                <div class="w-6 h-6 rounded-full bg-accent/20 flex items-center justify-center">
                    <span class="text-[10px] font-bold text-accent">{{ $page.props.auth.user.name.charAt(0).toUpperCase() }}</span>
                </div>
            </template>
            <template v-else>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </template>
            <span class="text-[10px] font-bold">{{ $page.props.auth?.user ? 'Akun' : 'Masuk' }}</span>
        </Link>

    </nav>
</template>
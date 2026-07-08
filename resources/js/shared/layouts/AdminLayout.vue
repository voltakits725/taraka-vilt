<script setup>
import { ref, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { useTheme } from '../composables/useTheme'

const page = usePage()
const user = page.props.auth?.user || { name: 'Admin' }
const isSidebarOpen = ref(false)

const { initTheme } = useTheme()
onMounted(() => initTheme())
</script>

<template>
    <div class="h-screen flex bg-base text-text-main font-sans overflow-hidden transition-colors duration-500">
        <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-black/50 z-20 md:hidden backdrop-blur-sm transition-opacity"></div>

        <aside :class="['fixed inset-y-0 left-0 z-30 w-64 bg-surface border-r border-border-theme flex flex-col shadow-2xl transition-transform duration-300 ease-in-out md:static md:translate-x-0', isSidebarOpen ? 'translate-x-0' : '-translate-x-full']">
            <div class="h-20 flex items-center px-4 border-b border-border-theme">
                <img src="/images/tarakav2.png" alt="Taraka" class="h-14 w-auto object-contain" />
            </div>

            <div class="px-5 py-4 border-b border-border-theme flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold bg-accent text-white shadow-sm text-lg shrink-0">
                    {{ user.name.charAt(0).toUpperCase() }}
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-sm font-bold text-text-main leading-none truncate">{{ user.name }}</span>
                    <span class="text-[10px] font-semibold text-text-muted uppercase tracking-wider mt-1">{{ user.role }}</span>
                </div>
            </div>
            
            <nav class="flex-1 p-4 space-y-2 mt-4 overflow-y-auto">
                <Link v-if="user.role === 'owner'" href="/admin/dashboard" @click="isSidebarOpen = false" class="block px-4 py-3 rounded-xl font-semibold transition-all" 
                    :class="$page.url === '/admin/dashboard' ? 'bg-accent text-white shadow-md' : 'text-text-muted hover:bg-surface-hover hover:text-text-main'">
                    Dashboard
                </Link>
                <Link href="/admin/orders" @click="isSidebarOpen = false" class="block px-4 py-3 rounded-xl font-semibold transition-all" 
                    :class="$page.url.startsWith('/admin/orders') ? 'bg-accent text-white shadow-md' : 'text-text-muted hover:bg-surface-hover hover:text-text-main'">
                    Pesanan Masuk
                </Link>
                <Link href="/admin/reservations" @click="isSidebarOpen = false" class="block px-4 py-3 rounded-xl font-semibold transition-all" 
                    :class="$page.url.startsWith('/admin/reservations') ? 'bg-accent text-white shadow-md' : 'text-text-muted hover:bg-surface-hover hover:text-text-main'">
                    Booking Meja
                </Link>
                <template v-if="['owner', 'admin'].includes(user.role)">
                    <Link href="/admin/tables" @click="isSidebarOpen = false" class="block px-4 py-3 rounded-xl font-semibold transition-all" 
                        :class="$page.url === '/admin/tables' ? 'bg-accent text-white shadow-md' : 'text-text-muted hover:bg-surface-hover hover:text-text-main'">
                        Master Meja
                    </Link>
                    <Link href="/admin/tables/qr" @click="isSidebarOpen = false" class="block px-4 py-3 rounded-xl font-semibold transition-all" 
                        :class="$page.url.startsWith('/admin/tables/qr') ? 'bg-accent text-white shadow-md' : 'text-text-muted hover:bg-surface-hover hover:text-text-main'">
                        Cetak QR Meja
                    </Link>
                    <Link href="/admin/categories" @click="isSidebarOpen = false" class="block px-4 py-3 rounded-xl font-semibold transition-all" 
                        :class="$page.url.startsWith('/admin/categories') ? 'bg-accent text-white shadow-md' : 'text-text-muted hover:bg-surface-hover hover:text-text-main'">
                        Kategori Menu
                    </Link>
                    <Link href="/admin/menus" @click="isSidebarOpen = false" class="block px-4 py-3 rounded-xl font-semibold transition-all" 
                        :class="$page.url.startsWith('/admin/menus') ? 'bg-accent text-white shadow-md' : 'text-text-muted hover:bg-surface-hover hover:text-text-main'">
                        Master Menu
                    </Link>
                </template>
                <Link href="/admin/ingredients" @click="isSidebarOpen = false" class="block px-4 py-3 rounded-xl font-semibold transition-all" 
                    :class="$page.url.startsWith('/admin/ingredients') ? 'bg-accent text-white shadow-md' : 'text-text-muted hover:bg-surface-hover hover:text-text-main'">
                    Master Bahan
                </Link>

                <div v-if="user.role === 'owner'" class="pt-4 mt-2 border-t border-border-theme">
                    <Link href="/admin/employees" @click="isSidebarOpen = false" class="block px-4 py-3 rounded-xl font-semibold transition-all" 
                        :class="$page.url.startsWith('/admin/employees') ? 'bg-accent text-white shadow-md' : 'text-text-muted hover:bg-surface-hover hover:text-text-main'">
                        Manajemen Karyawan
                    </Link>
                </div>
                
                <div class="pt-4 mt-2 border-t border-border-theme">
                    <Link href="/admin/theme" @click="isSidebarOpen = false" class="block px-4 py-3 rounded-xl font-semibold transition-all" 
                        :class="$page.url.startsWith('/admin/theme') ? 'bg-accent text-white shadow-md' : 'text-text-muted hover:bg-surface-hover hover:text-text-main'">
                        Pengaturan Tampilan
                    </Link>
                </div>
            </nav>

            <div class="p-4 border-t border-border-theme">
                <Link href="/logout" method="post" as="button" class="w-full px-4 py-3 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white rounded-xl font-bold transition-colors text-center">Logout</Link>
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0 bg-base">
            <header class="h-16 bg-surface border-b border-border-theme flex items-center justify-between px-4 sm:px-8 z-10 shrink-0 transition-colors duration-500">
                <div class="flex items-center gap-3">
                    <button @click="isSidebarOpen = true" class="md:hidden text-text-muted hover:text-text-main focus:outline-none p-2 rounded-lg hover:bg-surface-hover transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div class="hidden sm:block text-text-muted text-sm font-medium">Portal Admin Taraka Cafe</div>
                </div>


            </header>
            
            <div class="flex-1 p-4 sm:p-8 overflow-y-auto w-full transition-colors duration-500">
                <slot /> 
            </div>
        </main>
    </div>
</template>
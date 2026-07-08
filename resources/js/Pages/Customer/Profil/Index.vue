<script setup>
import { ref } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'

// FSD Components
import ProfileHeader from '../../../features/Customer/Profil/ui/ProfileHeader.vue'
import ProfileMenu from '../../../features/Customer/Profil/ui/ProfileMenu.vue'
import EditProfileModal from '../../../features/Customer/Profil/ui/EditProfileModal.vue'

const page = usePage()
const user = page.props.auth.user

const showEditModal = ref(false)
</script>

<template>
    <Head title="Profil Saya - Cafe Taraka" />

    <CustomerLayout>
        <div class="max-w-3xl mx-auto pb-24">
            
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-text-main mb-2">Profil Saya</h1>
                <p class="text-text-secondary">Kelola akun dan pengaturan preferensi kamu.</p>
            </div>

            <ProfileHeader :user="user" @edit-profile="showEditModal = true" />
            
            <ProfileMenu />

            <!-- Logout Button -->
            <Link href="/customer/logout" method="post" as="button" class="w-full py-4 bg-surface border-2 border-red-500/20 text-red-500 rounded-3xl font-bold shadow-sm hover:bg-red-50 transition-all text-lg text-center flex items-center justify-center gap-2 group">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Keluar Akun
            </Link>

        </div>

        <EditProfileModal 
            :show="showEditModal" 
            @close="showEditModal = false" 
        />
    </CustomerLayout>
</template>

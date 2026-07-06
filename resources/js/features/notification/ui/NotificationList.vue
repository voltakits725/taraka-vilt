<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'

const props = defineProps({
    userId: Number
})

const notifications = ref([])
const unreadCount = ref(0)
const isOpen = ref(false)

const fetchNotifications = async () => {
    try {
        const response = await axios.get('/notifications')
        notifications.value = response.data.notifications
        
        // Hitung unread count berdasarkan localStorage (seen_at)
        const seenAt = localStorage.getItem(`notifications_seen_${props.userId}`)
        if (seenAt) {
            let realCount = 0
            notifications.value.forEach(n => {
                if (!n.read_at && new Date(n.created_at).getTime() > parseInt(seenAt)) {
                    realCount++
                }
            })
            unreadCount.value = realCount
        } else {
            unreadCount.value = response.data.unread_count
        }
    } catch (error) {
        console.error('Failed to fetch notifications', error)
    }
}

const markAsRead = async (id) => {
    try {
        // Optimistic UI update: hilangkan badge NEW langsung tanpa nunggu backend
        const notif = notifications.value.find(n => n.id === id)
        if (notif && !notif.read_at) {
            notif.read_at = new Date().toISOString()
        }
        
        await axios.post(`/notifications/${id}/read`)
    } catch (error) {
        console.error('Failed to mark as read', error)
    }
}

const toggleDropdown = async () => {
    isOpen.value = !isOpen.value
    if (isOpen.value) {
        // Sembunyikan badge merah dan simpan waktu terakhir dilihat ke localStorage
        if (unreadCount.value > 0) {
            unreadCount.value = 0
            localStorage.setItem(`notifications_seen_${props.userId}`, Date.now().toString())
        }
        
        if (notifications.value.length === 0) {
            fetchNotifications()
        }
    }
}

onMounted(() => {
    fetchNotifications()

    // Minta izin notifikasi OS/Browser
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission()
    }

    // Listen to Reverb Websocket
    if (window.Echo && props.userId) {
        console.log("Mencoba connect ke Websocket untuk User ID:", props.userId);
        
        window.Echo.private(`App.Models.User.${props.userId}`)
            .notification((notification) => {
                console.log("🔥 YEAYYY NOTIFIKASI MASUK DARI WEBSOCKET!!! 🔥", notification);
                
                // Tambah notifikasi baru ke list
                notifications.value.unshift({
                    id: notification.id,
                    data: notification,
                    read_at: null,
                    created_at: new Date().toISOString()
                })
                unreadCount.value++
                
                // Refresh data halaman saat ini (berguna kalau lagi di halaman Status / History)
                router.reload()

                // Munculkan popup (misalnya sweetalert atau toast)
                let title = 'Notifikasi Baru'
                let confirmText = 'Oke'
                
                if (notification.type === 'order') {
                    if (notification.status === 'completed') {
                        title = 'Pesanan Selesai!'
                    } else if (notification.status === 'processing') {
                        title = 'Pesanan Diproses'
                    }
                } else if (notification.type === 'reservation') {
                    if (notification.status === 'confirmed') {
                        title = 'Booking Diterima!'
                    } else if (notification.status === 'cancelled') {
                        title = 'Booking Ditolak'
                    } else if (notification.status === 'completed') {
                        title = 'Sesi Selesai'
                        router.post('/leave-table', {}, { preserveScroll: true })
                    }
                }

                // Kirim push notification bawaan HP / OS
                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification(title, {
                        body: notification.message,
                        icon: '/images/tarakav2.png',
                        requireInteraction: true
                    })
                }
            })
    }
})

onBeforeUnmount(() => {
    if (window.Echo && props.userId) {
        window.Echo.leave(`App.Models.User.${props.userId}`)
    }
})

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
}
</script>

<template>
    <div class="relative">
        <button @click="toggleDropdown" class="p-2 relative text-text-primary hover:text-accent transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
            <span v-if="unreadCount > 0" class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                {{ unreadCount > 9 ? '9+' : unreadCount }}
            </span>
        </button>

        <!-- Dropdown Notifikasi -->
        <div v-if="isOpen" class="absolute right-0 mt-2 w-80 bg-surface rounded-2xl shadow-xl border border-black/5 z-50 overflow-hidden">
            <div class="p-4 border-b border-black/5 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-text-primary">Notifikasi</h3>
            </div>
            
            <div class="max-h-80 overflow-y-auto">
                <div v-if="notifications.length === 0" class="p-6 text-center text-text-muted text-sm">
                    Belum ada notifikasi
                </div>
                
                <div v-for="notif in notifications" :key="notif.id" 
                    @click="markAsRead(notif.id)"
                    class="p-4 border-b border-black/5 cursor-pointer hover:bg-black/5 transition-colors relative">
                    <div class="flex justify-between items-start mb-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-accent">{{ notif.data.status?.toUpperCase() }}</span>
                            <span v-if="!notif.read_at" class="bg-green-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">NEW</span>
                        </div>
                        <span class="text-[10px] text-text-muted">{{ formatDate(notif.created_at) }}</span>
                    </div>
                    <p class="text-sm text-text-primary mb-1">{{ notif.data.message }}</p>
                    <p v-if="notif.data.type === 'order'" class="text-xs text-text-muted">Order: {{ notif.data.midtrans_order_id }}</p>
                    <p v-if="notif.data.type === 'reservation'" class="text-xs text-text-muted">Meja: {{ notif.data.table_number }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'
import Swal from 'sweetalert2' // <-- Import ini
import axios from 'axios'

// AI Feature
const showAiModal = ref(false)
const aiChat = ref([])
const aiInput = ref('')
const isAiTyping = ref(false)

const openAiModal = () => {
    showAiModal.value = true
    if (aiChat.value.length === 0) {
        aiChat.value.push({
            role: 'ai',
            text: `Halo! Ada yang ingin ditanyakan tentang menu ${props.menu.name}? Aku bisa bantu cek apakah menu ini bebas alergi untuk kamu.`
        })
    }
}

const closeAiModal = () => {
    showAiModal.value = false
}

const sendAiMessage = async () => {
    if (!aiInput.value.trim() || isAiTyping.value) return

    const question = aiInput.value
    aiChat.value.push({ role: 'user', text: question })
    aiInput.value = ''
    isAiTyping.value = true

    try {
        const res = await axios.post(`/api/menu/${props.menu.slug}/ask-ai`, {
            question: question
        })
        aiChat.value.push({
            role: 'ai',
            text: res.data.answer,
            recommendations: res.data.recommendations
        })
    } catch (e) {
        aiChat.value.push({
            role: 'ai',
            text: 'Maaf, aku sedang tidak bisa membalas. Coba lagi nanti ya!'
        })
    } finally {
        isAiTyping.value = false
    }
}

const renderMarkdown = (text) => {
    if (!text) return ''
    return text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>')
}

const props = defineProps({
    menu: Object
})

const orderForm = ref({
    slug: props.menu.slug, 
    qty: 1,
    note: ''
})

const totalPrice = computed(() => {
    return props.menu.price * orderForm.value.qty
})

const addToCart = () => {
    router.post('/cart', orderForm.value, {
        preserveScroll: true,
        onSuccess: () => {
            // Baca CSS variable dari tema yang aktif
            const style = getComputedStyle(document.documentElement)
            const bgSurface  = style.getPropertyValue('--bg-surface').trim()
            const textMain   = style.getPropertyValue('--text-main').trim()
            const textMuted  = style.getPropertyValue('--text-muted').trim()
            const accent     = style.getPropertyValue('--color-accent').trim()
            const borderColor = style.getPropertyValue('--border-color').trim()

            Swal.fire({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                background: bgSurface,
                color: textMain,
                html: `
                    <div style="display:flex;align-items:center;gap:14px;padding:2px 0;">
                        <img
                            src="/images/taraka.png"
                            style="width:40px;height:40px;object-fit:contain;border-radius:10px;flex-shrink:0;"
                            alt="Taraka"
                        />
                        <div style="text-align:left;line-height:1.4;">
                            <div style="font-weight:800;font-size:15px;color:${textMain};font-family:'Cormorant Garamond',Georgia,serif;letter-spacing:0.02em;">
                                Masuk Keranjang! 🛍️
                            </div>
                            <div style="font-size:12.5px;color:${textMuted};margin-top:3px;font-weight:500;">
                                ${orderForm.value.qty}x ${props.menu.name} berhasil ditambahkan.
                            </div>
                        </div>
                    </div>
                `,
                didOpen: (popup) => {
                    // Progress bar warna accent tema
                    const bar = popup.querySelector('.swal2-timer-progress-bar')
                    if (bar) bar.style.background = accent

                    // Styling container sesuai tema
                    popup.style.border        = `1px solid ${borderColor}`
                    popup.style.boxShadow     = `0 8px 30px rgba(0,0,0,0.25), 0 0 0 1px ${accent}22`
                    popup.style.borderRadius  = '18px'
                    popup.style.padding       = '14px 20px'
                    popup.style.minWidth      = '300px'
                },
            })
        }
    })
}

</script><template>
    <CustomerLayout>
        <!-- Tombol Kembali -->
        <div class="mb-4 md:mb-8">
            <Link href="/menu" class="inline-flex items-center gap-2 text-text-muted hover:text-accent transition-colors font-bold text-sm bg-surface py-2 px-4 rounded-full border border-border-theme w-fit shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Katalog
            </Link>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 lg:gap-16">

            <!-- Gambar: lebih pendek di mobile, sticky hanya di desktop -->
            <div class="w-full lg:w-1/2 flex items-center">
                <div class="lg:sticky lg:top-28 w-full aspect-[3/2] sm:aspect-[4/3] lg:aspect-square rounded-2xl md:rounded-[32px] lg:rounded-[40px] overflow-hidden bg-surface-hover border-2 lg:border-4 border-surface shadow-xl lg:shadow-2xl">
                    <img v-if="menu.image" :src="menu.image" :alt="menu.name" class="w-full h-full object-cover hover:scale-110 transition-transform duration-700">
                    <div v-else class="w-full h-full flex items-center justify-center text-text-muted font-bold text-xl">No Image</div>
                </div>
            </div>

            <!-- Konten -->
            <div class="w-full lg:w-1/2 flex flex-col pb-28 md:pb-4 lg:pb-0">

                <!-- Info Menu -->
                <div class="mb-6 pb-6 border-b border-border-theme">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-accent/10 text-accent rounded-full text-xs font-black uppercase tracking-widest mb-3 border border-accent/20">
                        {{ menu.category?.name || 'Spesial' }}
                    </div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-text-main mb-3 leading-tight tracking-tight">{{ menu.name }}</h1>
                    <div class="text-2xl sm:text-3xl font-extrabold text-text-main drop-shadow-sm">Rp {{ menu.price.toLocaleString('id-ID') }}</div>
                    <p v-if="menu.description" class="text-text-muted font-medium text-base leading-relaxed mt-4">{{ menu.description }}</p>
                    
                    <button @click="openAiModal" class="mt-5 inline-flex w-full sm:w-auto items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-500/10 to-pink-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20 rounded-xl text-sm font-bold hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        Tanya AI (Bahan & Alergen)
                    </button>
                </div>

                <!-- Catatan -->
                <div class="space-y-6">
                    <div>
                        <h4 class="text-xs font-bold text-text-main mb-3 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Catatan Tambahan (Opsional)
                        </h4>
                        <textarea v-model="orderForm.note" rows="3" placeholder="Contoh: Jangan pakai sedotan plastik ya kak..."
                            class="w-full p-4 bg-surface-hover border border-border-theme rounded-2xl text-text-main text-sm focus:border-accent focus:ring-1 outline-none resize-none transition-all shadow-sm"></textarea>
                    </div>
                </div>

                <!-- Desktop/Tablet Bar: di dalam kolom konten, lebar ikut kolom -->
                <div class="hidden md:flex items-center gap-4 mt-8">
                    <div class="flex items-center bg-surface-hover border border-border-theme rounded-full p-1.5 flex-shrink-0">
                        <button @click="orderForm.qty > 1 && orderForm.qty--" class="w-11 h-11 rounded-full bg-surface shadow-sm flex items-center justify-center text-text-main hover:text-accent transition-colors font-bold text-xl">-</button>
                        <span class="w-12 text-center font-black text-text-main text-lg">{{ orderForm.qty }}</span>
                        <button @click="orderForm.qty++" class="w-11 h-11 rounded-full bg-surface shadow-sm flex items-center justify-center text-text-main hover:text-accent transition-colors font-bold text-xl">+</button>
                    </div>
                    <button @click="addToCart" class="flex-1 py-4 bg-accent text-white rounded-full font-bold shadow-xl shadow-accent/30 hover:scale-[1.02] hover:opacity-90 transition-all flex items-center justify-center gap-2 text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Tambah • Rp {{ totalPrice.toLocaleString('id-ID') }}
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Fixed Bar: di atas BottomNavbar (bottom-16 = 64px, menghindari z-50 BottomNavbar) -->
        <div class="md:hidden fixed bottom-16 left-0 right-0 z-40 bg-surface/95 backdrop-blur-md border-t border-border-theme px-4 py-3">
            <div class="flex items-center gap-3 max-w-7xl mx-auto">
                <div class="flex items-center bg-surface-hover border border-border-theme rounded-full p-1 flex-shrink-0">
                    <button @click="orderForm.qty > 1 && orderForm.qty--" class="w-10 h-10 rounded-full bg-surface shadow-sm flex items-center justify-center text-text-main hover:text-accent transition-colors font-bold text-xl">-</button>
                    <span class="w-10 text-center font-black text-text-main text-lg">{{ orderForm.qty }}</span>
                    <button @click="orderForm.qty++" class="w-10 h-10 rounded-full bg-surface shadow-sm flex items-center justify-center text-text-main hover:text-accent transition-colors font-bold text-xl">+</button>
                </div>
                <button @click="addToCart" class="flex-1 py-3 bg-accent text-white rounded-full font-bold shadow-xl shadow-accent/30 hover:opacity-90 transition-all flex items-center justify-center gap-2 text-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Tambah • Rp {{ totalPrice.toLocaleString('id-ID') }}
                </button>
            </div>
        </div>

    </CustomerLayout>

    <!-- AI Modal -->
    <div v-if="showAiModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeAiModal"></div>
        
        <div class="relative w-full max-w-lg bg-surface border border-border-theme rounded-[32px] overflow-hidden flex flex-col h-[85vh] md:h-[600px] shadow-2xl">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-border-theme flex items-center justify-between bg-surface/80 backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-accent/10 flex items-center justify-center text-accent">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-text-main text-sm">Taraka AI</h3>
                        <p class="text-[11px] text-accent font-bold">Menu Assistant</p>
                    </div>
                </div>
                <button @click="closeAiModal" class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-hover text-text-muted hover:text-red-500 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Chat Area -->
            <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-base">
                <div v-for="(msg, i) in aiChat" :key="i" :class="['flex', msg.role === 'user' ? 'justify-end' : 'justify-start']">
                    <div :class="['max-w-[85%] rounded-2xl px-4 py-3 text-sm leading-relaxed', msg.role === 'user' ? 'bg-accent text-white rounded-br-sm shadow-md shadow-accent/20' : 'bg-surface border border-border-theme text-text-main rounded-bl-sm shadow-sm']">
                        <div v-html="renderMarkdown(msg.text)"></div>
                        
                        <!-- Rekomendasi Menu -->
                        <div v-if="msg.recommendations && msg.recommendations.length > 0" class="mt-3 pt-3 border-t border-border-theme/50 space-y-2">
                            <p class="text-xs font-bold text-accent">Rekomendasi Menu Aman:</p>
                            <div v-for="rec in msg.recommendations" :key="rec.id" class="flex items-center gap-3 bg-surface-hover p-2 rounded-xl border border-border-theme">
                                <img v-if="rec.image" :src="rec.image" class="w-12 h-12 rounded-lg object-cover">
                                <div v-else class="w-12 h-12 rounded-lg bg-surface flex items-center justify-center border border-border-theme text-[10px]">No Img</div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-bold text-text-main truncate">{{ rec.name }}</div>
                                    <div class="text-[10px] text-text-muted font-medium mt-0.5">Rp {{ rec.price.toLocaleString('id-ID') }}</div>
                                </div>
                                <Link :href="`/menu/${rec.slug}`" class="px-2.5 py-1.5 bg-accent text-white text-[10px] font-bold rounded-lg hover:scale-105 transition-transform shrink-0">Lihat</Link>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div v-if="isAiTyping" class="flex justify-start">
                    <div class="bg-surface border border-border-theme rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-text-muted rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-text-muted rounded-full animate-bounce" style="animation-delay: 0.15s"></span>
                        <span class="w-1.5 h-1.5 bg-text-muted rounded-full animate-bounce" style="animation-delay: 0.3s"></span>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-surface border-t border-border-theme">
                <form @submit.prevent="sendAiMessage" class="flex items-center gap-2">
                    <input type="text" v-model="aiInput" :disabled="isAiTyping" placeholder="Tanya tentang alergen kacang, susu..." class="flex-1 bg-surface-hover border border-border-theme rounded-xl px-4 py-3 text-sm text-text-main focus:outline-none focus:border-accent focus:ring-1 transition-all">
                    <button type="submit" :disabled="!aiInput.trim() || isAiTyping" class="w-11 h-11 shrink-0 rounded-xl bg-accent text-white flex items-center justify-center hover:opacity-90 disabled:opacity-50 transition-all shadow-md shadow-accent/20">
                        <svg class="w-4 h-4 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
@supports (padding-bottom: env(safe-area-inset-bottom)) {
    .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
}
</style>

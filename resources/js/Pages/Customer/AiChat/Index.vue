<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import TopNavbar from '../../../widgets/navbar/ui/TopNavbar.vue'
import BottomNavbar from '../../../widgets/navbar/ui/BottomNavbar.vue'
import axios from 'axios'
import Swal from 'sweetalert2'

import { useTheme } from '../../../shared/composables/useTheme'

const page = usePage()
const { initTheme } = useTheme()

const conversations = ref([])
const activeConversationId = ref(null)
const messages = ref([])
const aiInput = ref('')
const isTyping = ref(false)
const chatContainer = ref(null)

const menuIdContext = new URLSearchParams(window.location.search).get('menu_id')

const loadConversations = async () => {
    try {
        const res = await axios.get('/api/ai/conversations')
        conversations.value = res.data
    } catch (e) {
        console.error("Gagal memuat riwayat", e)
    }
}

const loadMessages = async (id) => {
    try {
        const res = await axios.get(`/api/ai/conversations/${id}`)
        messages.value = res.data.map(msg => {
            return {
                role: msg.role === 'user' ? 'user' : 'ai',
                text: msg.content
            }
        })
        activeConversationId.value = id
        scrollToBottom()
    } catch (e) {
        console.error("Gagal memuat pesan", e)
    }
}

const selectConversation = (id) => {
    if (activeConversationId.value === id) return
    loadMessages(id)
}

const startNewChat = () => {
    activeConversationId.value = null
    messages.value = [{
        role: 'ai',
        text: 'Halo! Aku AI Taraka. Ada yang bisa aku bantu seputar menu, alergen, atau rekomendasi?'
    }]
}

const scrollToBottom = () => {
    nextTick(() => {
        if (chatContainer.value) {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight
        }
    })
}

// Very basic markdown to html wrapper (you can replace with marked.js later)
const renderMarkdown = (text) => {
    if (!text) return ''
    let html = text.replace(/!\[([^\]]*)\]\(([^)]+)\)/g, '<img src="$2" alt="$1" class="w-full max-w-[260px] sm:max-w-[320px] aspect-[4/3] object-cover rounded-xl shadow-sm my-3 border border-border-theme bg-surface" />')
    html = html.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" class="inline-block mt-2 px-4 py-2 bg-accent/10 text-accent border border-accent/20 rounded-lg text-xs font-bold hover:bg-accent hover:text-white transition-colors">$1</a>')
    html = html.replace(/\n\n/g, '</p><p>')
    html = html.replace(/\n/g, '<br>')
    return `<p>${html}</p>`
}

const sendMessage = async () => {
    if (!aiInput.value.trim() || isTyping.value) return

    const question = aiInput.value
    messages.value.push({ role: 'user', text: question })
    aiInput.value = ''
    isTyping.value = true
    scrollToBottom()

    try {
        const payload = {
            question: question,
            conversation_id: activeConversationId.value
        }
        
        if (!activeConversationId.value && menuIdContext) {
            payload.menu_id = menuIdContext
        }

        const res = await axios.post('/api/ai/ask', payload)
        
        messages.value.push({
            role: 'ai',
            text: res.data.answer
        })
        
        if (res.data.conversation_id && res.data.conversation_id !== activeConversationId.value) {
            activeConversationId.value = res.data.conversation_id
            loadConversations() 
        }
        
    } catch (e) {
        messages.value.push({
            role: 'ai',
            text: 'Maaf, terjadi kesalahan saat menghubungi server AI.'
        })
    } finally {
        isTyping.value = false
        scrollToBottom()
    }
}

onMounted(() => {
    initTheme()
    loadConversations()
    startNewChat()
})
</script>

<template>
    <div class="h-[100dvh] bg-base flex flex-col overflow-hidden" style="color: var(--text-main)">
        <TopNavbar :is-home="false" :is-scrolled="true" />

        <main class="flex-1 w-full pt-20 flex flex-col min-h-0">
            <div class="w-full flex-1 flex bg-surface border-t border-border-theme overflow-hidden relative">
                
                <!-- Sidebar (Riwayat) -->
                <div class="w-1/3 max-w-[320px] border-r border-border-theme bg-surface-hover/30 flex-col hidden md:flex shrink-0">
                <div class="p-4 border-b border-border-theme shrink-0">
                    <button @click="startNewChat" class="w-full py-3 px-4 bg-accent text-white rounded-xl font-bold text-sm flex items-center justify-center gap-2 hover:opacity-90 transition-opacity shadow-md hover:scale-[1.02]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Obrolan Baru
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-3 space-y-2">
                    <div v-if="conversations.length === 0" class="text-center text-text-muted text-xs p-4 bg-surface rounded-xl border border-border-theme/50">
                        Belum ada riwayat obrolan
                    </div>
                    <button 
                        v-for="conv in conversations" 
                        :key="conv.id" 
                        @click="selectConversation(conv.id)"
                        :class="['w-full text-left p-3.5 rounded-xl transition-all text-sm flex items-center gap-3', activeConversationId === conv.id ? 'bg-accent/10 border-accent/30 text-accent font-bold shadow-sm' : 'text-text-main hover:bg-surface-hover border border-transparent']"
                    >
                        <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        <span class="truncate">{{ conv.title || 'Obrolan Tanpa Judul' }}</span>
                    </button>
                </div>
            </div>

            <!-- Area Chat -->
            <div class="flex-1 flex flex-col bg-surface min-w-0">
                <!-- Mobile Header for Chat -->
                <div class="md:hidden flex items-center justify-between p-4 border-b border-border-theme shrink-0 bg-surface">
                    <span class="font-bold text-text-main flex items-center gap-2">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Taraka AI
                    </span>
                    <button @click="startNewChat" class="text-xs bg-accent text-white px-3 py-2 rounded-lg font-bold flex items-center gap-1 shadow-md">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Baru
                    </button>
                </div>

                <!-- Messages -->
                <div ref="chatContainer" class="flex-1 overflow-y-auto p-4 md:p-8 space-y-6">
                    <div v-for="(msg, i) in messages" :key="i" :class="['flex', msg.role === 'user' ? 'justify-end' : 'justify-start']">
                        <div :class="['max-w-[85%] md:max-w-[80%] rounded-[24px] p-5 text-[15px] leading-relaxed shadow-sm', msg.role === 'user' ? 'bg-accent text-white rounded-br-sm' : 'bg-surface-hover/50 border border-border-theme text-text-main rounded-bl-sm markdown-body']" 
                             v-html="msg.role === 'ai' ? renderMarkdown(msg.text) : msg.text">
                        </div>
                    </div>
                    
                    <div v-if="isTyping" class="flex justify-start">
                        <div class="bg-surface border border-border-theme text-text-main rounded-[24px] rounded-bl-sm p-5 text-sm flex items-center gap-2.5 shadow-sm">
                            <span class="w-2 h-2 bg-accent rounded-full animate-bounce"></span>
                            <span class="w-2 h-2 bg-accent rounded-full animate-bounce" style="animation-delay: 0.15s"></span>
                            <span class="w-2 h-2 bg-accent rounded-full animate-bounce" style="animation-delay: 0.3s"></span>
                        </div>
                    </div>
                </div>

                <!-- Input Box -->
                <div class="p-4 border-t border-border-theme bg-surface/90 backdrop-blur-md shrink-0">
                    <form @submit.prevent="sendMessage" class="flex items-center gap-3 w-full">
                        <input type="text" v-model="aiInput" :disabled="isTyping" placeholder="Tanya sesuatu tentang menu atau bahan makanan..." class="flex-1 bg-surface-hover border border-border-theme rounded-full px-6 py-4 text-[15px] text-text-main focus:outline-none focus:border-accent focus:ring-1 transition-all shadow-inner">
                        <button type="submit" :disabled="!aiInput.trim() || isTyping" class="shrink-0 w-14 h-14 rounded-full bg-accent text-white flex items-center justify-center hover:opacity-90 hover:scale-105 disabled:opacity-50 disabled:hover:scale-100 transition-all shadow-lg shadow-accent/20">
                            <svg class="w-6 h-6 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                </div>
                
            </div>
            </div>
        </main>
        
        <BottomNavbar />
    </div>
</template>

<style>
.markdown-body {
    word-break: break-word;
}
.markdown-body p {
    margin-bottom: 0.75rem;
}
.markdown-body p:last-child {
    margin-bottom: 0;
}
.markdown-body strong {
    font-weight: 800;
    color: var(--text-main);
}
</style>

<script setup>
import { ref, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import axios from 'axios'

const props = defineProps({
    show: Boolean,
    menu: Object
})
const emit = defineEmits(['close'])

const aiChat = ref([])
const aiInput = ref('')
const isAiTyping = ref(false)

// Init chat when opened
watch(() => props.show, (isOpen) => {
    if (isOpen && aiChat.value.length === 0) {
        aiChat.value.push({
            role: 'ai',
            text: `Halo! Ada yang ingin ditanyakan tentang menu ${props.menu?.name}? Aku bisa bantu cek apakah menu ini bebas alergi untuk kamu.`
        })
    }
})

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
</script>
<template>
    <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="$emit('close')"></div>
        
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
                <button @click="$emit('close')" class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-hover text-text-muted hover:text-red-500 hover:bg-red-50 transition-colors">
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

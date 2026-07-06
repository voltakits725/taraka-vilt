<script setup>
const qty = defineModel('qty', { type: Number, default: 1 })
const note = defineModel('note', { type: String, default: '' })

defineProps({
    totalPrice: Number
})
defineEmits(['add-to-cart'])
</script>
<template>
    <!-- Catatan -->
    <div class="space-y-6">
        <div>
            <h4 class="text-xs font-bold text-text-main mb-3 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Catatan Tambahan (Opsional)
            </h4>
            <textarea v-model="note" rows="3" placeholder="Contoh: Jangan pakai sedotan plastik ya kak..."
                class="w-full p-4 bg-surface-hover border border-border-theme rounded-2xl text-text-main text-sm focus:border-accent focus:ring-1 outline-none resize-none transition-all shadow-sm"></textarea>
        </div>
    </div>

    <!-- Desktop/Tablet Bar: di dalam kolom konten, lebar ikut kolom -->
    <div class="hidden md:flex items-center gap-4 mt-8">
        <div class="flex items-center bg-surface-hover border border-border-theme rounded-full p-1.5 flex-shrink-0">
            <button @click="qty > 1 && qty--" class="w-11 h-11 rounded-full bg-surface shadow-sm flex items-center justify-center text-text-main hover:text-accent transition-colors font-bold text-xl">-</button>
            <span class="w-12 text-center font-black text-text-main text-lg">{{ qty }}</span>
            <button @click="qty++" class="w-11 h-11 rounded-full bg-surface shadow-sm flex items-center justify-center text-text-main hover:text-accent transition-colors font-bold text-xl">+</button>
        </div>
        <button @click="$emit('add-to-cart')" class="flex-1 py-4 bg-accent text-white rounded-full font-bold shadow-xl shadow-accent/30 hover:scale-[1.02] hover:opacity-90 transition-all flex items-center justify-center gap-2 text-base">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            Tambah • Rp {{ totalPrice?.toLocaleString('id-ID') }}
        </button>
    </div>

    <!-- Mobile Fixed Bar: di atas BottomNavbar -->
    <Teleport to="body">
        <div class="md:hidden fixed bottom-16 left-0 right-0 z-40 bg-surface/95 backdrop-blur-md border-t border-border-theme px-4 py-3">
            <div class="flex items-center gap-3 max-w-7xl mx-auto">
                <div class="flex items-center bg-surface-hover border border-border-theme rounded-full p-1 flex-shrink-0">
                    <button @click="qty > 1 && qty--" class="w-10 h-10 rounded-full bg-surface shadow-sm flex items-center justify-center text-text-main hover:text-accent transition-colors font-bold text-xl">-</button>
                    <span class="w-10 text-center font-black text-text-main text-lg">{{ qty }}</span>
                    <button @click="qty++" class="w-10 h-10 rounded-full bg-surface shadow-sm flex items-center justify-center text-text-main hover:text-accent transition-colors font-bold text-xl">+</button>
                </div>
                <button @click="$emit('add-to-cart')" class="flex-1 py-3 bg-accent text-white rounded-full font-bold shadow-xl shadow-accent/30 hover:opacity-90 transition-all flex items-center justify-center gap-2 text-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Tambah • Rp {{ totalPrice?.toLocaleString('id-ID') }}
                </button>
            </div>
        </div>
    </Teleport>
</template>

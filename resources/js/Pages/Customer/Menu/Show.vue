<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'
import Swal from 'sweetalert2'

// FSD Components
import MenuDetailImage from '../../../features/Customer/Menu/ui/MenuDetailImage.vue'
import MenuDetailInfo from '../../../features/Customer/Menu/ui/MenuDetailInfo.vue'
import MenuDetailAction from '../../../features/Customer/Menu/ui/MenuDetailAction.vue'
import AiAssistantModal from '../../../features/Customer/Menu/ui/AiAssistantModal.vue'

const props = defineProps({
    menu: Object
})

const showAiModal = ref(false)

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

</script>

<template>
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
            <MenuDetailImage :menu="menu" />

            <!-- Konten -->
            <div class="w-full lg:w-1/2 flex flex-col pb-28 md:pb-4 lg:pb-0">

                <!-- Info Menu -->
                <MenuDetailInfo :menu="menu" @open-ai="showAiModal = true" />

                <!-- Aksi (Catatan & Add to Cart) -->
                <MenuDetailAction 
                    v-model:qty="orderForm.qty"
                    v-model:note="orderForm.note"
                    :totalPrice="totalPrice"
                    @add-to-cart="addToCart"
                />

            </div>
        </div>

    </CustomerLayout>

    <!-- AI Modal -->
    <AiAssistantModal 
        :show="showAiModal" 
        :menu="menu" 
        @close="showAiModal = false" 
    />
</template>

<style scoped>
@supports (padding-bottom: env(safe-area-inset-bottom)) {
    .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
}
</style>

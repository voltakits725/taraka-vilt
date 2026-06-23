<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'
import CartItem from '../../../entities/cart/ui/CartItem.vue'
import TableSelector from '../../../features/cart/ui/TableSelector.vue'
import Swal from 'sweetalert2'

const props = defineProps({
    cartItems: {
        type: Array,
        default: () => []
    }
})

const page = usePage()
const tableNumber = ref(page.props.activeTable || '')
const isProcessing = ref(false)

const subTotal = computed(() => {
    return props.cartItems.reduce((total, item) => total + (item.price * item.qty), 0)
})

const taxAmount = computed(() => subTotal.value * 0.10)
const grandTotal = computed(() => subTotal.value + taxAmount.value)

// Helper: baca warna tema dari CSS variables
const getThemeColors = () => {
    const s = getComputedStyle(document.documentElement)
    return {
        bg:     s.getPropertyValue('--bg-surface').trim(),
        text:   s.getPropertyValue('--text-main').trim(),
        muted:  s.getPropertyValue('--text-muted').trim(),
        accent: s.getPropertyValue('--color-accent').trim(),
        border: s.getPropertyValue('--border-color').trim(),
    }
}

// Styled popup (modal center)
const themedPopup = (popup, c) => {
    popup.style.background    = c.bg
    popup.style.color         = c.text
    popup.style.border        = `1px solid ${c.border}`
    popup.style.borderRadius  = '24px'
    popup.style.boxShadow     = `0 16px 48px rgba(0,0,0,0.3), 0 0 0 1px ${c.accent}22`
}

// Styled toast (bottom-right)
const themedToast = (popup, c) => {
    popup.style.background   = c.bg
    popup.style.border       = `1px solid ${c.border}`
    popup.style.borderRadius = '18px'
    popup.style.boxShadow    = `0 8px 30px rgba(0,0,0,0.25), 0 0 0 1px ${c.accent}22`
    popup.style.padding      = '14px 20px'
    popup.style.minWidth     = '280px'
    const bar = popup.querySelector('.swal2-timer-progress-bar')
    if (bar) bar.style.background = c.accent
}

// --- Aksi Interaktif Realtime ---
const updateQuantity = (item, newQty) => {
    if (newQty < 1) {
        removeItem(item)
        return
    }
    router.patch(`/cart/${item.cart_key}`, { qty: newQty }, {
        preserveScroll: true,
        preserveState: true
    })
}

const removeItem = (item) => {
    const c = getThemeColors()

    Swal.fire({
        background: c.bg,
        color: c.text,
        html: `
            <div style="display:flex;flex-direction:column;align-items:center;gap:12px;padding:8px 0;">
                <img src="/images/taraka.png" style="width:48px;height:48px;object-fit:contain;border-radius:12px;" alt="Taraka" />
                <div style="font-family:'Cormorant Garamond',Georgia,serif;font-weight:800;font-size:22px;color:${c.text};">
                    Hapus pesanan?
                </div>
                <div style="font-size:14px;color:${c.muted};font-weight:500;">
                    ${item.name} bakal dihapus dari keranjang.
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            confirmButton: 'swal-btn-confirm',
            cancelButton: 'swal-btn-cancel',
        },
        buttonsStyling: false,
        didOpen: (popup) => themedPopup(popup, c),
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/cart/${item.cart_key}`, {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    const c2 = getThemeColors()
                    Swal.fire({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        background: c2.bg,
                        color: c2.text,
                        html: `
                            <div style="display:flex;align-items:center;gap:14px;padding:2px 0;">
                                <img src="/images/taraka.png" style="width:36px;height:36px;object-fit:contain;border-radius:10px;flex-shrink:0;" alt="Taraka" />
                                <div style="text-align:left;line-height:1.4;">
                                    <div style="font-weight:800;font-size:14px;color:${c2.text};font-family:'Cormorant Garamond',Georgia,serif;">
                                        Berhasil dihapus 🗑️
                                    </div>
                                    <div style="font-size:12px;color:${c2.muted};margin-top:2px;font-weight:500;">
                                        ${item.name} sudah dikeluarkan dari keranjang.
                                    </div>
                                </div>
                            </div>
                        `,
                        didOpen: (popup) => themedToast(popup, c2),
                    })
                }
            })
        }
    })
}

const handleCheckout = () => {
    const c = getThemeColors()

    if (!tableNumber.value) {
        Swal.fire({
            background: c.bg,
            color: c.text,
            html: `
                <div style="display:flex;flex-direction:column;align-items:center;gap:12px;padding:8px 0;">
                    <img src="/images/taraka.png" style="width:48px;height:48px;object-fit:contain;border-radius:12px;" alt="Taraka" />
                    <div style="font-family:'Cormorant Garamond',Georgia,serif;font-weight:800;font-size:22px;color:${c.text};">
                        Oops...
                    </div>
                    <div style="font-size:14px;color:${c.muted};font-weight:500;">
                        Pilih lokasi meja dulu bro!
                    </div>
                </div>
            `,
            confirmButtonText: 'Oke',
            customClass: { confirmButton: 'swal-btn-confirm' },
            buttonsStyling: false,
            didOpen: (popup) => themedPopup(popup, c),
        })
        return
    }

    isProcessing.value = true
    
    // POST request ke backend checkout
    router.post('/checkout', {
        table_number: tableNumber.value
    }, {
        preserveScroll: true,
        onSuccess: () => {
            isProcessing.value = false
            // Jika berhasil, controller akan otomatis meredirect ke halaman order status
            // karena checkout controller mengembalikan redirect()->route('customer.order.status', $order->id)
        },
        onError: (errors) => {
            isProcessing.value = false
            console.error('Checkout error:', errors)
            
            Swal.fire({
                background: c.bg,
                color: c.text,
                html: `
                    <div style="display:flex;flex-direction:column;align-items:center;gap:12px;padding:8px 0;">
                        <div style="width:48px;height:48px;border-radius:50%;background:#ef444420;color:#ef4444;display:flex;align-items:center;justify-center:center;font-size:24px;">❌</div>
                        <div style="font-family:'Cormorant Garamond',Georgia,serif;font-weight:800;font-size:22px;color:${c.text};">
                            Gagal Memproses
                        </div>
                        <div style="font-size:14px;color:${c.muted};font-weight:500;text-align:center;">
                            ${errors.checkout || 'Terjadi kesalahan saat memproses pesanan lo. Coba lagi.'}
                        </div>
                    </div>
                `,
                confirmButtonText: 'Tutup',
                customClass: { confirmButton: 'swal-btn-cancel' },
                buttonsStyling: false,
                didOpen: (popup) => themedPopup(popup, c),
            })
        }
    })
}
</script>


<template>
    <CustomerLayout>
        
        <div class="mb-8 md:mb-10">
            <h2 class="text-3xl md:text-4xl font-extrabold text-text-main mb-3">Keranjang Lo</h2>
            <p class="text-text-muted text-lg">Cek lagi pesanan lo sebelum lanjut ke pembayaran.</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 lg:gap-10 pb-24">
            
            <div class="w-full lg:w-2/3 space-y-4">
                <template v-if="cartItems.length > 0">
                    <CartItem 
                        v-for="(item, index) in cartItems" 
                        :key="item.cart_key" 
                        :item="item" 
                        @increase="updateQuantity(item, item.qty + 1)"
                        @decrease="updateQuantity(item, item.qty - 1)"
                        @remove="removeItem(item)"
                    />
                </template>
                
                <div v-else class="text-center py-20 bg-surface border border-border-theme rounded-3xl shadow-sm">
                    <div class="w-20 h-20 mx-auto bg-surface-hover rounded-full flex items-center justify-center mb-6 text-text-muted">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <p class="text-text-main font-black text-xl mb-3">Keranjang lo masih kosong nih.</p>
                    <p class="text-text-muted mb-6">Yuk eksplorasi menu dan temukan sajian favorit lo.</p>
                    <Link href="/menu" class="inline-flex items-center gap-2 px-8 py-3.5 bg-accent text-white rounded-full font-bold hover:opacity-90 hover:-translate-y-0.5 transition-all shadow-lg shadow-accent/30">
                        Lihat Katalog Menu
                    </Link>
                </div>
            </div>

            <div class="w-full lg:w-1/3">
                <div class="sticky top-28">
                    
                    <TableSelector v-if="!$page.props.activeTable" @update:table="tableNumber = $event" />
                    
                    <div v-else class="bg-surface border border-border-theme p-6 rounded-3xl shadow-sm mb-6 flex items-center justify-between">
                        <div>
                            <p class="text-text-muted text-sm font-bold">Memesan untuk</p>
                            <h3 class="text-xl font-black text-text-main">Meja {{ $page.props.activeTable }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-accent/10 flex items-center justify-center text-accent">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>

                    <div class="bg-surface border border-border-theme p-6 md:p-8 rounded-3xl shadow-sm">
                        <h3 class="text-xl font-black text-text-main mb-6">Ringkasan</h3>
                        
                        <div class="space-y-4 mb-6 text-sm font-bold text-text-muted border-b border-border-theme pb-6">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span class="text-text-main transition-all duration-300">Rp {{ subTotal.toLocaleString('id-ID') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Pajak (10%)</span>
                                <span class="text-text-main transition-all duration-300">Rp {{ taxAmount.toLocaleString('id-ID') }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-8">
                            <span class="text-lg font-black text-text-main">Total Bayar</span>
                            <span class="text-2xl font-black text-accent transition-all duration-300">Rp {{ grandTotal.toLocaleString('id-ID') }}</span>
                        </div>

                        <button @click="handleCheckout" :disabled="cartItems.length === 0 || isProcessing" 
                            class="w-full py-4 md:py-5 bg-accent text-white rounded-full font-bold shadow-xl shadow-accent/30 hover:opacity-90 hover:-translate-y-0.5 transition-all text-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none flex items-center justify-center gap-2">
                            <span v-if="isProcessing" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                            <span v-else class="flex items-center gap-2">
                                Lanjut Bayar
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                        </button>
                    </div>

                </div>
            </div>

        </div>

    </CustomerLayout>
</template>

<style>
/* SweetAlert2 Custom Buttons — pakai warna tema */
.swal-btn-confirm {
    padding: 10px 28px;
    border-radius: 14px;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    background: var(--color-accent);
    color: #fff;
    border: none;
    margin: 0 6px;
}
.swal-btn-confirm:hover {
    opacity: 0.85;
    transform: translateY(-1px);
}
.swal-btn-cancel {
    padding: 10px 28px;
    border-radius: 14px;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    background: var(--bg-surface-hover, #e5e7eb);
    color: var(--text-muted, #6b7280);
    border: 1px solid var(--border-color, #d1d5db);
    margin: 0 6px;
}
.swal-btn-cancel:hover {
    opacity: 0.85;
    transform: translateY(-1px);
}
</style>
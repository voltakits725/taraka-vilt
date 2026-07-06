<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'
import { useCartAlerts } from '../../../features/Customer/Cart/model/useCartAlerts'

// FSD Components
import CartItem from '../../../entities/Customer/Cart/ui/CartItem.vue'
import TableSelector from '../../../features/Customer/Cart/ui/TableSelector.vue'
import CartEmptyState from '../../../features/Customer/Cart/ui/CartEmptyState.vue'
import CartSummary from '../../../features/Customer/Cart/ui/CartSummary.vue'
import OrderTypeSelector from '../../../features/Customer/Cart/ui/OrderTypeSelector.vue'
import TimeSelector from '../../../features/Customer/Cart/ui/TimeSelector.vue'

const props = defineProps({
    cartItems: { type: Array, default: () => [] },
    bookedTables: { type: Array, default: () => [] },
    activeTables: { type: Array, default: () => [] }
})

const page = usePage()
const { confirmRemoveItem, showSuccessRemove, showValidationAlert, showCheckoutError } = useCartAlerts()

const tableNumber = ref(page.props.activeTable || '')
const orderType = ref('dine_in')
const selectedTime = ref('')
const isProcessing = ref(false)

// Generate available times (15:00 - 21:00, every 2 hours)
const availableTimes = []
for (let i = 15; i <= 21; i += 2) {
    availableTimes.push(`${i}:00`)
}

const blockedTableNumbers = computed(() => {
    if (!selectedTime.value) return [];
    
    const [selH, selM] = selectedTime.value.split(':').map(Number);
    const selMinutes = selH * 60 + selM;

    return props.bookedTables.filter(b => {
        if (!b.time) return false;
        const [bH, bM] = b.time.split(':').map(Number);
        return Math.abs(selMinutes - (bH * 60 + bM)) < 120;
    }).map(b => b.table);
})

const subTotal = computed(() => props.cartItems.reduce((total, item) => total + (item.price * item.qty), 0))
const taxAmount = computed(() => subTotal.value * 0.10)
const grandTotal = computed(() => subTotal.value + taxAmount.value)

const updateQuantity = (item, newQty) => {
    if (newQty < 1) return removeItem(item)
    router.patch(`/cart/${item.cart_key}`, { qty: newQty }, { preserveScroll: true, preserveState: true })
}

const removeItem = async (item) => {
    const result = await confirmRemoveItem(item)
    if (result.isConfirmed) {
        router.delete(`/cart/${item.cart_key}`, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => showSuccessRemove(item)
        })
    }
}

const handleCheckout = () => {
    const isScanOrder = !!page.props.activeTable

    if (orderType.value === 'dine_in' && !isScanOrder && (!tableNumber.value || !selectedTime.value)) {
        return showValidationAlert(selectedTime.value)
    }

    isProcessing.value = true
    
    const finalTime = isScanOrder 
        ? new Date().getHours().toString().padStart(2, '0') + ':00' 
        : selectedTime.value

    router.post('/checkout', {
        table_number: tableNumber.value,
        order_type: orderType.value,
        reservation_time: finalTime
    }, {
        preserveScroll: true,
        onSuccess: () => isProcessing.value = false,
        onError: (errors) => {
            isProcessing.value = false
            showCheckoutError(errors)
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
                <CartEmptyState v-else />
            </div>

            <div class="w-full lg:w-1/3">
                <div class="sticky top-28">
                    
                    <OrderTypeSelector v-if="!$page.props.activeTable" v-model="orderType" />

                    <div v-show="orderType === 'dine_in'">
                        <TimeSelector 
                            v-if="!$page.props.activeTable" 
                            v-model="selectedTime" 
                            :availableTimes="availableTimes" 
                            @reset-table="tableNumber = ''"
                        />

                        <TableSelector v-if="!$page.props.activeTable && selectedTime" :bookedTables="blockedTableNumbers" :activeTables="activeTables" @update:table="tableNumber = $event" />
                        
                        <div v-else-if="!$page.props.activeTable && !selectedTime" class="text-center p-5 bg-surface border border-border-theme rounded-2xl text-text-muted text-sm font-medium mb-6">
                            Silakan pilih jam reservasi di atas untuk melihat meja yang tersedia.
                        </div>
                        
                        <div v-else class="bg-surface border border-border-theme p-6 rounded-3xl shadow-sm mb-6 flex items-center justify-between">
                            <div>
                                <p class="text-text-muted text-sm font-bold">Memesan untuk</p>
                                <h3 class="text-xl font-black text-text-main">MEJA {{ $page.props.activeTable }} <span class="text-accent">- DINE IN</span></h3>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-accent/10 flex items-center justify-center text-accent">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <CartSummary 
                        :subTotal="subTotal" 
                        :taxAmount="taxAmount" 
                        :grandTotal="grandTotal" 
                        :isProcessing="isProcessing"
                        :cartItemsLength="cartItems.length"
                        @checkout="handleCheckout"
                    />

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
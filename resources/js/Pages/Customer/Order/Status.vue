<script setup>
import { onMounted, ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'

// FSD Components
import OrderStatusHeader from '../../../features/Customer/Order/ui/OrderStatusHeader.vue'
import OrderBillCard from '../../../features/Customer/Order/ui/OrderBillCard.vue'
import OrderActions from '../../../features/Customer/Order/ui/OrderActions.vue'

const props = defineProps({
    order: Object,
    snapToken: String,
    midtransClientKey: String
})

const isPaying = ref(false)

const payWithMidtrans = () => {
    if (!props.snapToken) {
        alert('Payment token not found!')
        return
    }

    isPaying.value = true
    
    // Gunakan snap window dari CDN
    window.snap.pay(props.snapToken, {
        onSuccess: function (result) {
            console.log('Success', result)
            isPaying.value = false
            router.reload()
        },
        onPending: function (result) {
            console.log('Pending', result)
            isPaying.value = false
            router.reload()
        },
        onError: function (result) {
            console.error('Error', result)
            isPaying.value = false
            router.reload()
        },
        onClose: function () {
            console.log('User closed the popup without finishing the payment')
            isPaying.value = false
            router.reload()
        }
    })
}

// Auto popup Midtrans if order is unpaid
onMounted(() => {
    if (props.order.payment_status === 'unpaid' && props.snapToken) {
        setTimeout(() => {
            payWithMidtrans()
        }, 1000)
    }
})
</script>

<template>
    <Head title="Status Pesanan" />
    <CustomerLayout>
        <div class="max-w-3xl mx-auto pb-24">
            
            <OrderStatusHeader :order="order" />
            
            <OrderBillCard :order="order" />

            <OrderActions :order="order" :isPaying="isPaying" @pay="payWithMidtrans" />

        </div>
    </CustomerLayout>
</template>

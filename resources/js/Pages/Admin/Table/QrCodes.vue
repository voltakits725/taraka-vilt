<script setup>
import { Head } from '@inertiajs/vue3'
import AdminLayout from '../../../shared/layouts/AdminLayout.vue'
import QrcodeVue from 'qrcode.vue'
import { ref, onMounted } from 'vue'

const tables = Array.from({ length: 15 }, (_, i) => i + 1)
const baseUrl = ref('')

onMounted(() => {
    baseUrl.value = window.location.origin
})

const printQRCodes = () => {
    window.print()
}
</script>

<template>
    <Head title="Cetak QR Code Meja" />
    <AdminLayout>
        
        <div class="mb-8 border-b border-border-theme pb-6 flex justify-between items-center print:hidden">
            <div>
                <h2 class="text-3xl font-extrabold text-text-main">
                    QR Code <span class="text-accent">Meja</span>
                </h2>
                <p class="text-text-muted mt-1">Cetak kode QR untuk ditempel di atas meja pelanggan.</p>
            </div>
            <button @click="printQRCodes" class="px-6 py-2.5 bg-accent text-white font-bold rounded-xl shadow-md hover:bg-accent/90 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Halaman
            </button>
        </div>

        <!-- Print Area -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 print:grid-cols-3 print:gap-8 print:m-0">
            <div v-for="table in tables" :key="table" class="bg-white border-2 border-dashed border-gray-300 rounded-3xl p-6 flex flex-col items-center text-center shadow-sm print:shadow-none print:border-solid print:border-black break-inside-avoid">
                <div class="mb-4">
                    <img src="/images/taraka.png" alt="Logo" class="h-8 object-contain mx-auto print:grayscale">
                </div>
                
                <h3 class="text-3xl font-black text-gray-900 mb-4 print:text-black">MEJA {{ table }}</h3>
                
                <div class="bg-white p-3 rounded-2xl border border-gray-100 shadow-inner inline-block print:border-black print:shadow-none">
                    <qrcode-vue 
                        v-if="baseUrl" 
                        :value="`${baseUrl}/scan/${table}`" 
                        :size="150" 
                        level="H" 
                        render-as="svg" 
                        class="mx-auto" 
                    />
                </div>
                
                <p class="mt-5 text-sm font-bold text-gray-500 uppercase tracking-wider print:text-black">Scan Untuk Pesan</p>
                <p class="mt-1 text-[10px] text-gray-400 break-all print:text-gray-600">{{ baseUrl }}/scan/{{ table }}</p>
            </div>
        </div>

    </AdminLayout>
</template>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .print\:hidden {
        display: none !important;
    }
    main {
        background-color: white !important;
    }
    /* Mengubah area grid yang mau diprint jadi kelihatan semua */
    .print\:grid-cols-3, .print\:grid-cols-3 * {
        visibility: visible;
    }
    .print\:grid-cols-3 {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>

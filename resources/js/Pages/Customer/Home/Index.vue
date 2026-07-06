<script setup>
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'
import HeroSection from '../../../features/Customer/Home/ui/HeroSection.vue'
import MarqueeStrip from '../../../features/Customer/Home/ui/MarqueeStrip.vue'
import HowItWorks from '../../../features/Customer/Home/ui/HowItWorks.vue'
import BestSellerSection from '../../../features/Customer/Home/ui/BestSellerSection.vue'
import AmbientBreak from '../../../features/Customer/Home/ui/AmbientBreak.vue'
import AiBanner from '../../../features/Customer/Home/ui/AiBanner.vue'
import Testimonials from '../../../features/Customer/Home/ui/Testimonials.vue'
import FinalCta from '../../../features/Customer/Home/ui/FinalCta.vue'

defineProps({
    previewMenus: Array
})
</script>

<template>
    <CustomerLayout>
        <HeroSection />

        <!-- Wrapper Konten Bawah -->
        <div class="max-w-7xl mx-auto px-6 md:px-12 w-full">
            <MarqueeStrip />
            <HowItWorks />
            <BestSellerSection :previewMenus="previewMenus" />
            <AmbientBreak />
            <AiBanner />
            <Testimonials />
            <FinalCta />
        </div>
    </CustomerLayout>
</template>
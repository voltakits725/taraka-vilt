<script setup>
defineProps({
    menu: Object
})
</script>
<template>
    <div class="w-full lg:w-1/2 flex items-center">
        <div class="lg:sticky lg:top-28 w-full aspect-[3/2] sm:aspect-[4/3] lg:aspect-square rounded-2xl md:rounded-[32px] lg:rounded-[40px] overflow-hidden bg-surface-hover border-2 lg:border-4 border-surface shadow-xl lg:shadow-2xl">
            <img v-if="menu.image" :src="menu.image" :alt="menu.name" class="w-full h-full object-cover hover:scale-110 transition-transform duration-700">
            <div v-else class="w-full h-full flex items-center justify-center text-text-muted font-bold text-xl">No Image</div>
        </div>
    </div>
</template>

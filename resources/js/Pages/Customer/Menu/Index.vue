<!-- resources/js/Pages/Customer/Menu/Index.vue -->
<script setup>
import { computed } from 'vue'
import CustomerLayout from '../../../shared/layouts/CustomerLayout.vue'

// Import FSD Components
import MenuCatalogHero from '../../../features/Customer/Menu/ui/MenuCatalogHero.vue'
import MenuFilter from '../../../features/Customer/Menu/ui/MenuFilter.vue'
import ActiveFilters from '../../../features/Customer/Menu/ui/ActiveFilters.vue'
import MenuEmptyState from '../../../features/Customer/Menu/ui/MenuEmptyState.vue'
import MenuPagination from '../../../features/Customer/Menu/ui/MenuPagination.vue'
import MenuCard from '../../../entities/Customer/Menu/ui/MenuCard.vue'

defineProps({
    menus: Object,
    categories: Array,
    filters: Object
})
</script>

<template>
    <CustomerLayout>
        <!-- Hero Section -->
        <MenuCatalogHero :totalMenus="menus.total" :totalCategories="categories?.length" />

        <!-- Filter & Search -->
        <MenuFilter :categories="categories" :filters="filters" />

        <!-- Active Filters Display -->
        <ActiveFilters :filters="filters" :categories="categories" />

        <!-- Result Count Bar -->
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-text-muted">
                Menampilkan
                <span class="font-bold" style="color: var(--text-main)">{{ menus.from || 0 }}–{{ menus.to || 0 }}</span>
                dari
                <span class="font-bold" style="color: var(--text-main)">{{ menus.total }}</span>
                menu
            </p>
            <div v-if="menus.last_page > 1" class="text-xs text-text-muted">
                Hal. <span class="font-bold" style="color: var(--text-main)">{{ menus.current_page }}</span> / {{ menus.last_page }}
            </div>
        </div>

        <!-- Empty State -->
        <MenuEmptyState v-if="menus.data.length === 0" />

        <!-- Menu Grid -->
        <div v-else class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-5 md:gap-6 mb-10">
            <MenuCard
                v-for="(menu, index) in menus.data"
                :key="menu.id"
                :menu="menu"
                :style="{ animationDelay: `${index * 60}ms` }"
                class="card-appear"
            />
        </div>

        <!-- Pagination -->
        <MenuPagination :menus="menus" :filters="filters" />

    </CustomerLayout>
</template>

<style scoped>
.card-appear {
    animation: cardFadeIn 0.5s ease-out both;
}

@keyframes cardFadeIn {
    from {
        opacity: 0;
        transform: translateY(16px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
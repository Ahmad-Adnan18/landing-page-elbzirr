<script setup>
import { Link } from '@inertiajs/vue3';
import { useCartStore } from '@/Stores/cart';
import { HomeIcon, ShoppingBagIcon, InformationCircleIcon, Square3Stack3DIcon } from '@heroicons/vue/24/outline'; // Default icons
import { HomeIcon as HomeIconSolid, ShoppingBagIcon as ShoppingBagIconSolid, InformationCircleIcon as InformationCircleIconSolid, Square3Stack3DIcon as Square3Stack3DIconSolid } from '@heroicons/vue/24/solid'; // Active icons

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
});

const cartStore = useCartStore();

const getIcon = (label, isActive) => {
    switch (label) {
        case 'Beranda':
            return isActive ? HomeIconSolid : HomeIcon;
        case 'Menu':
            return isActive ? Square3Stack3DIconSolid : Square3Stack3DIcon;
        case 'Keranjang':
            return isActive ? ShoppingBagIconSolid : ShoppingBagIcon;
        case 'Tentang Kami':
            return isActive ? InformationCircleIconSolid : InformationCircleIcon; // Or UserIcon/users    
        default:
            return isActive ? InformationCircleIconSolid : InformationCircleIcon;
    }
};
</script>

<template>
    <div class="fixed bottom-0 left-0 z-50 w-full h-16 bg-white border-t border-gray-200 shadow-lg sm:hidden">
        <div class="grid h-full max-w-lg grid-cols-4 mx-auto font-medium">
            <Link 
                v-for="item in items" 
                :key="item.label" 
                :href="item.link"
                class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 group relative"
            >
                <component 
                    :is="getIcon(item.label, item.active)" 
                    :class="['w-6 h-6 mb-0.5', item.active ? 'text-orange-600' : 'text-gray-500 group-hover:text-orange-600']"
                />
                
                <span 
                    :class="['text-[10px] whitespace-nowrap', item.active ? 'text-orange-600' : 'text-gray-500 group-hover:text-orange-600']"
                >
                    {{ item.label }}
                </span>

                <!-- Cart Badge -->
                <span v-if="item.label === 'Keranjang' && cartStore.itemCount > 0" class="absolute top-2 right-4 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                    {{ cartStore.itemCount }}
                </span>
            </Link>
        </div>
    </div>
</template>

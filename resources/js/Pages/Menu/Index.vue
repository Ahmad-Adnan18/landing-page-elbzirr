<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    products: Array,
});

const formatPrice = (price, currency) => {
    if (currency === 'IDR') {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(price);
    } else {
        return `₺ ${price}`;
    }
};
</script>

<template>
    <Head title="Menu" />

    <AppLayout>
        <div class="py-16 bg-gray-50 relative min-h-screen overflow-hidden">
             <!-- Decorative blob -->
            <div class="absolute top-0 left-0 -ml-20 -mt-20 w-64 h-64 rounded-full bg-orange-200 opacity-20 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 -mr-20 -mb-20 w-80 h-80 rounded-full bg-yellow-200 opacity-20 blur-3xl"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-12">
                    <h2 class="text-base text-orange-600 font-semibold tracking-wide uppercase">Menu Kami</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Pilih Favoritmu
                    </p>
                </div>

                <div v-if="products.length === 0" class="text-center text-gray-500 py-12">
                    Belum ada produk yang tersedia saat ini.
                </div>
                <div v-else class="grid grid-cols-3 gap-2 sm:gap-6 lg:grid-cols-3 xl:grid-cols-4 xl:gap-8">
                    <Link v-for="product in products" :key="product.id" :href="route('product.show', product.id)" class="group relative bg-white rounded-lg sm:rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden flex flex-col">
                        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-200 group-hover:opacity-90 transition-opacity relative">
                             <img :src="product.image_path ? '/storage/' + product.image_path : 'https://via.placeholder.com/400'" :alt="product.name" class="w-full h-28 sm:h-64 object-cover object-center">
                        </div>
                        <div class="p-2 sm:p-6 flex flex-col flex-grow">
                            <h3 class="text-xs sm:text-lg font-bold text-gray-900 group-hover:text-orange-600 transition-colors line-clamp-2">
                                {{ product.name }}
                            </h3>
                            <p class="mt-0.5 sm:mt-2 text-[10px] sm:text-sm text-gray-500 line-clamp-1 sm:line-clamp-2">{{ product.description }}</p>
                            
                            <div class="mt-auto pt-2 sm:pt-4 flex items-center justify-between">
                                <p class="text-xs sm:text-xl font-bold text-gray-900">
                                    <span v-if="product.variants && product.variants.length > 0">
                                        {{ formatPrice(product.variants[0].price, product.variants[0].currency) }}
                                    </span>
                                    <span v-else class="text-gray-500 text-[10px] sm:text-sm">Varian</span>
                                </p>
                                <span class="inline-flex items-center justify-center p-1 sm:p-2 bg-orange-100 rounded-full text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                                    <svg class="w-3 h-3 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </span>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

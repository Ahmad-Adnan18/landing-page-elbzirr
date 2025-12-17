<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { useCartStore } from '@/Stores/cart';

const props = defineProps({
    product: Object,
});

const cartStore = useCartStore();
const selectedVariant = ref(null);

onMounted(() => {
    if (props.product && props.product.variants && props.product.variants.length > 0) {
        selectedVariant.value = props.product.variants[0];
    }
});

const addToCart = () => {
    if (selectedVariant.value) {
        cartStore.addToCart(props.product, selectedVariant.value);
        alert('Berhasil ditambahkan ke keranjang!'); // Replace with better UI later
    }
};

const formatPrice = (price, currency) => {
    if (currency === 'IDR') {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(price);
    } else {
        return `₺ ${price}`;
    }
};
</script>

<template>
    <Head :title="product ? product.name : 'Detail Produk'" />

    <AppLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 grid grid-cols-1 md:grid-cols-2 gap-8" v-if="product">
                        <!-- Product Image -->
                        <div>
                            <img :src="product.image_path ? '/storage/' + product.image_path : 'https://via.placeholder.com/400'" :alt="product.name" class="w-full rounded-lg shadow-md">
                        </div>

                        <!-- Product Details -->
                        <div>
                            <h1 class="text-3xl font-bold mb-2">{{ product.name }}</h1>
                            <p class="text-gray-600 mb-4">{{ product.description }}</p>

                            <!-- Variants -->
                            <div class="mb-6" v-if="product.variants && product.variants.length > 0">
                                <h3 class="text-lg font-semibold mb-2">Pilih Varian:</h3>
                                <div class="flex flex-wrap gap-2">
                                    <button 
                                        v-for="variant in product.variants" 
                                        :key="variant.id"
                                        @click="selectedVariant = variant"
                                        :class="['px-4 py-2 rounded-full border transition-colors', 
                                            selectedVariant && selectedVariant.id === variant.id 
                                            ? 'bg-orange-500 text-white border-orange-500' 
                                            : 'bg-white text-gray-700 border-gray-300 hover:border-orange-500']"
                                    >
                                        {{ variant.name }}
                                    </button>
                                </div>
                            </div>

                            <!-- Price & Add to Cart -->
                            <div class="mt-8 border-t pt-6">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-gray-700 font-medium">Harga:</span>
                                    <span class="text-2xl font-bold text-orange-600" v-if="selectedVariant">
                                        {{ formatPrice(selectedVariant.price, selectedVariant.currency) }}
                                    </span>
                                    <span class="text-gray-500" v-else>Pilih varian</span>
                                </div>

                                <button 
                                    class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    :disabled="!selectedVariant || !selectedVariant.is_available"
                                    @click="addToCart"
                                >
                                    {{ selectedVariant && !selectedVariant.is_available ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 text-gray-900" v-else>
                        Produk tidak ditemukan.
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

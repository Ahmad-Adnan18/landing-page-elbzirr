<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useCartStore } from '@/Stores/cart';
import { ref, computed } from 'vue';

const props = defineProps({
    waNumber: String,
    isStoreOpen: Boolean,
});

const cartStore = useCartStore();

const form = ref({
    name: '',
    address: '',
    notes: '',
});

const formatPrice = (price, currency) => {
    if (currency === 'IDR') {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(price);
    } else {
        return `₺ ${price}`;
    }
};

const checkout = () => {
    if (!form.value.name || !form.value.address) {
        alert('Mohon isi Nama dan Alamat');
        return;
    }

    let message = `Halo Admin Elbzirr,\nSaya mau pesan via Website:\n\n`;
    message += `DATA PEMESAN\n`;
    message += `Nama: ${form.value.name}\n`;
    message += `Alamat: ${form.value.address}\n`;
    if (form.value.notes) {
        message += `Catatan: ${form.value.notes}\n`;
    }
    
    const status = props.isStoreOpen ? 'OPEN' : 'PO (Pre-Order)';
    message += `Status: ${status}\n\n`;

    message += `DETAIL PESANAN\n`;
    message += `------------------\n`;

    cartStore.items.forEach((item, index) => {
        message += `${index + 1}. ${item.product.name}\n`;
        message += `   Varian: ${item.variant.name} (${formatPrice(item.variant.price, item.variant.currency)})\n`;
        message += `   Qty: ${item.quantity}\n\n`;
    });

    message += `------------------\n`;
    message += `REKAP TAGIHAN:\n`;
    if (cartStore.totalTRY > 0) {
        message += `💰 Total Lira: ₺ ${cartStore.totalTRY}\n`;
    }
    if (cartStore.totalIDR > 0) {
        message += `💰 Total Rupiah: ${formatPrice(cartStore.totalIDR, 'IDR')}\n`;
    }
    message += `\nMohon info pembayaran.`;

    const encodedMessage = encodeURIComponent(message);
    window.open(`https://wa.me/${props.waNumber}?text=${encodedMessage}`, '_blank');
};
</script>

<template>
    <Head title="Keranjang Belanja" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Keranjang Belanja
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="!isStoreOpen" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Toko sedang <strong>TUTUP</strong>. Pesanan Anda akan diproses sebagai <strong>PRE-ORDER</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div v-if="cartStore.items.length === 0" class="text-center py-8">
                            <p class="text-gray-500 text-lg">Keranjang Anda kosong.</p>
                            <a :href="route('home')" class="text-orange-600 hover:underline mt-4 inline-block">Mulai Belanja</a>
                        </div>

                        <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Cart Items -->
                            <div class="lg:col-span-2">
                                <h3 class="text-lg font-semibold mb-4">Item</h3>
                                <div class="space-y-4">
                                    <div v-for="(item, index) in cartStore.items" :key="index" class="flex items-center justify-between border-b pb-4">
                                        <div class="flex items-center space-x-4">
                                            <img :src="item.product.image_path ? '/storage/' + item.product.image_path : 'https://via.placeholder.com/80'" class="w-20 h-20 object-cover rounded-md">
                                            <div>
                                                <h4 class="font-medium">{{ item.product.name }}</h4>
                                                <p class="text-sm text-gray-500">{{ item.variant.name }}</p>
                                                <p class="text-sm font-semibold text-orange-600">
                                                    {{ formatPrice(item.variant.price, item.variant.currency) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <div class="flex items-center border rounded-md">
                                                <button @click="item.quantity > 1 ? item.quantity-- : cartStore.removeFromCart(index)" class="px-3 py-1 hover:bg-gray-100">-</button>
                                                <span class="px-3 py-1 border-l border-r">{{ item.quantity }}</span>
                                                <button @click="item.quantity++" class="px-3 py-1 hover:bg-gray-100">+</button>
                                            </div>
                                            <button @click="cartStore.removeFromCart(index)" class="text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Checkout Form & Summary -->
                            <div class="bg-gray-50 p-6 rounded-lg h-fit">
                                <h3 class="text-lg font-semibold mb-4">Ringkasan Pesanan</h3>
                                
                                <div class="space-y-2 mb-6">
                                    <div class="flex justify-between" v-if="cartStore.totalTRY > 0">
                                        <span>Total (Lira)</span>
                                        <span class="font-bold">₺ {{ cartStore.totalTRY }}</span>
                                    </div>
                                    <div class="flex justify-between" v-if="cartStore.totalIDR > 0">
                                        <span>Total (Rupiah)</span>
                                        <span class="font-bold">{{ formatPrice(cartStore.totalIDR, 'IDR') }}</span>
                                    </div>
                                </div>

                                <h3 class="text-lg font-semibold mb-4">Detail Pelanggan</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                                        <input v-model="form.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                        <textarea v-model="form.address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                                        <textarea v-model="form.notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"></textarea>
                                    </div>
                                </div>

                                <button @click="checkout" class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-colors flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    Pesan via WhatsApp
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

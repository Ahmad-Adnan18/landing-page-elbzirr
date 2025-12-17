import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: [],
    }),
    getters: {
        totalTRY: (state) => {
            return state.items.reduce((total, item) => {
                if (item.variant.currency === 'TRY') {
                    return total + (parseFloat(item.variant.price) * item.quantity);
                }
                return total;
            }, 0);
        },
        totalIDR: (state) => {
            return state.items.reduce((total, item) => {
                if (item.variant.currency === 'IDR') {
                    return total + (parseFloat(item.variant.price) * item.quantity);
                }
                return total;
            }, 0);
        },
        itemCount: (state) => {
            return state.items.reduce((total, item) => total + item.quantity, 0);
        },
    },
    actions: {
        addToCart(product, variant, quantity = 1) {
            const existingItem = this.items.find(item => item.variant.id === variant.id);

            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                this.items.push({
                    product: {
                        id: product.id,
                        name: product.name,
                        image_path: product.image_path,
                    },
                    variant: variant,
                    quantity: quantity,
                });
            }
        },
        removeFromCart(index) {
            this.items.splice(index, 1);
        },
        clearCart() {
            this.items = [];
        },
    },
    persist: true, // Optional: if using pinia-plugin-persistedstate
});

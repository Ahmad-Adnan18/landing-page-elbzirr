<script setup>
import { ref, computed, onMounted, useTemplateRef } from 'vue';

const props = defineProps({
    products: {
        type: Array,
        required: true,
        default: () => []
    }
});

const currentIndex = ref(0);
const direction = ref('right');
const containerRef = useTemplateRef('containerRef');
const touchStartX = ref(0);
const touchEndX = ref(0);

const currentProduct = computed(() => {
    if (!props.products.length) return null;
    return props.products[currentIndex.value];
});

const nextSlide = () => {
    if (!props.products.length) return;
    direction.value = 'right';
    currentIndex.value = (currentIndex.value + 1) % props.products.length;
};

const prevSlide = () => {
    if (!props.products.length) return;
    direction.value = 'left';
    currentIndex.value = (currentIndex.value - 1 + props.products.length) % props.products.length;
};

// Swipe Logic
const handleTouchStart = (e) => {
    touchStartX.value = e.changedTouches[0].screenX;
};

const handleTouchEnd = (e) => {
    touchEndX.value = e.changedTouches[0].screenX;
    handleSwipe();
};

const handleMouseDown = (e) => {
    touchStartX.value = e.clientX;
};

const handleMouseUp = (e) => {
    touchEndX.value = e.clientX;
    handleSwipe();
};

const handleSwipe = () => {
    const threshold = 50;
    if (touchStartX.value - touchEndX.value > threshold) {
        nextSlide();
    }
    if (touchEndX.value - touchStartX.value > threshold) {
        prevSlide();
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
    <div 
        ref="containerRef"
        class="relative w-full bg-[#faefe5] overflow-hidden py-16 sm:py-24 select-none cursor-grab active:cursor-grabbing" 
        v-if="products.length > 0"
        @touchstart="handleTouchStart"
        @touchend="handleTouchEnd"
        @mousedown="handleMouseDown"
        @mouseup="handleMouseUp"
    >
         <!-- Top Wave -->
        <div class="absolute top-0 left-0 w-full overflow-hidden leading-[0] z-0 pointer-events-none">
            <svg class="relative block w-[calc(100%+1.3px)] h-[50px] sm:h-[100px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#ffffff"></path>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 h-full min-h-[500px] flex items-center overflow-hidden">
            <Transition :name="direction === 'right' ? 'slide-right' : 'slide-left'">
                <div :key="currentProduct.id" class="w-full absolute inset-x-0 px-4 sm:px-6 lg:px-8 top-1/2 -translate-y-1/2">
                    <div class="flex flex-col-reverse lg:flex-row items-center justify-between gap-8 lg:gap-12">
                        
                        <!-- Text Content (Left) -->
                        <div class="w-full lg:w-1/2 space-y-6 text-center lg:text-left">
                            <div>
                                <h2 class="text-4xl sm:text-5xl font-extrabold text-[#ea580c] font-cursive transform -rotate-2">
                                    Signatures
                                </h2>
                                <h3 class="text-2xl sm:text-4xl font-black text-gray-900 mt-2 uppercase tracking-wide">
                                    {{ currentProduct.name }}
                                </h3>
                            </div>

                            <p class="text-lg text-gray-700 leading-relaxed max-w-lg mx-auto lg:mx-0">
                                {{ currentProduct.description }}
                            </p>
                            
                            <div class="space-y-2">
                                <p class="text-2xl font-bold text-gray-900">
                                    <span v-if="currentProduct.variants && currentProduct.variants.length > 0">
                                        {{ formatPrice(currentProduct.variants[0].price, currentProduct.variants[0].currency) }}
                                    </span>
                                </p>
                                <p class="text-sm text-gray-600 italic">
                                    *Premium Ingredients, Freshly Made
                                </p>
                            </div>
                        </div>

                        <!-- Image Content (Right) -->
                        <div class="w-full lg:w-1/2 relative flex justify-center items-center pointer-events-none">
                            <div class="relative w-64 h-64 sm:w-80 sm:h-80 md:w-96 md:h-96 lg:w-[450px] lg:h-[450px]">
                                <!-- Circle Background -->
                                <div class="absolute inset-0 bg-white rounded-full shadow-2xl opacity-50 transform scale-90"></div>
                                
                                <!-- Product Image -->
                                <img 
                                    :src="currentProduct.image_path ? '/storage/' + currentProduct.image_path : 'https://via.placeholder.com/400'" 
                                    :alt="currentProduct.name" 
                                    class="w-full h-full object-cover rounded-full shadow-lg relative z-10"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
        
        <!-- Bottom Wave -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-[0] z-0 pointer-events-none">
            <svg class="relative block w-[calc(100%+1.3px)] h-[50px] sm:h-[100px] transform rotate-180" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#ffffff"></path>
            </svg>
        </div>
    </div>
</template>

<style scoped>
.font-cursive {
    font-family: 'Brush Script MT', 'Comic Sans MS', cursive; 
}

/* Slide Right (Next) */
.slide-right-enter-active,
.slide-right-leave-active {
  transition: all 0.5s ease-in-out;
}

.slide-right-enter-from {
  transform: translateX(100%);
  opacity: 0;
}

.slide-right-leave-to {
  transform: translateX(-100%);
  opacity: 0;
}

/* Slide Left (Prev) */
.slide-left-enter-active,
.slide-left-leave-active {
  transition: all 0.5s ease-in-out;
}

.slide-left-enter-from {
  transform: translateX(-100%);
  opacity: 0;
}

.slide-left-leave-to {
  transform: translateX(100%);
  opacity: 0;
}
</style>

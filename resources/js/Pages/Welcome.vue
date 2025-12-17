<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import SpotlightCard from '@/Components/SpotlightCard.vue';
import SignatureCarousel from '@/Components/SignatureCarousel.vue';
import TestimonialCard from '@/Components/TestimonialCard.vue';


defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    signatureProducts: Array,
    testimonials: Array,
});

import { ref, useTemplateRef } from 'vue';

const scrollContainer = useTemplateRef('scrollContainer');
const isDown = ref(false);
const startX = ref(0);
const scrollLeft = ref(0);

const startDrag = (e) => {
    if (!scrollContainer.value) return;
    isDown.value = true;
    startX.value = e.pageX - scrollContainer.value.offsetLeft;
    scrollLeft.value = scrollContainer.value.scrollLeft;
};

const stopDrag = () => {
    isDown.value = false;
};

const moveDrag = (e) => {
    if (!isDown.value || !scrollContainer.value) return;
    e.preventDefault();
    const x = e.pageX - scrollContainer.value.offsetLeft;
    const walk = (x - startX.value) * 2; // scroll-fast
    scrollContainer.value.scrollLeft = scrollLeft.value - walk;
};
</script>

<template>
    <Head title="Welcome" />

    <AppLayout>
        <!-- Hero Section -->
        <div class="relative bg-white overflow-hidden w-full h-screen items-center justify-center flex">
            <div class="absolute inset-0">
                <img src="/images/hero-section.jpg" alt="Background" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gray-50 opacity-25"></div>
            </div>
            <div class="relative max-w-7xl mx-auto lg:grid lg:grid-cols-2">
                <div class="pb-16 pt-16 px-4 sm:px-6 lg:px-4 lg:pb-24 lg:pt-32 xl:px-4">
                    <div class="lg:self-center">
                        <h1 class="text-6xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl lg:text-5xl xl:text-6xl">
                            <span class="block">Dimsum Premium</span>
                            <span class="block text-orange-600">Cita Rasa Otentik</span>
                        </h1>
                        <p class="mt-6 text-lg leading-6 text-gray-800">
                            Nikmati dimsum terbaik yang dibuat dengan bahan-bahan premium. Dibuat segar, dikukus sempurna, dan siap memanjakan lidah Anda.
                        </p>
                        <div class="mt-8 flex gap-4">
                            <Link :href="route('menu.index')" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-orange-600 hover:bg-orange-700 md:py-4 md:text-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                Pesan Sekarang
                            </Link>
                            <a href="#about" class="inline-flex items-center justify-center px-8 py-3 border border-gray-300 text-base font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 md:py-4 md:text-lg shadow-sm hover:shadow-md transition-all duration-300">
                                Pelajari Lebih Lanjut
                            </a>
                        </div>
                    </div>
                </div>
                <!-- <div class="relative -mt-12 lg:mt-0 lg:col-start-2">
                    <div class="relative h-64 sm:h-72 md:h-96 lg:h-full lg:absolute lg:inset-y-0 lg:right-0 lg:w-full">
                        <img class="w-full h-full object-cover" src="images/logo1.png" alt="Dimsum Platter"> 
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent rounded-bl-3xl lg:rounded-bl-[5rem]"></div>
                    </div>
                </div> -->
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-base text-orange-600 font-semibold tracking-wide uppercase">Kenapa Memilih Kami</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Kualitas yang Bisa Anda Rasakan
                    </p>
                </div>

                <div class="mt-10 relative">
                    <div 
                        ref="scrollContainer"
                        @mousedown="startDrag"
                        @mouseleave="stopDrag"
                        @mouseup="stopDrag"
                        @mousemove="moveDrag"
                        class="flex overflow-x-auto gap-4 pb-6 px-4 -mx-4 sm:grid sm:grid-cols-2 sm:gap-10 sm:pb-0 sm:px-0 sm:mx-0 lg:grid-cols-3 scrollbar-hide cursor-grab active:cursor-grabbing select-none"
                        :class="isDown ? '' : 'snap-x snap-mandatory'"
                    >
                        <SpotlightCard class-name="snap-center shrink-0 w-[85%] sm:w-auto flex flex-col items-center text-center bg-orange-50 transition-transform hover:scale-105 duration-300 border-orange-100" spotlight-color="rgba(234, 88, 12, 0.2)">
                            <div class="relative z-10 flex flex-col items-center">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-orange-500 text-white mb-4 shadow-lg">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">Bahan Premium</h3>
                                <p class="mt-2 text-base text-gray-500">Kami hanya menggunakan bahan-bahan segar dan berkualitas tinggi untuk dimsum kami.</p>
                            </div>
                        </SpotlightCard>
                        <SpotlightCard class-name="snap-center shrink-0 w-[85%] sm:w-auto flex flex-col items-center text-center bg-orange-50 transition-transform hover:scale-105 duration-300 border-orange-100" spotlight-color="rgba(234, 88, 12, 0.2)">
                            <div class="relative z-10 flex flex-col items-center">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-orange-500 text-white mb-4 shadow-lg">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">Dibuat Segar</h3>
                                <p class="mt-2 text-base text-gray-500">Dibuat segar setiap hari untuk memastikan tekstur dan rasa terbaik di setiap gigitan.</p>
                            </div>
                        </SpotlightCard>
                        <SpotlightCard class-name="snap-center shrink-0 w-[85%] sm:w-auto flex flex-col items-center text-center bg-orange-50 transition-transform hover:scale-105 duration-300 border-orange-100" spotlight-color="rgba(234, 88, 12, 0.2)">
                            <div class="relative z-10 flex flex-col items-center">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-orange-500 text-white mb-4 shadow-lg">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">Harga Terjangkau</h3>
                                <p class="mt-2 text-base text-gray-500">Nikmati dimsum kualitas premium dengan harga yang tidak menguras kantong.</p>
                            </div>
                        </SpotlightCard>
                    </div>
                </div>
            </div>
            </div>


        <!-- Signature Carousel -->
        <SignatureCarousel :products="signatureProducts" />

        <!-- Testimonial Section -->
        <div id="about" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="text-4xl font-extrabold text-gray-900">Customers Testimonial</h2>
                </div>

                <div 
                    class="flex overflow-x-auto gap-6 pb-8 px-4 -mx-4 sm:mx-0 sm:px-0 snap-x snap-mandatory scrollbar-hide"
                >
                    <TestimonialCard 
                        v-for="testimonial in testimonials" 
                        :key="testimonial.id" 
                        :testimonial="testimonial" 
                    />
                </div>
                
                <!-- Indicators (Mock visual) -->
                <div class="flex justify-center gap-2 mt-4">
                    <span class="w-2 h-2 rounded-full bg-gray-300"></span>
                    <span class="w-2 h-2 rounded-full bg-black"></span>
                    <span class="w-2 h-2 rounded-full bg-gray-300"></span>
                </div>
            </div>
        </div>

        <!-- Product Grid -->

    </AppLayout>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    testimonial: {
        type: Object,
        required: true,
    },
});

const defaultAvatar = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(props.testimonial.name) + '&color=7F9CF5&background=EBF4FF';
const avatarUrl = computed(() => props.testimonial.avatar_path ? '/storage/' + props.testimonial.avatar_path : defaultAvatar);

</script>

<template>
    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100 min-w-[300px] w-full sm:w-[350px] flex-shrink-0 snap-center">
        <!-- Header -->
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-3">
                <img :src="avatarUrl" :alt="testimonial.name" class="w-10 h-10 rounded-full object-cover bg-gray-200">
                <div>
                    <h4 class="font-bold text-gray-900 text-sm leading-tight">{{ testimonial.name }}</h4>
                    <p class="text-xs text-gray-500 mt-0.5">{{ testimonial.role || 'Local Guide' }}</p>
                </div>
            </div>
            <button class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                </svg>
            </button>
        </div>

        <!-- Rating & Time -->
        <div class="flex items-center mt-3 gap-2">
            <div class="flex text-yellow-400">
                <svg v-for="i in 5" :key="i" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path :class="i <= testimonial.rating ? 'text-yellow-400' : 'text-gray-300'" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            </div>
            <span class="text-xs text-gray-500">• 1 month ago</span>
        </div>

        <!-- Content -->
        <p class="mt-3 text-sm text-gray-700 leading-relaxed">
            {{ testimonial.content }}
        </p>

        <!-- Ordered Items -->
        <div v-if="testimonial.ordered_items" class="mt-3 text-xs text-gray-500">
            <span class="font-medium text-gray-600">Ordered:</span> {{ testimonial.ordered_items }}
        </div>

        <!-- Footer -->
        <div class="mt-4 pt-3 border-t border-gray-50 flex items-center text-gray-500 text-xs gap-1 cursor-pointer hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
            </svg>
            Helpful?
        </div>
    </div>
</template>

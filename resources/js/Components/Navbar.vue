<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useCartStore } from '@/Stores/cart';
import BottomNavbar from '@/Components/BottomNavbar.vue';

const activeSection = ref('home');

const menuItems = computed(() => [
    { label: 'Beranda', ariaLabel: 'Ke Beranda', link: route('home'), active: route().current('home') && activeSection.value === 'home' },
    { label: 'Menu', ariaLabel: 'Lihat Menu', link: route('menu.index'), active: route().current('menu.index') },
    { label: 'Keranjang', ariaLabel: 'Lihat Keranjang', link: route('cart.index'), active: route().current('cart.index') },
    { label: 'Tentang Kami', ariaLabel: 'Tentang Kami', link: route('home') + '#about', active: route().current('home') && activeSection.value === 'about' }
]);

const desktopMenuItems = computed(() => {
    return menuItems.value.filter(item => item.label !== 'Keranjang');
});

const showingNavigationDropdown = ref(false);
const cartStore = useCartStore();
const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 10;
    
    // Scroll Spy Logic
    if (route().current('home')) {
        const sections = ['home', 'menu', 'about'];
        for (const section of sections) {
            const el = document.getElementById(section === 'home' ? 'app' : section); // Assuming 'app' or top is home
            // Actually 'home' might not have an ID, usually top of page. 
            // Let's assume 'home' is the top section.
            // If we have specific IDs for sections.
            
            // Better approach:
            // Check scroll position against section offsets.
            // Let's assume we have IDs: 'menu', 'about'. 'home' is default if < menu.
            
            const menuEl = document.getElementById('menu');
            const aboutEl = document.getElementById('about');
            
            const scrollY = window.scrollY + 100; // Offset for navbar
            
            if (aboutEl && scrollY >= aboutEl.offsetTop) {
                activeSection.value = 'about';
            } else if (menuEl && scrollY >= menuEl.offsetTop) {
                activeSection.value = 'menu';
            } else {
                activeSection.value = 'home';
            }
        }
    }
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Initial check
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <nav :class="[
        'fixed w-full z-50 transition-all duration-300 top-0',
        isScrolled ? 'bg-orange-600 border-transparent shadow-lg' : 'bg-transparent border-transparent'
    ]">
        <div class="max-w-7xl mx-auto px-4 sm:px-4 lg:px-4 justify-center items-center">
            <div class="flex justify-between h-16">
                <!-- Logo (Left) -->
                <div class="flex items-center">
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('home')" class="flex items-center gap-2">
                            <img src="/images/logo.png" alt="Elbzirr Logo" class="h-8 w-auto" />
                            <span :class="['text-2xl font-bold transition-colors duration-300', isScrolled ? 'text-white' : 'text-orange-600']">Elbzirr</span>
                        </Link>
                    </div>
                </div>

                <div class="hidden sm:flex sm:space-x-8 sm:items-center justify-center flex-1">
                    <Link 
                        v-for="item in desktopMenuItems" 
                        :key="item.label"
                        :href="item.link" 
                        :class="['inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out group', 
                            item.active
                                ? (isScrolled ? 'text-white' : 'text-orange-600')
                                : (isScrolled ? 'text-white hover:text-gray-200' : 'text-gray-500 hover:text-gray-700')
                        ]"
                    >
                        <span class="relative py-1">
                            {{ item.label }}
                            <span 
                                class="absolute bottom-0 left-0 w-full h-0.5 transition-transform duration-300 origin-left transform scale-x-0 group-hover:scale-x-100"
                                :class="[
                                    isScrolled ? 'bg-white' : 'bg-orange-600',
                                    item.active ? 'scale-x-100' : ''
                                ]"
                            ></span>
                        </span>
                    </Link>
                </div>
                


                <!-- Right Side (Cart & Hamburger) -->
                <div class="flex items-center">
                    <!-- Mobile Social Icons -->
                     <div class="flex items-center space-x-3 sm:hidden mr-4" v-if="$page.props.social">
                        <a 
                            v-if="$page.props.social.instagram"
                            :href="$page.props.social.instagram" 
                            target="_blank"
                            rel="noopener noreferrer"
                            :class="['transition-colors duration-300', isScrolled ? 'text-white hover:text-gray-200' : 'text-orange-600 hover:text-orange-700']"
                        >
                            <span class="sr-only">Instagram</span>
                            <svg fill="currentColor" viewBox="0 0 24 24" class="h-5 w-5" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.153-1.772c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465 1.067-.047 1.407-.06 4.123-.06h.08zm1.096 11.133c.483-2.417-1.488-4.576-3.951-4.19C7.456 9.4 6.77 11.83 8.32 13.56c1.33 1.49 3.867 1.16 4.09-.597.027-.225.068-.432.12-.628l-.119-.068zM12 7a5 5 0 100 10 5 5 0 000-10zm5.34-1.74a1.07 1.07 0 11-2.14 0 1.07 1.07 0 012.14 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a 
                            v-if="$page.props.social.tiktok"
                            :href="$page.props.social.tiktok" 
                            target="_blank"
                            rel="noopener noreferrer"
                            :class="['transition-colors duration-300', isScrolled ? 'text-white hover:text-gray-200' : 'text-orange-600 hover:text-orange-700']"
                        >
                            <span class="sr-only">TikTok</span>
                            <svg fill="currentColor" viewBox="0 0 24 24" class="h-5 w-5" aria-hidden="true">
                                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.35-1.17 1.09-1.07 1.93.03.5.25.96.61 1.25.79.62 2 1.02 3 1.02 1.79 0 3.27-1.45 3.27-3.64.03-3.09.03-6.18.03-9.27 1.99.91 4.16.82 5.92-.32V2.8c-1.02.16-2.05.29-3.08.39-.7.06-1.4.03-2.1-.01V.02h-1.6z"/>
                            </svg>
                        </a>
                    </div>
                
                    <div class="hidden sm:flex sm:items-center">
                        <!-- Social Icons -->
                        <div class="flex items-center space-x-3 mr-4 border-r border-gray-300 pr-4" v-if="$page.props.social">
                            <a 
                                v-if="$page.props.social.instagram"
                                :href="$page.props.social.instagram" 
                                target="_blank"
                                rel="noopener noreferrer"
                                :class="['transition-colors duration-300', isScrolled ? 'text-white hover:text-gray-200' : 'text-gray-600 hover:text-orange-600']"
                            >
                                <span class="sr-only">Instagram</span>
                                <svg fill="currentColor" viewBox="0 0 24 24" class="h-5 w-5" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465 1.067-.047 1.407-.06 4.123-.06h.08zm1.096 11.133c.483-2.417-1.488-4.576-3.951-4.19C7.456 9.4 6.77 11.83 8.32 13.56c1.33 1.49 3.867 1.16 4.09-.597.027-.225.068-.432.12-.628l-.119-.068zM12 7a5 5 0 100 10 5 5 0 000-10zm5.34-1.74a1.07 1.07 0 11-2.14 0 1.07 1.07 0 012.14 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a 
                                v-if="$page.props.social.tiktok"
                                :href="$page.props.social.tiktok" 
                                target="_blank"
                                rel="noopener noreferrer"
                                :class="['transition-colors duration-300', isScrolled ? 'text-white hover:text-gray-200' : 'text-gray-600 hover:text-orange-600']"
                            >
                                <span class="sr-only">TikTok</span>
                                <svg fill="currentColor" viewBox="0 0 24 24" class="h-5 w-5" aria-hidden="true">
                                    <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.35-1.17 1.09-1.07 1.93.03.5.25.96.61 1.25.79.62 2 1.02 3 1.02 1.79 0 3.27-1.45 3.27-3.64.03-3.09.03-6.18.03-9.27 1.99.91 4.16.82 5.92-.32V2.8c-1.02.16-2.05.29-3.08.39-.7.06-1.4.03-2.1-.01V.02h-1.6z"/>
                                </svg>
                            </a>
                        </div>
                        
                        <Link :href="route('cart.index')" 
                            :class="['relative p-2 transition-colors duration-300', isScrolled ? 'text-white hover:text-gray-800' : 'text-gray-800 hover:text-gray-500']">
                            <span class="sr-only">Keranjang</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                            <span v-if="cartStore.itemCount > 0" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                {{ cartStore.itemCount }}
                            </span>
                        </Link>
                    </div>

                </div>
            </div>
        </div>

    </nav>

    <!-- Bottom Navbar (Mobile) -->
    <BottomNavbar :items="menuItems" />
</template>


import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, DefineComponent, h } from 'vue';
import VueSmoothScroll from 'vue3-smooth-scroll';
import { ZiggyVue } from 'ziggy-js';
import '../css/app.css';
import { initializeTheme } from './composables/useAppearance';
import Index from './pages/Index.vue';

// Add Google Fonts programmatically
const addGoogleFonts = () => {
    // Preconnect for better performance
    const preconnect1 = document.createElement('link');
    preconnect1.rel = 'preconnect';
    preconnect1.href = 'https://fonts.googleapis.com';
    document.head.appendChild(preconnect1);

    const preconnect2 = document.createElement('link');
    preconnect2.rel = 'preconnect';
    preconnect2.href = 'https://fonts.gstatic.com';
    preconnect2.crossOrigin = 'crossorigin';
    document.head.appendChild(preconnect2);

    // Main font stylesheet
    const fontLink = document.createElement('link');
    fontLink.rel = 'stylesheet';
    fontLink.href =
        'https://fonts.googleapis.com/css2?family=Questrial&family=Spline+Sans:wght@300..700&display=swap';
    document.head.appendChild(fontLink);
};

// Load Google Fonts
addGoogleFonts();

const appName = import.meta.env.VITE_APP_NAME || 'CAM Admissions Portal';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: async (name: string) => {
        const pages = import.meta.glob<DefineComponent>('./Pages/**/*.vue', { eager: true });
        const component = await pages[`./Pages/${name}.vue`];
        const page = component?.default || component;
        
      

        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(VueSmoothScroll) 
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

initializeTheme();
import './bootstrap';
import '../css/main.css';
import { createPinia } from 'pinia'
import { useDarkModeStore } from '@/stores/darkMode.js'
import { darkModeKey } from '@/config'
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
const pinia = createPinia()

const appName =
    import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`,
        import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

const darkModeStore = useDarkModeStore(pinia)
document.documentElement.classList.forEach((token) => {
        if (token.indexOf('style') === 0) {
            document.documentElement.classList.replace(token, `style-basic`)
        }
        document.documentElement.classList.replace(token, `style-basic`)
    })
    /* Dark mode */
if (
    (!localStorage[darkModeKey] && window.matchMedia('(prefers-color-scheme: dark)').matches) ||
    localStorage[darkModeKey] === '1'
) {
    darkModeStore.set(true)
}
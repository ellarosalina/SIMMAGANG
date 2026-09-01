import './bootstrap';

import Alpine from 'alpinejs';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

window.Alpine = Alpine;

Alpine.start();

const pages = import.meta.glob('./Pages/**/*.vue', {
    eager: true,
});

createInertiaApp({
    resolve: name => {
        const page = pages[`./Pages/${name}.vue`];

        if (!page) {
            console.error('Halaman Vue tidak ditemukan:', name);
            console.log('Halaman Vue yang tersedia:', Object.keys(pages));

            throw new Error(
                `Halaman Vue tidak ditemukan: ./Pages/${name}.vue`
            );
        }

        return page;
    },

    setup({ el, App, props, plugin }) {
        createApp({
            render: () => h(App, props),
        })
            .use(plugin)
            .mount(el);
    },

    progress: {
        color: '#2563eb',
    },
});
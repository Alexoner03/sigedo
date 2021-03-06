require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import VCalendar from 'v-calendar';
import "gridjs/dist/theme/mermaid.css";


const el = document.getElementById('app');

createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .mixin({ methods: { route : window.route } })
    .use(InertiaPlugin)
    .use(VCalendar, {})
    .mount(el);

InertiaProgress.init({ color: '#bc8158' });

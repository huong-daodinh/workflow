import './bootstrap';
import '../css/app.css';
import 'easymde/dist/easymde.min.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/src/js';
import type { DefineComponent } from 'vue';
import { createPinia } from 'pinia';
import appSetting from './app-setting';
import i18n from '@/i18n';
import { TippyPlugin } from 'tippy.vue';
import Maska from 'maska';
import VueEasymde from 'vue3-easymde';
import Popper from 'vue3-popper';
import { CkeditorPlugin } from '@ckeditor/ckeditor5-vue';
import FloatingVue from 'floating-vue';
import 'floating-vue/dist/style.css';
// import myApp from './App.vue';
const appName = import.meta.env.VITE_APP_NAME || 'Work Flow';
const pinia = createPinia();
createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob<DefineComponent>('./Pages/**/*.vue')
    ),
  setup({ el, App, props, plugin }) {
    // App = myApp;
    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(pinia)
      .use(i18n)
      .use(TippyPlugin)
      .use(Maska)
      .use(VueEasymde)
      .component('Popper', Popper)
      .use(CkeditorPlugin)
      .use(FloatingVue);
    appSetting.init();
    app.mount(el);
    return app;
  },
  progress: {
    color: '#4B5563'
  }
});

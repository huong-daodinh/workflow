import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vueI18n from '@intlify/vite-plugin-vue-i18n';
import path from 'path';

export default defineConfig({
  plugins: [
    laravel({
      input: 'resources/js/app.ts',
      refresh: true
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    }),
    vueI18n({
      include: path.resolve(__dirname, './resources/js/locales/**')
    })
  ],
  optimizeDeps: {
    include: ['quill']
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './resources/js'),
      '@css': path.resolve(__dirname, './resources/css'),
      '@asset': path.resolve(__dirname, './public/assets')
    }
  }
});

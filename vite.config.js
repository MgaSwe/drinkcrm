import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    vue(),
  ],
  server: {
    host: true,       // 0.0.0.0 im Container
    port: 5173,       // fester Port
    strictPort: true, // wenn belegt -> Fehler statt auf 5174 wechseln
    hmr: { host: 'localhost', port: 5173 },
  },
})

// https://nuxt.com/docs/api/configuration/nuxt-config
import ckeditor5 from "@ckeditor/vite-plugin-ckeditor5";
import svgLoader from "vite-svg-loader";
import { createRequire } from "node:module";
const require = createRequire(import.meta.url);

const larkTheme = require.resolve("@ckeditor/ckeditor5-theme-lark");

export default defineNuxtConfig({
  ssr: true,
  devtools: { enabled: true },
  css: ['~/assets/css/main.css'],
  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {},
    },
  },
  modules: ['nuxt-icon', '@pinia/nuxt', '@pinia-plugin-persistedstate/nuxt'],
  runtimeConfig: {
    public: {
      stripePk: process.env.STRIPE_PK_KEY,
      webURL: process.env.WEB_URL || 'http://localhost:3000',
      apiURL: process.env.API_URL || 'http://localhost:8000',
    }
  },
  vite: {
    plugins: [
      ckeditor5({
        theme: larkTheme,
      }),
      svgLoader(),
    ],
  },
  app: {
    head: {
      script: [
        { src: 'https://js.stripe.com/v3/' },
        { src: 'https://cdn.jsdelivr.net/npm/chart.js' }
      ],
    }
  },
  pages: true,
  routeRules: {
    /**
     * Set custom headers matching paths
     */
    '/_nuxt/**': { headers: { 'cache-control': 's-maxage=0' } },
    /**
     * Add cors headers
     */
    '/api/**': { cors: true },
    /**
     * Static page generated on-demand, revalidates in background
     */
    '/blog/**': { swr: true },
    /**
     * Static page generated on-demand once
     */
    '/articles/**': { static: true },
    /**
     * Render these routes with SPA - Disable SSR
     */
    '/admin/**': { ssr: false },
    '/coming-soon': { ssr: false },
  }
})

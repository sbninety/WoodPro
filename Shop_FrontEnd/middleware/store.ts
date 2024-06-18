import { useSessionStore } from "~/stores/session"

export default defineNuxtRouteMiddleware((to, from) => {
    const sessionStore = useSessionStore();

    const path = [
        '/store',
        '/store/products',
        '/store/products/create',
        '/store/products/edit',
        '/store/orders'
    ]

    if (!sessionStore.isStoreLogin && path.includes(to.path)) {
        return navigateTo('/store/login');
    }

    if (sessionStore.type == 2 && (to.path == '/store/login' || to.path == '/store')) {
        return navigateTo('/store/products');
    }

    if (sessionStore.type == 3 && to.path == '/store/login') {
        return navigateTo('/store');
    }
})
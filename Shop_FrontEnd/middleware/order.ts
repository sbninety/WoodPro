import { useSessionStore } from "~/stores/session"

export default defineNuxtRouteMiddleware((from, to) => {
    const sessionStore = useSessionStore();

    const skip = [
        '/account/address',
        '/orders'
    ]

    if (!sessionStore.isCustomerLogin && skip.includes(to.path)) {
        return navigateTo('/account/login');
    }
})
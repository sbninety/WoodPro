import { useSessionStore } from "~/stores/session"

export default defineNuxtRouteMiddleware((from, to) => {
    const sessionStore = useSessionStore();

    const skip = [
        '/account/login',
        '/account/register',
        '/account/forgot'
    ]

    if (sessionStore.isCustomerLogin && skip.includes(to.path)) {
        return navigateTo('/');
    }
})
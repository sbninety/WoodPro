import { defineStore } from 'pinia'

export const useSessionStore = defineStore({
    id: 'session-store',
    state: () => ({
        id: null,
        type: null,
        token: null,
        isLoading: false
    }),
    actions: {
        setToken(value: any) {
            localStorage.removeItem("token")

            localStorage.setItem("token", value);

            this.$state.token = value;
        },
        logout() {
            localStorage.removeItem("token");
            this.$state.id = null;
            this.$state.type = null;
            this.$state.token = null;
        }
    },
    getters: {
        isCustomerLogin: (state): boolean => state.type == 1,
        isStoreLogin: (state): boolean => state.type == 2 || state.type == 3
    },
    persist: true
})

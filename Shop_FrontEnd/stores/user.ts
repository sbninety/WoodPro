import { defineStore } from "pinia";
import { useSessionStore } from "./session";
import { useCartService } from "~/services/cart";

export const useUserStore = defineStore({
    id: 'user',
    state: () => ({
        cart: [],
        checkout: []
    }),
    actions: {
        async initCart() {
            const sessionStore = useSessionStore();
            const cartService = useCartService();

            if (sessionStore.id) {
                const res = await cartService.getCartByUser(sessionStore.id);

                if (res.data.length) {
                    this.$state.cart = res.data;
                } else {
                    if (this.$state.cart.length) {
                        this.$state.cart.forEach(async (item) => {
                            item.user_id = sessionStore.id;
                            await cartService.store(item).then(res => {
                                item.id = res.data.id;
                            })
                        })
                    }
                }
            }
        },
        async addToCart(item: any) {
            const sessionStore = useSessionStore();
            const cartService = useCartService();

            if (this.$state.cart.length) {
                let count = 0;
                this.$state.cart.forEach(async (prod) => {
                    if (
                        prod.product_id == item.product_id &&
                        prod.color_id == item.color_id &&
                        prod.material_id == item.material_id &&
                        prod.size_id == item.size_id
                    ) {
                        count++;
                        prod.quantity = prod.quantity + item.quantity
                        if (prod.quantity > prod.product_quantity) {
                            prod.quantity = prod.product_quantity;
                        }
                        if (sessionStore.id) {
                            let formData = new FormData();
                            formData.append("quantity", prod.quantity);
                            formData.append("_method", "PATCH");
                            await cartService.update(formData, prod.id);
                        }
                    }
                });
                if (count == 0) {
                    if (sessionStore.id) {
                        item.user_id = sessionStore.id;
                        await cartService.store(item).then(res => {
                            this.$state.cart.push(res.data);
                        });
                    } else {
                        this.$state.cart.push(item);
                    }
                }
            } else {
                if (sessionStore.id) {
                    item.user_id = sessionStore.id;
                    await cartService.store(item).then(res => {
                        this.$state.cart.push(res.data);
                    });
                } else {
                    this.$state.cart.push(item);
                }
            }
        }
    },
    persist: {
        storage: persistedState.localStorage,
    },
})
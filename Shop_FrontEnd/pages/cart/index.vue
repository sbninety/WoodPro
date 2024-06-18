<script setup lang="ts">
import MainLayout from "~/layouts/default";
import CartItem from "~/components/pages/cart/item.vue";
import { useUtils } from "~/composables/utils";
import { useUserStore } from "~/stores/user";
import { useSessionStore } from "~/stores/session";

const userStore = useUserStore();
const sessionStore = useSessionStore();
const { price } = useUtils();

const selectedItems = ref([]);

onMounted(() => {
  sessionStore.isLoading = true;
  setTimeout(() => {
    sessionStore.isLoading = false;
  }, 500);
});

const totalPriceComputed = computed(() => {
  let price = 0;
  selectedItems.value.forEach((item) => {
    price += item.price * item.quantity;
  });
  return price;
});

const selectedRadioFunc = (e) => {
  if (!selectedItems.value.length) {
    selectedItems.value.push(e);
    return;
  }
  selectedItems.value.forEach((item, index) => {
    if (
      e.product_id == item.product_id &&
      e.color_id == item.color_id &&
      e.material_id == item.material_id &&
      e.size_id == item.size_id
    ) {
      selectedItems.value.splice(index, 1);
    } else {
      selectedItems.value.push(e);
    }
  });
};

const goToCheckout = () => {
  if (selectedItems.value.length) {
    userStore.checkout = selectedItems.value;
    return navigateTo("checkout");
  }
};
</script>
<template>
  <MainLayout>
    <div class="w-[1200px] mx-auto py-10">
      <ul>
        <NuxtLink class="text-sm text-[#676767] italic font-light"
          >Trang chủ
          <span class="px-2"> / </span>
        </NuxtLink>
        <NuxtLink class="text-sm text-[#676767] italic font-light"
          >Giỏ hàng</NuxtLink
        >
      </ul>
    </div>
    <div class="w-[1200px] mx-auto" v-if="!userStore.cart.length">
      <h4 class="text-lg">
        Giỏ hàng của bạn hiện đang trống.
        <NuxtLink to="/" class="font-light hover:text-[#676767]"
          >Tiếp tục mua sắm</NuxtLink
        >
      </h4>
    </div>
    <div class="w-[1200px] mx-auto gap-4" v-else>
      <div class="bg-[#F6F6F6] rounded-lg p-4">
        <div class="text-2xl font-bold">
          Giỏ hàng ({{ userStore.cart.length }})
        </div>
      </div>
      <div class="bg-[#FBFBFB] rounded-lg p-4 mt-4 flex flex-col gap-5">
        <table>
          <tr class="font-bold">
            <td class="w-[50px]"></td>
            <td class="w-[550px]">Sản phẩm</td>
            <td class="w-[200px]">Giá</td>
            <td>Số lượng</td>
          </tr>
          <tr v-for="item in userStore.cart" :key="item">
            <CartItem
              :item="item"
              :selectedItems="selectedItems"
              @selectedRadio="selectedRadioFunc"
            />
          </tr>
        </table>
      </div>
      <div class="flex mt-10">
        <div class="w-2/3"></div>
        <div class="w-1/3">
          <div class="flex justify-between items-end">
            <h3 class="text-xl font-bold">Tổng giá:</h3>
            <p class="text-2xl font-bold mr-5 text-orange-400">
              {{ price(totalPriceComputed) }}đ
            </p>
          </div>
          <div class="flex justify-between">
            <div />
            <p class="text-sm mr-5">(Chưa bao gồm phí vận chuyển)</p>
          </div>
          <div class="flex justify-between">
            <div />
            <NuxtLink class="mt-7 font-light text-sm hover:text-[#676767]"
              ><< Tiếp tục mua sắm</NuxtLink
            >
            <button
              @click="goToCheckout()"
              class="bg-black mt-5 mr-5 text-white font-light px-5 py-1.5 rounded hover:bg-[#A8A88F]"
            >
              THANH TOÁN
            </button>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>
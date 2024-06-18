<script setup lang="ts">
import { useSessionStore } from "~/stores/session";
import { useUserStore } from "~/stores/user";
import { useAuthService } from "~/services/auth";

const sessionStore = useSessionStore();
const userStore = useUserStore();
const authService = useAuthService();

const isAccountMenu = ref(false);
const keyword = ref(null);

const search = () => {
  return navigateTo({
    path: "/search",
    query: {
      q: keyword.value,
    },
  });
};

const logout = async () => {
  sessionStore.logout();
  userStore.cart = [];
  userStore.checkout = [];
  await authService.logout();
  window.location.reload();
};
</script>

<template>
  <div id="TopMenu" class="w-full bg-[#f6f6f6] border-b z-60">
    <ul
      class="flex items-center justify-between text-sm text-[#333333] px-2 h-10 w-[1200px] mx-auto"
    >
      <NuxtLink class="px-3 cursor-pointer" to="/store"
        >Đồ gỗ nội thất Woodpro</NuxtLink
      >
      <div class="flex items-center h-full px-2.5">
        <li class="border-r border-r-gray-400 px-3">Email: info@gmail.com</li>
        <li class="border-r border-r-gray-400 px-3">Hỗ trợ: 0987.654.321</li>
        <li
          @mouseenter="isAccountMenu = true"
          @mouseleave="isAccountMenu = false"
          class="relative flex items-center px-2.5 hover:text-[#ff4646] h-full cursor-pointer"
          :class="
            isAccountMenu
              ? 'bg-white border z-40 shadow-[0_15px_100px_40px_rgba(0,0,0,0.3)]'
              : 'border border-[#fafafa]'
          "
        >
          <Icon name="ph:user-thin" size="17" />
          <Icon name="mdi:chevron-down" size="15" class="ml-5" />
          <div
            id="AccountMenu"
            v-if="isAccountMenu"
            class="absolute bg-white w-[220px] text-[#333333] z-40 top-[39px] -left-[100px] border-x border-b"
          >
            <div v-if="!sessionStore.isCustomerLogin">
              <div class="text-semibold text-[15px] my-4 px-3">
                Chào mừng tới Woodpro!
              </div>
              <div class="flex items-center w-full gap-1 px-3 mb-3">
                <NuxtLink
                  to="/account/login"
                  class="bg-black text-center w-full text-[16px] rounded-sm text-white font-semibold p-2"
                >
                  Đăng nhập / Đăng ký
                </NuxtLink>
              </div>
            </div>
            <div class="border-b"></div>
            <ul
              class="bg-white flex flex-col"
              v-if="sessionStore.isCustomerLogin"
            >
              <NuxtLink
                to="/orders"
                class="text-[13px] py-2 px-4 w-full hover:bg-gray-200"
              >
                Đơn hàng
              </NuxtLink>
              <li class="text-[13px] py-2 px-4 w-full hover:bg-gray-200">
                <button @click="logout()">Đăng xuất</button>
              </li>
            </ul>
          </div>
        </li>
      </div>
    </ul>
  </div>
  <div id="MainHeader" class="flex items-center w-full bg-white">
    <div class="flex justify-start gap-10 w-[1150px] px-3 py-5 mx-auto">
      <NuxtLink to="/" class="min-w-[170px]">
        <img src="/logo.png" width="170" />
      </NuxtLink>
      <div class="w-[700px] my-auto">
        <div class="relative">
          <form
            @submit.prevent="search()"
            class="flex items-center border-2 border-black rounded-md w-full"
          >
            <input
              class="w-full placeholder-gray-400 text-sm pl-3 focus:outline-none"
              placeholder="Tìm kiếm sản phẩm"
              type="text"
              name="q"
              autocomplete="off"
              v-model="keyword"
            />
            <button
              type="submit"
              class="flex items-center h-[100%] p-1.5 px-2 bg-black"
            >
              <Icon name="ic:outline-search" size="20" color="#ffffff" />
            </button>
          </form>
        </div>
      </div>
      <NuxtLink class="flex items-center" to="/cart">
        <div class="relative hover:cursor-pointer">
          <span
            class="absolute flex items-center justify-center right-[3px] top-0 h-[17px] bg-black min-w-[17px] text-xs text-white px-0.5 rounded-full"
          >
            {{ userStore.cart.length }}
          </span>
          <div class="min-w-[40px]">
            <Icon name="material-symbols:shopping-cart-outline" size="33" />
          </div>
        </div>
      </NuxtLink>
    </div>
  </div>
</template>
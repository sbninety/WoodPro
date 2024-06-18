<script setup lang="ts">
import { useSessionStore } from "~/stores/session";
import { useUserStore } from "~/stores/user";
import { useAuthService } from "~/services/auth";

const sessionStore = useSessionStore();
const userStore = useUserStore();
const authService = useAuthService();

const logout = async () => {
  sessionStore.logout();
  userStore.cart = [];
  userStore.checkout = [];
  await authService.logout();
  window.location.reload();
};
</script>
<template>
  <div class="w-full border-b border-[#0000000D]">
    <div class="w-[1350px] mx-auto flex justify-between items-center">
      <div class="flex gap-1.5 items-center">
        <NuxtLink to="/store" class="mt-[5px] mb-[12px]">
          <img src="/logo.png" width="90.97" />
        </NuxtLink>
        <h3 class="text-[18px] ml-5">Kênh Cửa Hàng</h3>
      </div>
      <div class="flex items-center gap-8">
        <button
          @click="logout()"
          class="flex items-center justify-center gap-2 border border-[#00000033] rounded-[20px] text-[16px] px-[18px] py-[8px]"
        >
          ĐĂNG XUẤT
          <div>
            <Icon name="mdi:log-out" size="25" />
          </div>
        </button>
      </div>
    </div>
  </div>
</template>
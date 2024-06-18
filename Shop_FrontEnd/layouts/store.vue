<script setup lang="ts">
import StoreHeader from "~/components/layouts/store/header.vue";
import StoreFooter from "~/components/layouts/store/footer.vue";
import SideBar from "~/components/layouts/store/sidebar.vue";
import { useSessionStore } from "~/stores/session";

const sessionStore = useSessionStore();
</script>
<template>
  <StoreHeader />
  <div class="flex w-[1400px] mx-auto">
    <div class="w-[20%]">
      <SideBar />
    </div>
    <div class="w-[80%]">
      <slot></slot>
    </div>
  </div>
  <StoreFooter />
  <div
    v-if="sessionStore.isLoading"
    class="fixed bg-black bg-opacity-40 inset-0 w-full z-40 flex items-center justify-center h-[100vh] overflow-hidden"
  >
    <div class="p-3 rounded-md bg-black bg-opacity-20">
      <Icon
        name="icon-park-outline:loading-four"
        class="animate-spin"
        size="100"
        color="#B3E5FC"
      />
      <div class="text-center pt-5 text-xl font-bold text-white">Đang tải</div>
    </div>
  </div>
</template>
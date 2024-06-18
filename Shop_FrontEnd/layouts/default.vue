<script setup lang="ts">
import MainHeader from "~/components/layouts/default/header.vue";
import MainFooter from "~/components/layouts/default/footer.vue";
import { useSessionStore } from "~/stores/session";

const sessionStore = useSessionStore();
</script>

<template>
  <div class="w-full fixed z-40">
    <MainHeader />
  </div>
  <div class="pt-[150px]"></div>
  <slot />
  <MainFooter />
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
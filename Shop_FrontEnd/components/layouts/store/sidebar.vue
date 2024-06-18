<script setup lang="ts">
const active = ref(0);
</script>
<template>
  <div
    class="pl-2 py-3 border-b border-black hover:bg-[#EEEEEE]"
    :class="active === 1 ? 'bg-[#EEEEEE]' : ''"
    @click="active = 1"
  >
    <NuxtLink to="/store/products">
      <div
        class="flex items-center gap-5 text-sm font-semibold"
        :class="active === 2 ? 'bg-[#EEEEEE]' : ''"
        @click="active = 2"
      >
        <img src="/image 40.png" width="30" /> Quản Lý Sản Phẩm
      </div>
    </NuxtLink>
  </div>
  <div
    class="pl-2 py-3 border-b border-black hover:bg-[#EEEEEE]"
    :class="active === 3 ? 'bg-[#EEEEEE]' : ''"
    @click="active = 3"
  >
    <NuxtLink to="/store/orders"
      ><div class="flex items-center gap-5 text-sm font-semibold">
        <img src="/image 38.png" width="30" /> Quản Lý Đơn Hàng
      </div></NuxtLink
    >
  </div>
</template>
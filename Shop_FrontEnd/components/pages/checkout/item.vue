<script setup lang="ts">
import { useUtils } from "~/composables/utils";

const props = defineProps(["product"]);

const { product } = toRefs(props);
const { price } = useUtils();
</script>
<template>
  <div class="flex items-center justify-between">
    <div class="flex gap-3">
      <div class="relative w-[80px]">
        <img
          :src="product.product_image"
          class="rounded-md border-2 border-[#676767] w-[80px] h-[80px]"
        />
        <div
          class="absolute w-5 h-5 bg-[#A3A3A3] rounded-full flex items-center justify-center text-white text-xs font-bold -top-2 -right-2"
        >
          {{ product.quantity }}
        </div>
      </div>
      <div class="w-[310px]">
        <h3 class="text-sm font-semibold w-[250px]">
          {{ product.product_name }}
        </h3>
        <div class="flex flex-wrap mt-2 gap-3">
          <div
            class="bg-[#EEE3CE] px-1 py-1 border border-[#b58023] text-[#b58023] text-[11px] rounded-lg"
          >
            {{ product.color_name }}
          </div>
          <div
            class="bg-white px-1.5 py-1 border border-[#676767] text-[11px] rounded-lg"
          >
            {{ product.length }} x {{ product.width }} x {{ product.height }}
          </div>
          <div
            class="bg-white px-1.5 py-1 border border-[#74B018] text-[#74B018] text-[11px] rounded-lg"
          >
            {{ product.material_name }}
          </div>
        </div>
      </div>
    </div>
    <div class="font-semibold text-[15px]">{{ price(product.price) }}đ</div>
  </div>
</template>
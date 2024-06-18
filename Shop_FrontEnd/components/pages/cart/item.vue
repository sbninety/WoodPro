<script setup lang="ts">
import { useUtils } from "~/composables/utils";
import { useUserStore } from "~/stores/user";
import { useSessionStore } from "~/stores/session";
import { useCartService } from "~/services/cart";

const { price, onlyNumber } = useUtils();
const userStore = useUserStore();
const sessionStore = useSessionStore();
const cartService = useCartService();

const props = defineProps(["item", "selectedItems"]);
const { item, selectedItems } = toRefs(props);

const emit = defineEmits(["selectedRadio"]);

let isHover = ref(false);
let isSelected = ref(false);

watch(
  () => isSelected.value,
  () => {
    emit("selectedRadio", item.value);
  }
);

const watchQuantity = () => {
  if (item.value.quantity < 0) {
    item.value.quantity = 1;
  }
  if (item.value.quantity > item.value.product_quantity) {
    item.value.quantity = item.value.product_quantity;
  }
};

const decrease = () => {
  if (item.value.quantity > 1) {
    item.value.quantity--;
  }
  updateQuantity();
  return;
};

const increase = () => {
  if (item.value.quantity < item.value.product_quantity) {
    item.value.quantity++;
  }
  updateQuantity();
  return;
};

const updateQuantity = async () => {
  if (sessionStore.id) {
    let formData = new FormData();
    formData.append("quantity", item.value.quantity);
    formData.append("_method", "PATCH");
    await cartService.update(formData, item.value.id);
  }
};

const removeFromCart = () => {
  userStore.cart.forEach(async (prod, index) => {
    if (
      prod.product_id == item.value.product_id &&
      prod.color_id == item.value.color_id &&
      prod.material_id == item.value.material_id &&
      prod.size_id == item.value.size_id
    ) {
      emit("selectedRadio", item.value);
      userStore.cart.splice(index, 1);
      if (sessionStore.id) {
        await cartService.delete(prod.id);
      }
    }
  });
};
</script>
<template>
  <td class="my-auto border-t border-[#676767]">
    <div
      @mouseenter="isHover = true"
      @mouseleave="isHover = false"
      class="flex items-center justify-start p-0.5 cursor-pointer"
    >
      <div
        @click="isSelected = !isSelected"
        class="flex items-center justify-center h-[20px] w-[20px] rounded-full border mr-5 ml-2"
        :class="[
          isHover ? 'border-[#fd374f]' : 'border-gray-300',
          isSelected ? 'bg-[#fd374f]' : '',
        ]"
      >
        <div class="h-[8px] w-[8px] rounded-full bg-white" />
      </div>
    </div>
  </td>
  <td class="border-t border-[#676767]">
    <div class="flex py-3">
      <img class="rounded-md w-[90px] h-[90px]" :src="item.product_image" />
      <div class="overflow-hidden pl-2 w-full">
        <div class="flex items-center justify-between w-full">
          <div class="flex items-center justify-between">
            <div class="pl-2 pr-5 font-semibold">
              {{ item.product_name }}
            </div>
          </div>
        </div>
        <div class="flex pl-2 mt-2 gap-3">
          <div
            class="bg-[#EEE3CE] px-1 py-1 border border-[#b58023] text-[#b58023] text-[11px] rounded-lg"
          >
            {{ item.color_name }}
          </div>
          <div
            class="bg-white px-1.5 py-1 border border-[#676767] text-[11px] rounded-lg"
          >
            {{ item.length }} x {{ item.width }} x {{ item.height }}
          </div>
          <div
            class="bg-white px-1.5 py-1 border border-[#74B018] text-[#74B018] text-[11px] rounded-lg"
          >
            {{ item.material_name }}
          </div>
        </div>
      </div>
    </div>
  </td>
  <td class="border-t border-[#676767]">{{ price(item.price) }}đ</td>
  <td class="border-t border-[#676767]">
    <div class="flex h-8">
      <button
        @click="decrease()"
        class="border-y border-l border-[#00000033] w-8"
      >
        -
      </button>
      <input
        type="text"
        class="w-16 border border-[#00000033] text-center focus:outline-none appearance-none"
        v-model="item.quantity"
        @keypress="onlyNumber($event)"
        @input="watchQuantity()"
        @blur="updateQuantity()"
      />
      <button
        @click="increase()"
        class="border-y border-r border-[#00000033] w-8"
      >
        +
      </button>
    </div>
  </td>
  <td class="border-t border-[#676767]">
    <button class="hover:text-red-500" @click="removeFromCart()">
      <Icon name="material-symbols:delete-outline" size="20" />
    </button>
  </td>
</template>
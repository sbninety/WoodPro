<script setup lang="ts">
import Swal from "sweetalert2";
import { useUtils } from "~/composables/utils";
import { useUserStore } from "~/stores/user";

const { price, onlyNumber } = useUtils();
const userStore = useUserStore();

const props = defineProps(["product"]);
const { product } = toRefs(props);

const isHover = ref(false);
const isShowDialog = ref(false);
const successDialog = ref(false);
const currentImage = ref(product.value.images[0].path);
const selectedColor = ref(product.value.colors[0]);
const selectedMaterial = ref(product.value.materials[0]);
const selectedSize = ref(product.value.sizes[0]);
const quantity = ref(1);

const priceComputed = computed(() => {
  if (product.value.discount && selectedSize.value.price) {
    return (
      selectedSize.value.price -
      selectedSize.value.price * (product.value.discount / 100)
    );
  } else if (product.value.discount && !selectedSize.value.price) {
    return (
      product.value.price - product.value.price * (product.value.discount / 100)
    );
  } else if (!product.value.discount && selectedSize.value.price) {
    return selectedSize.value.price;
  } else {
    return product.value.price;
  }
});

const watchQuantity = () => {
  if (quantity.value < 0) {
    quantity.value = 1;
  }
  if (quantity.value > product.value.quantity) {
    quantity.value = product.value.quantity;
  }
};

const decrease = () => {
  if (quantity.value > 1) {
    quantity.value--;
  }
  return;
};

const increase = () => {
  if (quantity.value < product.value.quantity) {
    quantity.value++;
  }
  return;
};

const addToCart = () => {
  const item = {
    id: null,
    user_id: null,
    product_id: product.value.id,
    product_name: product.value.name,
    product_quantity: product.value.quantity,
    product_image: product.value.images[0].path,
    price: priceComputed.value,
    color_id: selectedColor.value.id,
    color_name: selectedColor.value.name,
    material_id: selectedMaterial.value.id,
    material_name: selectedMaterial.value.name,
    size_id: selectedSize.value.id,
    length: selectedSize.value.length,
    width: selectedSize.value.width,
    height: selectedSize.value.height,
    quantity: quantity.value,
  };
  userStore.addToCart(item);
  showAlert();
};

const showAlert = () => {
  Swal.fire({
    icon: "success",
    title: "Thêm giỏ hàng thành công",
    showConfirmButton: false,
    timer: 1500,
  });
};
</script>
<template>
  <div
    class="relative overflow-hidden"
    @mouseenter="isHover = true"
    @mouseleave="isHover = false"
  >
    <NuxtLink :to="`/products/${product.slug}`">
      <img :src="product.images[0].path" width="265" class="h-[265px]" />
      <h4 class="mt-2 capitalize line-clamp-2">
        {{ product.name }}
      </h4>
    </NuxtLink>
    <span class="text-[18px] font-medium">
      <span v-if="product.discount">
        {{ price(product.price - product.price * (product.discount / 100)) }}đ
      </span>
      <span v-else>{{ price(product.price) }}đ</span>
    </span>
    <span
      v-if="product.discount"
      class="line-through text-[14px] font-medium ml-2"
      >{{ price(product.price) }}đ</span
    >
    <div
      v-if="product.discount"
      class="absolute bg-[#FFE600] w-10 h-10 flex items-center justify-center top-0 right-5 text-xs font-bold"
    >
      <span>- {{ product.discount }}%</span>
    </div>
    <div
      @click="isShowDialog = true"
      class="absolute top-12 right-[25px] transition duration-150 cursor-pointer z-10"
      :class="isHover ? '' : 'translate-x-14'"
    >
      <Icon name="ion:bag-add-outline" size="30" />
    </div>
    <div
      v-if="!product.quantity"
      class="absolute w-full h-[265px] bg-black bg-opacity-20 top-0 flex items-center justify-center text-xl text-white font-bold z-20 transition duration-150"
      :class="isHover ? '' : 'translate-x-[265px]'"
    >
      HẾT HÀNG
    </div>
  </div>
  <Transition name="bounce">
    <div
      v-if="isShowDialog"
      class="fixed bg-black bg-opacity-40 inset-0 w-full z-40 flex items-center justify-center h-[100vh] overflow-hidden"
    >
      <div class="relative rounded-md bg-white w-[850px] pb-5">
        <button
          class="absolute right-0 bg-black p-1.5"
          @click="isShowDialog = false"
        >
          <Icon name="mdi:close" color="#FFFFFF" size="25" />
        </button>
        <div class="flex mt-5 px-5 gap-5">
          <div class="w-[40%]">
            <img
              class="rounded-lg object-cover w-full h-[315px]"
              v-if="currentImage"
              :src="currentImage"
            />
            <div class="flex items-center justify-center mt-2">
              <div v-for="image in product.images" :key="image">
                <img
                  @mouseenter="currentImage = image.path"
                  @click="currentImage = image.path"
                  width="70"
                  class="rounded-md object-fit border-[3px] cursor-pointer h-[70px]"
                  :class="currentImage === image.path ? 'border-[#ff5353]' : ''"
                  :src="image.path"
                />
              </div>
            </div>
          </div>
          <div class="w-[60%]">
            <h2 class="text-xl text-[#676767] font-bold">
              {{ product.name }}
            </h2>
            <h3 class="text-lg font-semibold">{{ price(priceComputed) }}đ</h3>
            <div class="bg-[#F5F5F5] mt-2 border border-[#CCC] p-3">
              <div>
                <h4 class="text-sm">Màu sắc:</h4>
                <div class="flex flex-wrap mt-2 gap-3">
                  <div
                    v-for="color in product.colors"
                    :key="color"
                    class="px-3 py-1 border cursor-pointer text-xs"
                    :class="
                      selectedColor == color
                        ? 'bg-[#EEE3CE] border-[#b58023] text-[#b58023]'
                        : 'bg-white border-[#CCC]'
                    "
                    @click="selectedColor = color"
                  >
                    {{ color.name }}
                  </div>
                </div>
              </div>
              <div class="mt-3">
                <h4 class="text-sm">Kích thước (ngang x cao x sâu) (cm):</h4>
                <div class="flex flex-wrap mt-2 gap-3">
                  <div
                    v-for="size in product.sizes"
                    :key="size"
                    class="px-3 py-1 border cursor-pointer text-xs"
                    :class="
                      selectedSize == size
                        ? 'bg-[#EEE3CE] border-[#b58023] text-[#b58023]'
                        : 'bg-white border-[#CCC]'
                    "
                    @click="selectedSize = size"
                  >
                    {{ size.length }} x {{ size.width }} x {{ size.height }}
                  </div>
                </div>
              </div>
              <div class="mt-3">
                <h4 class="text-sm">Chất liệu:</h4>
                <div class="flex flex-wrap mt-2 gap-3">
                  <div
                    v-for="material in product.materials"
                    :key="material"
                    class="px-3 py-1 border cursor-pointer text-xs"
                    :class="
                      selectedMaterial == material
                        ? 'bg-[#EEE3CE] border-[#b58023] text-[#b58023]'
                        : 'bg-white border-[#CCC]'
                    "
                    @click="selectedMaterial = material"
                  >
                    {{ material.name }}
                  </div>
                </div>
              </div>
            </div>
            <div class="flex items-center mt-5">
              <div class="w-[80px] text-sm text-[#000000CC]">Số lượng:</div>
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
                  v-model="quantity"
                  @keypress="onlyNumber($event)"
                  @input="watchQuantity()"
                />
                <button
                  @click="increase()"
                  class="border-y border-r border-[#00000033] w-8"
                >
                  +
                </button>
              </div>
              <div class="text-sm text-[#00000066] ml-5">
                {{ product.quantity }} sản phẩm có sẵn
              </div>
            </div>
            <button
              @click="addToCart()"
              class="bg-[#676767] text-white font-bold uppercase px-3 py-2 text-xs mt-3"
            >
              Thêm vào giỏ
            </button>
          </div>
        </div>
      </div>
    </div>
  </Transition>
  <!-- <Transition name="bounce">
    <div
      v-if="successDialog"
      class="fixed inset-0 w-full z-50 flex items-center justify-center h-[100vh] overflow-hidden"
    >
      <div
        class="relative rounded-md bg-white -bg-opacity-5 w-[450px] p-20 flex flex-col items-center justify-center gap-5"
      >
        <Icon
          name="clarity:success-line"
          size="70"
          color="#14A44D"
          class="bg-[#14A44D] bg-opacity-10 rounded-full p-2"
        />
        <p class="text-[20px]">Thêm giỏ hàng thành công</p>
      </div>
    </div>
  </Transition> -->
</template>

<style scoped>
.bounce-enter-active {
  animation: bounce-in 0.5s;
}
.bounce-leave-active {
  animation: bounce-in 0.5s reverse;
}
@keyframes bounce-in {
  0% {
    transform: scale(0);
  }
  /* 50% {
    transform: scale(0.5);
  } */
  100% {
    transform: scale(1);
  }
}
</style>
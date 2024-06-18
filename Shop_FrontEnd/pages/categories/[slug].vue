<script setup lang="ts">
import MainLayout from "~/layouts/default.vue";
import ProductCard from "~/components/globals/card/product.vue";
import { useCategoryService } from "~/services/category";
import { useProductService } from "~/services/product";
import { useSessionStore } from "~/stores/session";

const route = useRoute();
const categoryService = useCategoryService();
const productService = useProductService();
const sessionStore = useSessionStore();

const category = ref({});
const products = ref([]);
const displayProducts = ref([]);

const startIndex = ref(0);
const lastIndex = ref(8);

onMounted(async () => {
  sessionStore.isLoading = true;
  await categoryService
    .getCategoryBySlug(route.params.slug)
    .then((res) => {
      category.value = res.data;
    })
    .catch((e) => {
      console.log(e);
    });

  await productService
    .getProductByCategoryId(category.value.id)
    .then((res) => {
      products.value = res.data;
      displayProducts.value = products.value.slice(
        startIndex.value,
        lastIndex.value
      );
    })
    .catch((e) => {
      console.log(e);
    });

  setTimeout(() => {
    sessionStore.isLoading = false;
  }, 200);
});

const loadMore = () => {
  lastIndex.value = lastIndex.value + 4;
  if (lastIndex.value >= products.value.length) {
    lastIndex.value = products.value.length;
  }

  displayProducts.value = products.value.slice(
    startIndex.value,
    lastIndex.value
  );
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
        <NuxtLink class="text-sm text-[#676767] italic font-light uppercase">{{
          category.name
        }}</NuxtLink>
      </ul>
    </div>
    <div class="w-[1200px] mx-auto">
      <h2 class="uppercase text-center text-2xl text-[#676767] font-bold">
        {{ category.name }}
      </h2>
      <div class="grid grid-cols-4 gap-10 mt-10">
        <div v-for="product in displayProducts" :key="product">
          <ProductCard :product="product" />
        </div>
      </div>
      <div
        v-if="lastIndex < products.length"
        class="text-center text-xl text-[#676767] leading-6 font-medium mt-10 cursor-pointer hover:text-[#9E9E9E]"
        @click="loadMore()"
      >
        Xem thêm
      </div>
    </div>
  </MainLayout>
</template>
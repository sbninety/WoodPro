<script setup lang="ts">
import MainLayout from "~/layouts/default.vue";
import CategoryCard from "~/components/globals/card/category.vue";
import ProductCard from "~/components/globals/card/product.vue";
import { register } from "swiper/element/bundle";
import { useCategoryService } from "~/services/category";
import { useProductService } from "~/services/product";
import { useSessionStore } from "~/stores/session";

register();
const categoryService = useCategoryService();
const productService = useProductService();
const sessionStore = useSessionStore();

const categories = ref([]);
const specialProducts = ref([]);
const saleProducts = ref([]);
const suggestProducts = ref([]);

onMounted(async () => {
  sessionStore.isLoading = true;
  const res = await Promise.all([
    categoryService.getAll(),
    productService.getSpeical(),
    productService.getSale(),
    productService.getSuggest(),
  ]);

  categories.value = res[0].data;
  specialProducts.value = res[1].data;
  saleProducts.value = res[2].data;
  suggestProducts.value = res[3].data;

  setTimeout(() => {
    sessionStore.isLoading = false;
  }, 200);
});
</script>
<template>
  <MainLayout>
    <div id="IndexPage" class="w-[1200px] mx-auto px-2">
      <div id="Slider">
        <swiper-container
          :slides-per-view="1"
          :navigation="true"
          :loop="true"
          :pagination="{ clickable: true }"
          :autoplay="{
            delay: 2500,
            disableOnInteraction: false,
          }"
        >
          <swiper-slide><img src="/slide 1.png" /></swiper-slide>
          <swiper-slide><img src="/slide 2.png" /></swiper-slide>
          <swiper-slide><img src="/slide 3.png" /></swiper-slide>
          <swiper-slide><img src="/slide 4.png" /></swiper-slide>
          <swiper-slide><img src="/slide 5.png" /></swiper-slide>
        </swiper-container>
      </div>
      <div class="text-center border-b border-black pt-16 py-10">
        <p class="text-xl">CHÀO MỪNG ĐẾN VỚI WOODPRO</p>
        <p class="text-xl font-bold italic mt-3">
          Nơi hội tụ tin cậy về lĩnh vực sản xuất đồ gỗ nội thất
        </p>
      </div>
      <div id="Categories" class="mt-5">
        <h4 class="text-2xl text-[#676767] font-bold">Danh Mục Sản Phẩm</h4>
        <div class="grid grid-cols-5 gap-5 mt-5">
          <div v-for="category in categories" :key="category">
            <CategoryCard :category="category" />
          </div>
        </div>
      </div>
      <div class="mt-10 border-t border-black">
        <img src="/banner1.png" />
      </div>
      <div class="mt-5 border-t border-black pt-5">
        <h4 class="text-2xl text-[#676767] font-bold">Sản Phẩm Nổi Bật</h4>
        <div class="grid grid-cols-4 gap-10 mt-5">
          <div v-for="product in specialProducts" :key="product">
            <ProductCard :product="product" />
          </div>
        </div>
      </div>
      <div class="mt-10 border-t border-black pt-5">
        <img src="/banner2.png" />
      </div>
      <div class="mt-5 border-t border-black pt-5">
        <h4 class="text-2xl text-[#676767] font-bold">Sản Phẩm Ưu Đãi</h4>
        <div class="grid grid-cols-4 gap-10 mt-5">
          <div v-for="product in saleProducts" :key="product">
            <ProductCard :product="product" />
          </div>
        </div>
      </div>
      <div class="mt-10 border-t border-black pt-5">
        <img src="/banner3.png" />
      </div>
      <div class="mt-5 border-t border-black pt-5">
        <h4 class="text-2xl text-[#676767] font-bold">Có Thể Bạn Cũng Thích</h4>
        <div class="grid grid-cols-4 gap-10 mt-5">
          <div v-for="product in suggestProducts" :key="product">
            <ProductCard :product="product" />
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>
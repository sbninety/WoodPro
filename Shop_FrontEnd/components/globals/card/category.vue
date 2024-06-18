<script setup lang="ts">
const props = defineProps(["category"]);
const { category } = toRefs(props);
</script>
<template>
  <NuxtLink :to="`/categories/${category.slug}`">
    <img :src="category.image" width="210" class="h-[210px]" />
    <h3 class="mt-2.5 text-sm text-[#676767] leading-[18px] uppercase">
      {{ category.name }}
    </h3>
  </NuxtLink>
</template>
<script setup>
const props = defineProps(["comment"]);
const { comment } = toRefs(props);
</script>
<template>
  <div class="flex gap-[15px] py-5 border-b">
    <div>
      <img src="/avatar.png" />
    </div>
    <div>
      <p>{{ comment.name }}</p>
      <div class="flex">
        <div v-for="i in 5" :key="i">
          <Icon
            :name="
              i <= comment.rate ? 'ic:outline-star' : 'ic:outline-star-border'
            "
            size="18"
            color="#FFC107"
          />
        </div>
      </div>
      <p class="text-sm text-[#00000080]">{{ comment.created_at }}</p>
      <p>
        {{ comment.content }}
      </p>
      <div class="flex flex-wrap gap-2 mt-2">
        <div v-for="i in comment.images" :key="i">
          <img :src="i.path" width="80" />
        </div>
      </div>
    </div>
  </div>
</template>
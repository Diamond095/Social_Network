<template>
  <div class="mb-8 pb-8 border-b border-gray-400">
    <h1 class="text-xl">{{ post.title }}</h1>
    <img
      class="my-3 mx-auto"
      v-if="post.image_url"
      :src="post.image_url"
      :alt="post.title"
    />
    <p >{{ post.content }}</p>
    <p class="text-right text-sm">{{ post.date }}</p>
  </div>
</template>
<script>
export default {
  name: "Post",
  props: ["post"],
};
</script>
<style scoped></style>

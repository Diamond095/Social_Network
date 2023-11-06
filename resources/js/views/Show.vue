<template>
  <div class="w-96 mx-auto">
    <Stat :stats="stats"></Stat>
    <div v-if="posts">
      <h1 class="mb-8 pb-8 border-b border-gray-400">Публикации</h1>
      <Post v-for="post in posts" :post="post"></Post>
    </div>
  </div>
</template>
<script>
import Post from "./user/Post.vue";
import Stat from "./user/Stat.vue";
export default {
  name: "Show",
  props: ["post"],
  data() {
    return {
      posts: null,
      userId: this.$route.params.id,
      stats: [],
    };
  },
  components: {
    Post,
    Stat,
  },
  mounted() {
    this.getPosts();
    this.getStats();
  },
  methods: {
    getPosts() {
      axios.get(`/api/user/${this.userId}/posts`).then((res) => {
        this.posts = res.data.data;
      });
    },
    getStats() {
      axios.post("/api/user/stats", { user_id: this.userId }).then((res) => {
        this.stats = res.data.data;
      });
    },
  },
};
</script>
<style scoped></style>

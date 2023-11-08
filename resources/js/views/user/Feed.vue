<template>
    <div class="w-96 mx-auto">
      <div v-if="posts">
        <h1 class="mb-8 pb-8 border-b border-gray-400">Публикации</h1>
        <Post v-for="post in posts" :post="post"></Post>
      </div>
    </div>
  </template>
 <script>
 import Post from "./Post.vue";
  export default {
    name: "Show",
    props: ["post"],
    data() {
      return {
        posts: null,
      };
    },
    components: {
      Post,
    },
    mounted() {
      this.getPosts();
    },
    methods: {
      getPosts() {
        axios.get(`/api/feed`).then((res) => {
          this.posts = res.data.data;
        })
        .catch(e=>{
        if(e.response.status==401){
          localStorage.removeItem('x_xsrf_token');
        }
      });;
      }
    },
  };
  </script>
  <style scoped></style>
  
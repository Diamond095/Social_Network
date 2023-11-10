<template>
  <div class="bg-gray-50 w-96 mx-auto">
    <Stat :userId="'auth'" :stats="stats"></Stat>
    <div class="mb-4">
      <div class="mb-3">
        <input
          v-model="title"
          class="w-96 rounded-3xl border p-2 border-slate-300"
          type="text"
          placeholder="заголовок"
        />
      </div>
      <div v-if="errors.title">
        <p v-for="error in errors.title" class="text-sm mb-2 text-red-500">
          {{ error }}
        </p>
      </div>

      <div class="mb-3">
        <textarea
          v-model="content"
          class="w-96 rounded-3xl border p-2 border-slate-300"
          placeholder="текст поста"
        ></textarea>
      </div>
      <div v-if="errors.content">
        <p v-for="error in errors.content" class="text-sm mb-2 text-red-500">
          {{ error }}
        </p>
      </div>

      <div class="flex mb-3 items-center items-center">
        <div>
          <input @change="storeImage" ref="file" accept=".png, .jpeg, .jpg" type="file" class="hidden" />
          <a
            href="#"
            class="block p-2 w-32 text-center text-sm rounded-3xl bg-sky-500 text-white"
            @click.prevent="selectFile()"
            >Изображение</a
          >
        </div>

        <div v-if="image">
          <a href="#" class="ml-3" @click.prevent="image = null">Отмена</a>
        </div>
      </div>
      <div v-if="image">
        <img :src="image.url" alt="post-picture" />
      </div>
      <div>
        <a
          href="#"
          @click="createNewPost"
          class="block p-2 w-32 text-center rounded-3xl bg-green-600 text-white hover:bg-white hover:border hover:border-green-600 hover:text-green-600 box-border ml-auto"
          >Опубликовать</a
        >
      </div>
    </div>
    <div v-if="posts">
      <h1 class="mb-8 pb-8 border-b border-gray-400">Твои публикации</h1>
      <Post v-for="post in posts" :deleteStatus="true" :post="post"></Post>
    </div>
  </div>
</template>

<script>
import Post from "./Post.vue";
import Stat from "./Stat.vue";
import axios from "axios";

export default {
  name: "Personal",

  data() {
    return {
      title: "",
      content: "",
      image: null,
      posts: [],
      errors: [],
      stats: [],
    };
  },
  async mounted() {
    this.getPosts();
    this.getStats();
  },
  components: {
    Post,
    Stat,
  },

  methods: {
    selectFile() {
      this.fileInput = this.$refs.file;
      this.fileInput.click();
    },
    getPosts() {
      axios
        .get("/api/posts")
        .then((res) => {
          this.posts = res.data.data;
        })
        .catch((e) => {
          if (e.response.status == 401) {
            localStorage.removeItem("x_xsrf_token");
          }
        });
    },
    storeImage(e) {
      let file = e.target.files[0];
      let formData = new FormData();
      formData.append("image", file);
      axios.post("/api/post_image", formData).then((res) => {
        this.image = res.data.data;
        this.errors.image = null;
      });
    },
    createNewPost() {
      const id = this.image ? this.image.id : null;
      axios.get("/sanctum/csrf-cookie").then((res) => {
        axios
          .post("/api/post", {
            title: this.title,
            content: this.content,
            image_id: id,
          })
          .then((res) => {
            this.title = "";
            this.content = "";
            this.image = null;
            this.posts.unshift(res.data.data);
            this.errors = [];
          })
          .catch((e) => {
            this.errors = e.response.data.errors;
          });
      });
    },
    getStats() {
      axios.post("/api/user/stats", { user_id: null }).then((res) => {
        this.stats = res.data.data;
      });
    },
  },

};
</script>

<style scoped></style>

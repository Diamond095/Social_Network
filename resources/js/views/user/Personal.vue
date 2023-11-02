<template>
  <div class="w-96 mx-auto">
    <div class="mb-4">
      <div class="mb-3">
        <input
          v-model="title"
          class="w-96 rounded-3xl border p-2 border-slate-300"
          type="text"
          placeholder="title"
        />
      </div>
      <div class="mb-3">
        <textarea
          v-model="content"
          class="w-96 rounded-3xl border p-2 border-slate-300"
          placeholder="content"
        ></textarea>
      </div>
      <div class="flex mb-3 items-center items-center">
        <div>
          <input @change="storeImage" ref="file" type="file" class="hidden" />
          <a
            href="#"
            class="block p-2 w-16 text-center text-sm rounded-3xl bg-sky-500 text-white"
            @click.prevent="selectFile()"
            >Image</a>
        </div>
        <div v-if="image">
          <a href="#" class="ml-3" @click.prevent="image = null">Cancel</a>
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
          >Publish</a>
      </div>
    </div>
    <div v-if="posts">
            <h1 class="mb-8 pb-8 border-b border-gray-400">Posts</h1>
            <Post v-for="post in posts" :post="post"></Post>
        </div>
  </div>
</template>

<script>
import { getTransitionRawChildren } from 'vue';
import Post from './Post.vue';
export default {
  name: "Personal",

  data() {
    return {
      title: "",
      content: "",
      image: null,
      posts:[]
    };
  },
   mounted() {
     this.getPosts()
  },
  components:{
    Post
  },
  methods: {
    selectFile() {
      this.fileInput = this.$refs.file;
      this.fileInput.click();
    },
    getPosts(){
      axios.get('/api/posts').
      then(
        res=>{
        this.posts=res.data.data
        }
      )
    },
    storeImage(e) {
      let file = e.target.files[0];
      let formData = new FormData();
      formData.append("image", file);
      axios.post("/api/post_image", formData).then((res) => {
        this.image = res.data.data;
      });
    },
    createNewPost() {
      const id = this.image.id ? this.image.id : null;
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
            this.image_id = "";
            this.content.unshift(res.data.data);
          });
      });
    },
  },
};
</script>

<style scoped></style>

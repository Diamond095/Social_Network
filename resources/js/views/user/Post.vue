<template>
  <div class="mb-8 pb-8 border-b border-gray-400">
    <h1 class="text-xl">{{ post.title }}</h1>
    <router-link
      class="text-sm text-gray-800"
      :to="{ name: 'user.show', params: { id: post.user.id } }"
      >{{ post.user.name }}</router-link
    >
    <img
      class="my-3 mx-auto"
      v-if="post.image_url"
      :src="post.image_url"
      :alt="post.title"
    />
    <p>{{ post.content }}</p>

    <div v-if="post.reposted_post" class="bg-gray-100 p-4 my-4 border border-gray-200">
      <h1 class="text-xl">{{ post.reposted_post.title }}</h1>

      <router-link
        class="text-sm text-gray-800"
        :to="{ name: 'user.show', params: { id: post.reposted_post.user.id } }"
        >{{ post.reposted_post.user.name }}</router-link
      >
      <img
        class="my-3 mx-auto"
        v-if="post.reposted_post.image_url"
        :src="post.reposted_post.image_url"
        :alt="post.reposted_post.title"
      />
      <p>{{ post.reposted_post.content }}</p>
    </div>
    <div class="flex justify-between mt-2 items-center">
      <div class="flex">
        <div class="flex">
          <svg
            @click.prevent="toggleLike(post)"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            :class="[
              'mr-2 stroke-sky-500 cursor-pointer hover:fill-sky-500 w-6 h-6',
              post.is_liked ? 'fill-sky-500' : 'fill-white',
            ]"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"
            />
          </svg>
          <p>{{ post.likes_count }}</p>
        </div>
        <div class="flex">
          <svg
            @click.prevent="openRepost()"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            :class="[
              'mx-2 stroke-sky-500 cursor-pointer hover:fill-sky-500 w-6 h-6 fill-white',
            ]"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"
            />
          </svg>
          <p>{{ post.reposts_count }}</p>
        </div>
      </div>
      <p class="text-right text-slate-500 text-sm">{{ post.date }}</p>
    </div>

    <div v-if="is_repost" class="mt-4">
      <div>
        <input
          v-model="title"
          class="w-96 mb-3 rounded-3xl border p-2 border-slate-300"
          type="text"
          placeholder="заголовок"
        />
      </div>
      <div v-if="errors.title">
        <p v-for="error in errors.title" class="text-sm mb-2 text-red-500">
          {{ error }}
        </p>
      </div>
      <div>
        <textarea
          v-model="content"
          class="w-96 mb-3 rounded-3xl border p-2 border-slate-300"
          placeholder="текст поста"
        ></textarea>
      </div>
      <div v-if="errors.content">
        <p v-for="error in errors.content" class="text-sm mb-2 text-red-500">
          {{ error }}
        </p>
      </div>
      <div>
        <a
          @click.prevent="repost(post)"
          href="#"
          class="block p-2 w-24 text-center rounded-3xl bg-green-600 text-white hover:bg-white hover:border hover:border-green-600 hover:text-green-600 box-border ml-auto"
          >Репост</a
        >
      </div>
    </div>
    <div v-if="post.comments_count > 0" class="mt-4">
      <p class="cursor-pointer" v-if="!isShowed" @click="getComments(post)">
        Показать коментарии
      </p>
      <p class="cursor-pointer" v-if="isShowed" @click="isShowed = false">
        Скрыть комментарии
      </p>
      <div v-if="comments && isShowed">
        <div v-for="comment in comments" class="mt-4 pt-4 border-t border-gray-300">
          <div class="flex mb-2">
            <p class="text-sm mr-2">{{ comment.user.name }}</p>
            <p @click.prevent="setParentId(comment)" class="text-sm text-sky-500 cursor-pointer">
              Ответить
            </p>
          </div>
          <router-link class="text-sm text-gray-800" :to="{name: 'user.show', params: {id: comment.answered_for_user.id}} ">{{ comment.answered_for_user.name }}</router-link>
          <p>{{ comment.body }}</p>
          <p class="mt-2 text-right text-sm">{{ comment.date }}</p>
        </div>
      </div>
    </div>
    <div class="mt-4">
      <div class="mb-3">
        <div class="flex items-center">
          <p v-if="comment" class="mr-2">Ответ пользователю {{ comment.user.name }}</p>
          <p
            v-if="comment"
            @click="comment = null"
            class="cursor-pointer text-sky-400 text-sm"
          >
            Отмена
          </p>
        </div>
      </div>
      <div class="flex mb-3">
        <textarea
          v-model="body"
          class="w-88 rounded-3xl border p-2 border-slate-300"
          type="text"
          placeholder="Добавить комментарий"
        />
        <svg
          @click.prevent="storeComment(post)"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
          class="w-6 h-6 mt-2 ml-2"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"
          />
        </svg>
      </div>
      <div>
        <div v-if="errors.body">
          <p v-for="error in errors.body" class="text-sm mb-2 text-red-500">
            {{ error }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "Post",
  props: ["post"],
  data() {
    return {
      is_repost: false,
      title: "",
      content: "",
      body: "",
      repostedId: null,
      errors: [],
      comments: [],
      isShowed: false,
      comment: null,
    };
  },
  methods: {
    toggleLike(post) {
      axios.get(`/api/post/${post.id}/toggle_like`).then((res) => {
        post.is_liked = res.data.is_liked;
        post.likes_count = res.data.likes_count;
      });
    },
    openRepost() {
      if (this.$route.name == "user.personal") return;
      this.is_repost = !this.is_repost;
    },
    repost(post) {
      axios
        .post(`/api/post/${post.id}/repost`, {
          title: this.title,
          content: this.content,
        })
        .then((res) => {
          this.title = "";
          this.content = "";
          this.errors = [];
        })
        .catch((e) => {
          this.errors = e.response.data.errors;
        });
    },
    setParentId(comment){
            this.comment = comment
        },
    storeComment(post) {
      console.log(this.comment)
      const commentId = this.comment ? this.comment.id : null;
      axios
        .post(`/api/post/${post.id}/comment`, {
          body: this.body,
          parent_id: commentId
        })
        .then((res) => {
          this.body = "";
          this.errors = [];
          this.comments.unshift(res.data.data);
          this.parentId = null;
          post.comments_count++;
          this.comment=null;
        })
        .catch((e) => {
          this.errors = e.response.data.errors;
        });
    },
    getComments(post) {
      axios.get(`/api/post/${post.id}/comments`).then((res) => {
        this.comments = res.data.data;
        this.isShowed = true;
      });
    },
    
  },
};
</script>
<style scoped></style>

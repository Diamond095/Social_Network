<template>
  <div v-if="post.title!=null"  class="mb-8 pb-8 border-b border-gray-400">
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
          <svg
          v-if="deleteStatus==true"
            @click.prevent="toggleModal()"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-6 ml-2 stroke-red-500 h-6"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
            />
          </svg>
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
            <router-link
              :to="{ name: 'user.show', params: { id: comment.user.id } }"
              class="text-sm mr-2"
              >{{ comment.user.name }}</router-link
            >
            <p
              @click.prevent="setParentId(comment)"
              class="text-sm text-sky-500 cursor-pointer"
            >
              Ответить
            </p>
          </div>
          <p>
            <span v-if="comment.answered_for_user" class="text-sky-400"
              ><router-link
                :to="{ name: 'user.show', params: { id: comment.answered_for_user.id } }"
                >{{ comment.answered_for_user.name }},</router-link
              ></span
            >
            {{ comment.body }}
          </p>
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
  <div
    v-if="modalStatus"
    class="relative w-96 z-10"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
  >
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
      <div
        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
      >
        <div
          class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
        >
          <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div
                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
              >
                <svg
                  class="h-6 w-6 text-red-600"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  aria-hidden="true"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
                  />
                </svg>
              </div>
              <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                <h3
                  class="text-base font-semibold leading-6 text-gray-900"
                  id="modal-title"
                >
                  Вы уверены что хотите удалить пост?
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Ваш пост будет удален навсегда, без возможности восстановления
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
            <button
              @click.prevent="deletePost(post)"
              type="button"
              class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
            >
              Удалить пост
            </button>
            <button
              @click.prevent="toggleModal()"
              type="button"
              class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
            >
              Отмена
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";

export default {
  name: "Post",
  props: ["post", "deleteStatus"],
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
      modalStatus: false,
    };
  },
  methods: {
    async toggleLike(post) {
      await axios.post(`/api/post/${post.id}/toggle_like`).then((res) => {
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
    setParentId(comment) {
      this.comment = comment;
    },
    storeComment(post) {
      console.log(this.comment);
      const commentId = this.comment ? this.comment.id : null;
      axios
        .post(`/api/post/${post.id}/comment`, {
          body: this.body,
          parent_id: commentId,
        })
        .then((res) => {
          this.body = "";
          this.errors = [];
          this.comments.unshift(res.data.data);
          this.parentId = null;
          post.comments_count++;
          this.comment = null;
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
    toggleModal() {
      this.modalStatus = !this.modalStatus;
    },
    async deletePost(post) {
      await axios.get(`/api/post/delete/${post.id}`).then((res) => {
        post.title=null
      });
      this.modalStatus = false;
    },
  },
};
</script>
<style scoped></style>

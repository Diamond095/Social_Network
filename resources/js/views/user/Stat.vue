<template>
  <div class="flex justify-between mb-8 text-sm text-center">
    <router-link
      :to="{
        name: 'user.subscribers',
        params: {
          id: userId,
        },
      }"
    >
      <div>
        Подписчики
        <div>
          {{ stats.subscribers_count }}
        </div>
      </div>
    </router-link>
    <div>
      Лайки
      <div>
        {{ stats.likes_count }}
      </div>
    </div>
    <div>
      Посты
      <div>
        {{ stats.posts_count }}
      </div>
    </div>
    <router-link
      :to="{
        name: 'user.followings',
        params: {
          id: userId,
        },
      }"
    >
      <div>
        Подписки
        <div>
          {{ stats.followings_count }}
        </div>
      </div>
    </router-link>
  </div>
</template>
<script>
export default {
  name: "Stat",
  props: ["stats", "userId"],
  async mounted() {
    await this.getUserId();
  },
  data() {
    return {
      id: null,
    };
  },
  methods: {
    async getUserId() {
      if (this.userId == -1) {
       await  axios.get("/api/user/id").then((res) => {
          this.id = res.data.data;
        });
        console.log(this.id);
      }
    },
  },
};
</script>

<template>
  <div class="w-96 mx-auto">
    <div v-if="users">
      <div
        class="flex justify-between items-center mb-6 pb-6 border-b border-gray-400"
        v-for="user in users"
      >
        <router-link
          :to="{
            name: 'user.show',
            params: {
              id: user.id,
            },
          }"
        >
          <p>{{ user.id }}</p>
          <p>{{ user.name }}</p>
          <p>{{ user.email }}</p>
        </router-link>
        <div class="block p-2 w-32 text-center text-sm rounded-3xl bg-sky-500 text-white">
          <a @click.prevent="toggleFollowing(user)" href="#"> Follow </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Index",

  data() {
    return {
      users: [],
    };
  },
  async mounted() {
    await this.getUsers();
  },
  methods: {
    getUsers() {
      axios.get("/api/users").then((res) => {
        this.users = res.data.data;
      });
    },
    toggleFollowing(user) {
        axios.post(`/api/user/${user.id}/toggle_following`).then((res) => {
        this.posts = res.data.data;
      });
    },
  },
};
</script>

<style scoped></style>

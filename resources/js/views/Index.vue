<template>
  <div class="bg-gray-50 w-96 mx-auto">

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
          <p>{{ user.name }}</p>
        </router-link>
        <div>
            <a @click.prevent="toggleFollowing(user)"
                       :class="['block p-2 w-32 text-center text-sm rounded-3xl', user.is_following ? 'bg-white text-sky-500 border border-sky-500' : 'bg-sky-500 text-white']" href="#">
                        {{ user.is_following ? 'Отписаться' : 'Подписаться'}}</a>
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
      })
      .catch(e=>{
        if(e.reaponse.status==401){
          localStorage.removeItem('x_xsrf_token');
        }
      });;
    },
    toggleFollowing(user) {
        axios.post(`/api/user/${user.id}/toggle_following`).then(res => {
            user.is_following=res.data.is_following;
      });
    },
  },
};
</script>

<style scoped></style>

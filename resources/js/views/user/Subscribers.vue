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
            <p>{{ user.name }}</p>
          </router-link>
          <div>
              <a v-if="user.is_following!=null" @click.prevent="toggleFollowing(user)"
                         :class="['block p-2 w-32 text-center text-sm rounded-3xl', user.is_following ? 'bg-white text-sky-500 border border-sky-500' : 'bg-sky-500 text-white']" href="#">
                          {{ user.is_following ? 'Отписаться' : 'Подписаться'}}</a>
                          <a v-if="user.is_following==null"
                         class="block p-2 w-32 text-center text-sm rounded-3xl bg-white text-sky-500 border border-sky-500" href="#">
                          Это вы</a>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "Followings",
  
    data() {
      return {
        users: [],
      };
    },
    async mounted() {
       this.getSubscribers();
    },
    methods: {
      getSubscribers() {
        axios.post("/api/user/stats/subscribers", {user_id:this.$route.params.id}).then((res) => {
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
  
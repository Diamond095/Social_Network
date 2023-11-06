<template>
  <div>
      <div class="flex justify-between pb-4 pt-4 w-96 mx-auto">

          <router-link v-if="!token" :to="{ name: 'user.login'}">Войти</router-link>
          <router-link v-if="token" :to="{ name: 'user.personal'}">Твоя страница</router-link>
          <router-link v-if="!token" :to="{ name: 'user.registration'}">Регистрация</router-link>
          <router-link v-if="token" :to="{ name: 'user.users'}">Пользователи</router-link>
          <router-link v-if="token" :to="{ name: 'user.feed'}">Новости</router-link>
          <a v-if="token" @click.prevent="logout" href="#">Выйти</a>
      </div>
      <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: "App",
  data() {
      return {
          token: null
      }
  },

  mounted() {
      this.getToken()
  },

  watch: {
      $route(to, from) {
          this.getToken()
      }
  },

  methods: {

      getToken() {
          this.token = localStorage.getItem('x_xsrf_token')
      },

      logout() {
          axios.post('/logout')
              .then( res => {
                  localStorage.removeItem('x_xsrf_token')
                  this.$router.push({name: 'user.login'})
              })
      }
  }
}
</script>

<style scoped>
</style>

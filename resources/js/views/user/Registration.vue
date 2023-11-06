<template>
  <div>
    <div class="w-96 mx-auto">
      <div>
        <input
          v-model="name"
          type="name"
          placeholder="имя"
          class="w-96 p-1 mb-2 border border-inherit rounded-lg"
        />
      </div>
      <div v-if="errors.name">
        <p v-for="error in errors.name" class="text-sm mb-2 text-red-500">
          {{ error }}
        </p>
      </div>
      <div>
        <input
          v-model="email"
          type="email"
          placeholder="электронная почта"
          class="w-96 p-1 mb-2 border border-inherit rounded-lg"
        />
      </div>
      <div v-if="errors.email">
        <p v-for="error in errors.email" class="text-sm mb-2 text-red-500">
          {{ error }}
        </p>
      </div>
      <div>
        <input
          v-model="password"
          type="password"
          placeholder="пароль"
          class="w-96 p-1 mb-2 border border-inherit rounded-lg"
        />
      </div>
      <div v-if="errors.password">
        <p v-for="error in errors.password" class="text-sm mb-2 text-red-500">
          {{ error }}
        </p>
      </div>
      <div>
        <input
          v-model="password_confirmation"
          type="password"
          placeholder="подтверждение пароля"
          class="w-96 p-1 mb-2 border border-inherit rounded-lg"
        />
      </div>
      <div v-if="errors.password_confirmation">
        <p
          v-for="error in errors.password_confirmation"
          class="text-sm mb-2 text-red-500"
        >
          {{ error }}
        </p>
      </div>
      <input
        @click.prevent="register"
        type="submit"
        value="register"
        class="block float-right mx-auto w-32 p-1 bg-sky-400 text-white rounded-lg"
      />
    </div>
  </div>
</template>

<script>
export default {
  name: "Registration",

  data() {
    return {
      name: null,
      email: null,
      password: null,
      password_confirmation: null,
      errors: [],
    };
  },

  methods: {
    register() {
      if (this.password != this.password_confirmation) {
        this.errors.password_confirmation = [];
        this.errors.password_confirmation.push("Пароли не совподают");
        return;
      }
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/register", {
            name: this.name,
            email: this.email,
            password: this.password,
            password_confirmation: this.password_confirmation,
          })
          .then((res) => {
            localStorage.setItem("x_xsrf_token", res.config.headers["X-XSRF-TOKEN"]);
            this.$router.push({ name: "user.personal" });
          })
          .catch((e) => {
            this.errors = e.response.data.errors;
          });
      });
    },
  },
};
</script>

<style scoped></style>

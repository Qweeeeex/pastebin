<template>
  <div class="register">
    <h2>Регистрация</h2>
    <form @submit.prevent="register">
      <div>
        <label for="login">Логин:</label>
        <input type="text" v-model="registerData.login" required />
      </div>
      <div>
        <label for="password">Пароль:</label>
        <input type="password" v-model="registerData.password" required />
      </div>
      <button type="submit">Зарегистрироваться</button>
    </form>

    <p v-if="errorMessage">{{ errorMessage }}</p>
    <p v-if="successMessage">{{ successMessage }}</p>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'RegisterView',
  data() {
    return {
      registerData: {
        login: '',
        password: '',
      },
      errorMessage: '',
      successMessage: '',
    }
  },
  methods: {
    async register() {
      try {
        await axios.post('/users/register', {
          login: this.registerData.login,
          password: this.registerData.password,
        })

        this.successMessage = 'Регистрация прошла успешно. Теперь вы можете войти.'
        this.errorMessage = ''
      } catch (error) {
        this.errorMessage = 'Ошибка регистрации. Попробуйте снова.'
        this.successMessage = ''
      }
    },
  },
}
</script>

<style scoped>
.register {
  max-width: 400px;
  margin: auto;
}
button {
  margin-top: 10px;
}
</style>
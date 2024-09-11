<template>
  <div class="login">
    <h2>Авторизация</h2>
    <form @submit.prevent="login">
      <div>
        <label for="login">Логин:</label>
        <input type="text" v-model="loginData.login" required />
      </div>
      <div>
        <label for="password">Пароль:</label>
        <input type="password" v-model="loginData.password" required />
      </div>
      <button type="submit">Войти</button>
    </form>

    <p v-if="errorMessage">{{ errorMessage }}</p>
  </div>
</template>

<script>
import { ref } from 'vue'
import axios from 'axios'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

export default {
  name: 'LoginView',
  setup() {
    const store = useStore()
    const router = useRouter()

    const loginData = ref({
      login: '',
      password: '',
    })

    const errorMessage = ref('')

    const login = async () => {
      try {
        const response = await axios.post('/auth/login', {
          login: loginData.value.login,
          password: loginData.value.password,
        })

        store.commit('setToken', response.data.access_token)

        router.push('/')
      } catch (error) {
        errorMessage.value = 'Ошибка авторизации. Проверьте логин и пароль.'
      }
    }

    return {
      loginData,
      login,
      errorMessage,
    }
  },
}
</script>

<style scoped>
.login {
  max-width: 400px;
  margin: auto;
}
button {
  margin-top: 10px;
}
</style>
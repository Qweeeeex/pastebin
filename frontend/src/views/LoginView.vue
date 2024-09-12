<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h2 class="text-center">Авторизация</h2>
            <form @submit.prevent="login">
              <div class="form-group">
                <label for="login">Логин:</label>
                <input
                    type="text"
                    id="login"
                    class="form-control"
                    v-model="loginData.login"
                    required
                />
              </div>
              <div class="form-group mt-3">
                <label for="password">Пароль:</label>
                <input
                    type="password"
                    id="password"
                    class="form-control"
                    v-model="loginData.password"
                    required
                />
              </div>
              <button type="submit" class="btn btn-primary btn-block mt-4">
                Войти
              </button>
            </form>
            <p v-if="errorMessage" class="text-danger mt-3 text-center">{{ errorMessage }}</p>
          </div>
        </div>
      </div>
    </div>
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
</style>
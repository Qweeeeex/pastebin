<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h2 class="text-center">Регистрация</h2>
            <form @submit.prevent="register">
              <div class="form-group">
                <label for="login">Логин:</label>
                <input
                    type="text"
                    id="login"
                    class="form-control"
                    v-model="registerData.login"
                    required
                />
              </div>
              <div class="form-group mt-3">
                <label for="password">Пароль:</label>
                <input
                    type="password"
                    id="password"
                    class="form-control"
                    v-model="registerData.password"
                    required
                />
              </div>
              <button type="submit" class="btn btn-primary btn-block mt-4">
                Зарегистрироваться
              </button>
            </form>
            <p v-if="errorMessage" class="text-danger mt-3 text-center">{{ errorMessage }}</p>
            <p v-if="successMessage" class="text-success mt-3 text-center">{{ successMessage }}</p>
          </div>
        </div>
      </div>
    </div>
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
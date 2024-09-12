<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <h2 class="text-center">Создать пасту</h2>
            <form @submit.prevent="createPaste">
              <div class="form-group">
                <label for="name">Название пасты:</label>
                <input type="text" id="name" class="form-control" v-model="name" required />
              </div>

              <div class="form-group mt-3">
                <label for="text">Текст пасты:</label>
                <textarea id="text" class="form-control" v-model="text" rows="5" required></textarea>
              </div>

              <div class="form-group mt-3">
                <label for="expirationTime">Время жизни:</label>
                <select id="expirationTime" class="form-control" v-model="expTime">
                  <option value="10M">10 минут</option>
                  <option value="1H">1 час</option>
                  <option value="3H">3 часа</option>
                  <option value="1D">1 день</option>
                  <option value="1W">1 неделя</option>
                  <option value="1M">1 месяц</option>
                </select>
              </div>

              <div class="form-group mt-3">
                <label for="availability">Доступность:</label>
                <select id="availability" class="form-control" v-model="availability">
                  <option value="public">Публичная</option>
                  <option value="unlisted">Доступ по ссылке</option>
                  <option value="private">Приватная</option>
                </select>
              </div>

              <button type="submit" class="btn btn-primary btn-block mt-4">Создать пасту</button>
            </form>
            <p v-if="errorMessage" class="text-danger mt-3">{{ errorMessage }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <RecentAndLoggedPastes />
</template>

<script>
import axios from 'axios'
import RecentAndLoggedPastes from "@/views/RecentAndLoggedPastes.vue"

export default {
  components: {RecentAndLoggedPastes},
  data() {
    return {
      name: '',
      text: '',
      expTime: '10M',
      availability: 'public',
      errorMessage: '',
    }
  },
  methods: {
    async createPaste() {
      try {
        const response = await axios.post('/pastes', {
          name: this.name,
          text: this.text,
          expirationTime: this.expTime,
          availability: this.availability,
        })
        this.$router.push(`/pastes/${response.data.id}`)
      } catch (error) {
        this.errorMessage = error
        console.error("Ошибка при создании пасты", error)
      }
    },
  },
}
</script>
<template>
  <div>
    <h2>Создать новую пасту</h2>
    <form @submit.prevent="createPaste">
      <div>
        <label>Название:</label>
        <input v-model="name" type="text" required />
      </div>
      <div>
        <label>Текст:</label>
        <textarea v-model="text" required></textarea>
      </div>
      <div>
        <label>Срок действия:</label>
        <select v-model="expTime">
          <option value="10M">10 минут</option>
          <option value="1H">1 час</option>
          <option value="3H">3 часа</option>
          <option value="1D">1 день</option>
          <option value="1W">1 неделя</option>
          <option value="1M">1 месяц</option>
        </select>
      </div>
      <div>
        <label>Доступность:</label>
        <select v-model="availability">
          <option value="public">Публичная</option>
          <option value="unlisted">Доступна по ссылке</option>
          <option value="private">Приватная</option>
        </select>
      </div>
      <button type="submit">Создать</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      name: '',
      text: '',
      expTime: '10M',
      availability: 'public',
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
        console.error("Ошибка при создании пасты", error)
      }
    },
  },
}
</script>
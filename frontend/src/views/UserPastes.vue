<template>
  <div>
    <h2>Ваши пасты</h2>
    <ul>
      <li v-for="paste in pastes" :key="paste.id">
        <router-link :to="`/pastes/${paste.id}`">{{ paste.name }}</router-link>
      </li>
    </ul>

    <div>
      <button @click="prevPage" :disabled="currentPage === 1">Назад</button>
      <button @click="nextPage" :disabled="pastes.length < pageSize">Вперед</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      pastes: [],
      currentPage: 1,
      pageSize: 10,
    }
  },
  created() {
    this.fetchUserPastes()
  },
  methods: {
    async fetchUserPastes() {
      try {
        const response = await axios.get(`/users/pastes?page=${this.currentPage}&limit=${this.pageSize}`)
        this.pastes = response.data
      } catch (error) {
        console.error("Ошибка при загрузке паст", error)
      }
    },
    nextPage() {
      this.currentPage++
      this.fetchUserPastes()
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--
        this.fetchUserPastes()
      }
    },
  },
}
</script>
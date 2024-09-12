<template>
  <div class="container mt-5">
    <h3>Мои пасты</h3>
    <ul class="list-group">
      <li v-for="paste in pastes" :key="paste.id" class="list-group-item">
        <router-link :to="`/pastes/${paste.id}`">{{ paste.name }}</router-link>
        <small v-if="paste.expTime" class="text-muted float-right"> Действительна до: {{ paste.expTime }}</small>
      </li>
    </ul>

    <nav aria-label="Page navigation" class="mt-4">
      <ul class="pagination justify-content-center">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <button class="page-link" @click="prevPage">Предыдущая</button>
        </li>
        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <button class="page-link" @click="nextPage">Следующая</button>
        </li>
      </ul>
    </nav>
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
      totalPages: 0,
    }
  },
  created() {
    this.fetchUserPastes()
  },
  methods: {
    async fetchUserPastes() {
      try {
        const response = await axios.get(`/users/pastes?page=${this.currentPage}&limit=${this.pageSize}`)
        this.pastes = response.data.items
        this.totalPages = Math.ceil(response.data.count / this.pageSize)
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
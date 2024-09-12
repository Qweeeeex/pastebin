<template>
  <div>
    <div class="container mt-5">
      <h3>Последние публичные пасты</h3>
      <ul class="list-group">
        <li v-for="paste in publicPastes" :key="paste.id" class="list-group-item">
          <router-link :to="`/pastes/${paste.id}`">{{ paste.name }}</router-link>
          <small v-if="paste.createdBy" class="text-muted float-right"> Автор: {{ paste.createdBy }}</small>
        </li>
      </ul>
      <div>
        <h3>Ваши последние пасты</h3>
        <ul v-if="isLoggedIn" class="list-group">
          <li v-for="paste in userPastes" :key="paste.id" class="list-group-item">
            <router-link :to="`/pastes/${paste.id}`">{{ paste.name }}</router-link>
            <small v-if="paste.createdBy" class="text-muted float-right">Автор: {{ paste.createdBy }}</small>
          </li>
        </ul>
        <h6 v-else>Вы не авторизованы :(</h6>
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { mapState } from 'vuex'

export default {
  data() {
    return {
      publicPastes: [],
      userPastes: [],
    }
  },
  computed: {
    ...mapState(['isLoggedIn']),
  },
  created() {
    this.fetchPublicPastes()
    if (this.isLoggedIn) {
      this.fetchUserPastes()
    }
  },
  methods: {
    async fetchPublicPastes() {
      try {
        const response = await axios.get(`/pastes?page=1&limit=10`)
        console.log(response)
        this.publicPastes = response.data.items
      } catch (error) {
        console.error("Ошибка при загрузке публичных паст", error)
      }
    },
    async fetchUserPastes() {
      try {
        const response = await axios.get('/users/pastes?page=1&limit=10')
        this.userPastes = response.data.items
      } catch (error) {
        console.error("Ошибка при загрузке пользовательских паст", error)
      }
    },
  },
}
</script>
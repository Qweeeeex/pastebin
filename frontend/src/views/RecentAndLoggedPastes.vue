<template>
  <div>
    <h3>Последние публичные пасты</h3>
    <ul>
      <li v-for="paste in publicPastes" :key="paste.id">
        <router-link :to="`/pastes/${paste.id}`">{{ paste.name }}</router-link>
      </li>
    </ul>

    <div v-if="userLoggedIn">
      <h3>Ваши последние пасты</h3>
      <ul>
        <li v-for="paste in userPastes" :key="paste.id">
          <router-link :to="`/pastes/${paste.id}`">{{ paste.name }}</router-link>
        </li>
      </ul>
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
    ...mapState(['userLoggedIn']),
  },
  created() {
    this.fetchPublicPastes()
    if (this.userLoggedIn) {
      this.fetchUserPastes()
    }
  },
  methods: {
    async fetchPublicPastes() {
      try {
        const response = await axios.get('/pastes')
        this.publicPastes = response.data
      } catch (error) {
        console.error("Ошибка при загрузке публичных паст", error)
      }
    },
    async fetchUserPastes() {
      try {
        const response = await axios.get('/user/pastes')
        this.userPastes = response.data
      } catch (error) {
        console.error("Ошибка при загрузке пользовательских паст", error)
      }
    },
  },
}
</script>
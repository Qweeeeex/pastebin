<template>
  <div v-if="paste">
    <h2>{{ paste.name }}</h2>
    <p>{{ paste.text }}</p>
    <p><strong>Доступность:</strong> {{ paste.availability }}</p>
    <p><strong>Срок действия:</strong> {{ paste.expirationTime }}</p>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      paste: null,
    }
  },
  props: {
    id: String,
  },
  created() {
    this.fetchPaste()
  },
  methods: {
    async fetchPaste() {
      try {
        const response = await axios.get(`/pastes/${this.id}`)
        this.paste = response.data
      } catch (error) {
        console.error("Ошибка при загрузке пасты", error)
      }
    },
  },
};
</script>
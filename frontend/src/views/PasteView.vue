<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div v-if="paste" class="card">
          <div class="card-body">
            <h2 class="card-title">{{ paste.name }}</h2>
            <p class="card-text">{{ paste.text }}</p>
            <p class="text-muted">Доступность: {{ paste.availability }}</p>
            <p class="text-muted">Доступно до: {{ paste.expTime }}</p>
            <p v-if="paste.createdBy" class="text-muted">Создал: {{ paste.createdBy}}</p>
          </div>
        </div>
      </div>
    </div>
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
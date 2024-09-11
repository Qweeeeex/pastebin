<template>
  <div id="app">
    <header>
      <nav>
        <router-link to="/">Главная</router-link>
        <router-link to="/create">Создать пасту</router-link>
        <router-link v-if="!isLoggedIn" to="/login">Войти</router-link>
        <router-link v-if="isLoggedIn" to="/user/pastes">Мои пасты</router-link>
      </nav>
    </header>

    <section>
      <!-- Рендеринг компонентов на основе маршрутов -->
      <router-view />
    </section>

    <!-- Блок для последних публичных паст -->
    <aside>
      <RecentPastes />
    </aside>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import RecentPastes from './views/RecentAndLoggedPastes.vue';

export default {
  components: {
    RecentPastes,
  },
  computed: {
    ...mapState({
      isLoggedIn: state => state.isLoggedIn, // Или аналогичное состояние авторизации в Pinia/Vuex
    }),
  },
};
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
nav {
  margin-bottom: 20px;
}
nav a {
  margin: 0 10px;
}
</style>

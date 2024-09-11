<template>
  <div id="app">
    <header>
      <nav>
        <router-link to="/">Главная</router-link>
        <router-link to="/create">Создать пасту</router-link>
        <router-link v-if="!isLoggedIn" to="/login">Войти</router-link>
        <router-link v-if="!isLoggedIn" to="/register">Регистрация</router-link>
        <router-link v-if="isLoggedIn" to="/user/pastes">Мои пасты</router-link>
        <button v-if="isLoggedIn" @click="logout">Выйти</button>
      </nav>
    </header>

    <section>
      <router-view />
    </section>

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
      isLoggedIn: state => state.isLoggedIn,
    }),
  },
  methods: {
    logout() {
      this.$store.commit('logout');
      this.$router.push('/');
    },
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

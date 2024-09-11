import { createStore } from 'vuex'
import axios from 'axios'

export default createStore({
    state: {
        token: null,
        isLoggedIn: false,
    },
    mutations: {
        setToken(state, token) {
            state.token = token
            state.isLoggedIn = !!token
        },
        logout(state) {
            state.token = null
            state.isLoggedIn = false
        },
    },
    actions: {
        async login({ commit }, { login, password }) {
            try {
                const response = await axios.post('/auth/login', { login, password })
                const token = response.data.accessToken
                commit('setToken', token)
            } catch (error) {
                console.error('Ошибка авторизации', error)
            }
        },
        logout({ commit }) {
            commit('logout')
        },
    },
    getters: {
        isLoggedIn: state => state.isLoggedIn,
    },
})

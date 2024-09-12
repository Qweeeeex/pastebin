import { createStore } from 'vuex'

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
        logout({ commit }) {
            commit('logout')
        },
    },
    getters: {
        isLoggedIn: state => state.isLoggedIn,
    },
})

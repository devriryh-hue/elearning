import { createStore } from 'vuex'

export default createStore({
  state() {
    return {
      courses: [],
      user: null,
      token: localStorage.getItem('token') || ''
    }
  },

  mutations: {

    SET_COURSES(state, courses) {
      state.courses = courses
    },

    SET_USER(state, user) {
      state.user = user
    },

    SET_TOKEN(state, token) {
      state.token = token
      localStorage.setItem('token', token)
    },

    LOGOUT(state) {
      state.user = null
      state.token = ''
      localStorage.removeItem('token')
    }

  },

  actions: {

    async loadCourses({ commit }) {

      const res = await fetch('http://127.0.0.1:8000/api/courses')

      const data = await res.json()

      commit('SET_COURSES', data)
    },

    login({ commit }, token) {
      commit('SET_TOKEN', token)
    }

  },

  getters: {

    isAuthenticated(state) {
      return !!state.token
    },

    coursesCount(state) {
      return state.courses.length
    }

  }

})
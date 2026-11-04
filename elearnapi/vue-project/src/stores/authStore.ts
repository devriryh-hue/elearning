import { defineStore } from 'pinia'
import api from '../services/api'

export const useAuthStore = defineStore('auth', {

  state: () => ({
    user: null,
    token: localStorage.getItem('token') || ''
  }),

  getters: {
    isLoggedIn: (state) => !!state.token
  },

  actions: {

    /*async login(form) {

      const res = await api.post('/login', form)

      this.token = res.data.token
      this.user = res.data.user
        //console.log(this.user)
      localStorage.setItem('token', this.token)
    }*/
   async login(form) {
  const res = await api.post('/login', form)

  this.token = res.data.token
  this.user = res.data.user
    localStorage.setItem('token', this.token)
  return true
},

    logout() {
      this.user = null
      this.token = ''
      localStorage.removeItem('token')
    }

  }

})
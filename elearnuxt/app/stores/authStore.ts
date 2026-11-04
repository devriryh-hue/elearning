import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {

  state: () => ({
    user: useCookie('user').value || null,
    token: useCookie('token').value || ''
  }),

  getters: {
    isLoggedIn: (state) => !!state.token
  },

  actions: {

    async login(form) {
      const { $api } = useNuxtApp()

      const res = await $api('/login', {
        method: 'POST',
        body: form
      })

      this.token = res.token
      this.user = res.user

      const tokenCookie = useCookie('token')
      useCookie('user').value = res.user
      tokenCookie.value = this.token

      return true
    },

    logout() {
      this.user = null
      this.token = ''
      const tokenCookie = useCookie('token')
      tokenCookie.value = ''

    },


  }

})
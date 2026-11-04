 
import { defineStore } from 'pinia'

export const useAttemptStore = defineStore('attempt', {
  state: () => ({
    attempt: null as any
  }),
  persist: true,

  actions: {
    setAttempt(attempt: any) {
      this.attempt = attempt
    },

    clear() {
      this.attempt = null
    }
  }
})
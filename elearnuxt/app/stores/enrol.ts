
import { defineStore } from 'pinia'

export const useEnrolStore = defineStore('enrol', {
  state: () => ({
    enrol: null as any
  }),
  persist: true,

  actions: {
    setEnrol(enrol: any) {
      this.enrol = enrol
    },

    clear() {
      this.enrol = null
    }
  }
})
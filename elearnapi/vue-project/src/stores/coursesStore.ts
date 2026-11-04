import { defineStore } from 'pinia'
import api from '../services/api'

export const useCourseStore = defineStore('course', {

  state: () => ({
    courses: [],
    loading: false
  }),

  actions: {

    async loadCourses() {
      this.loading = true

      try {
        const res = await api.get('/courses')
        //console.log(res.data)
        this.courses = res.data
      } finally {
        this.loading = false
      }
    }

  },

  getters: {

    coursesCount: (state) => state.courses.length

  }

})
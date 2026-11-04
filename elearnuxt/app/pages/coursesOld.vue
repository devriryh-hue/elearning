<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useCourses } from '../composables/useCourses'
import Navbar from '../components/Navbar.vue'

const courses = ref([])

const form = ref({
  title: '',
  description: ''
})

const {
  getCourses,
  createCourse,
  deleteCourse
} = useCourses()

const loadCourses = async () => {
  courses.value = await getCourses()
}

const addCourse = async () => {
  await createCourse(form.value)

  form.value = {
    title: '',
    description: ''
  }

  await loadCourses()
}

const removeCourse = async (id: number) => {
  await deleteCourse(id)

  await loadCourses()
}

onMounted(async () => {
  await loadCourses()
})
</script>

<template>
  <Navbar />
  <div>
    <h1>Courses</h1>

    <form @submit.prevent="addCourse">
      <div class="form-group mb-2">
        <input
          v-model="form.title"
          class="form-control"
          placeholder="Titre"
        >
      </div>

      <div class="form-group mb-2">
        <textarea
          v-model="form.description"
          class="form-control"
          placeholder="Description"
        />
      </div>

      <button
        class="btn btn-primary"
        type="submit"
      >
        Ajouter
      </button>
    </form>

    <hr>

    <div
      v-for="course in courses"
      :key="course.id"
    >
      <h3>{{ course.title }}</h3>

      <p>{{ course.description }}</p>

      <small>
        Prof: {{ course.teacher.name }}
      </small>

      <br>

      <button
        class="btn btn-danger"
        @click="removeCourse(course.id)"
      >
        Supprimer
      </button>

      <hr>
    </div>
  </div>
</template>
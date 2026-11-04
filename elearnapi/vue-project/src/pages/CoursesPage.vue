<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useCourseStore } from '../stores/coursesStore'
import {
  getCourses,
  createCourse,
  deleteCourse
} from '../services/courseService'
const courses = ref<any[]>([])

const form = ref({
  title: '',
  description: ''
})
/*
const loadCourses = async () => {
  const response = await getCourses()

  courses.value = response.data
}
*/
const courseStore = useCourseStore()
const addCourse = async () => {

  await createCourse(form.value)

  form.value = {
    title: '',
    description: ''
  }

  courseStore.loadCourses()
}

const removeCourse = async (id: number) => {

  await deleteCourse(id)

  courseStore.loadCourses()
}

onMounted(() => {
  courseStore.loadCourses()
})
</script>

<template>

  <div >

    <h1>Courses</h1>
    
    <form @submit.prevent="addCourse">
      <div class="form-group mb-2">
<input class="form-control"
        v-model="form.title"
        placeholder="Titre"
      />
      </div>
      <div class="form-group mb-2">
<textarea class="form-control"
        v-model="form.description"
        placeholder="Description"
      />
      </div>

      

      <button  class="btn btn-primary" type="submit">
        Ajouter
      </button>

    </form>

    <hr />

    <div
      v-for="course in courseStore.courses"
      :key="course.id"
    >
      <h3>{{ course.title }}</h3>

      <p>{{ course.description }}</p>

      <small>
        Prof: {{ course.teacher.name }}
      </small>

      <br />

      <button  class="btn btn-danger" @click="removeCourse(course.id)">
        Supprimer
      </button>

      <hr />
    </div>

  </div>

</template>
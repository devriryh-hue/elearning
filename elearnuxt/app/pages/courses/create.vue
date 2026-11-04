<!-- create.vue -->
<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useCourses } from '~/composables/useCourses'
import CourseForm from '~/components/Courses/CourseForm.vue'
definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const router = useRouter()

const { createCourse } = useCourses()

const save = async (data: any) => {
  await createCourse(data)

  router.push('/courses')
}
</script>

<template>
  <CourseForm @submit="save" />
</template>
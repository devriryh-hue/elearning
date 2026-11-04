<!-- edit.vue -->
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
//import { useCourses } from '~/composables/useCourses'   
import CourseForm from '~/components/Courses/CourseForm.vue'
import { useCrud } from '~/composables/useCrud'

definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const route = useRoute()
const router = useRouter()
const { $api } = useNuxtApp()
//const { updateCourse } = useCourses()
const { updateCrud } = useCrud('courses')


const course = ref()

onMounted(async () => {
  course.value = await $api(`/courses/${route.params.id}`)
})

const save = async (data: any) => {
  //await updateCourse(Number(route.params.id), data)
  console.log('data', data)
  await updateCrud(Number(route.params.id), data)

  router.push('/courses')
}
</script>

<template>
  <CourseForm v-if="course" :course="course" @submit="save" />
</template>
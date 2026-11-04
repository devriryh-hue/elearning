<!-- create.vue -->
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useCrud } from '~/composables/useCrud'
import QuizForm from '~/components/Quizzes/QuizForm.vue'
definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const router = useRouter()

const { createCrud } = useCrud('quizzes')

const { $api } = useNuxtApp()
const route = useRoute()
const save = async (data: any) => {
  await createCrud(data)

  router.push(`/lessons/${route.params.id}/quiz`)
}
//const lessons = ref()
const idlesson = ref(route.params.id)
onMounted(async () => {
  //lessons.value = await $api(`/lessons/${route.params.id}`)
  
})
</script>

<template>
  <QuizForm @submit="save" :idlesson="idlesson"/>
</template>
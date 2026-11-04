<!-- edit.vue -->
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useCrud } from '~/composables/useCrud'   
import QuizForm from '~/components/Quizzes/QuizForm.vue'

definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const route = useRoute()
const router = useRouter()
const { $api } = useNuxtApp()
const { updateCrud } = useCrud('quizzes')

const quiz = ref()

onMounted(async () => {
  quiz.value = await $api(`/quizzes/${route.params.id}`)
  //console.log("quiz", quiz.value)
})

const save = async (data: any) => {
  await updateCrud(Number(route.params.id), data)
  const lessonId = quiz.value.lesson_id
  router.push(`/lessons/${lessonId}/quiz`)
}
</script>

<template>
  <QuizForm
    v-if="quiz"
    :quiz="quiz"
    @submit="save"
  />
</template>
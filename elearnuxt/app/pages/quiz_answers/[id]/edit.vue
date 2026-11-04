<!-- edit.vue -->
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useCrud } from '~/composables/useCrud'   
import QuizAnswerForm from '~/components/QuizAnswers/QuizAnswerForm.vue'

definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const route = useRoute()
const router = useRouter()
const { $api } = useNuxtApp()
const { updateCrud } = useCrud('quiz-answers')

const quizAnswer = ref()

onMounted(async () => {
  quizAnswer.value = await $api(`/quiz-answers/${route.params.id}`)
  console.log('quizAnswer', quizAnswer.value)
})

const save = async (data: any) => {
  await updateCrud(Number(route.params.id), data)

  router.push('/quiz-answers')
}
</script>

<template>
  <QuizAnswerForm
    v-if="quizAnswer"
    :quiz_answer="quizAnswer"
    @submit="save"
  />
</template>
<!-- create.vue -->
<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useCrud } from '~/composables/useCrud'
import QuizAnswerForm from '~/components/QuizAnswers/QuizAnswerForm.vue'
definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const router = useRouter()

const { createCrud } = useCrud('quiz-answers')

const save = async (data: any) => {
  await createCrud(data)

  router.push('/quiz-answers')
}
</script>

<template>
  <QuizAnswerForm @submit="save" />
</template>
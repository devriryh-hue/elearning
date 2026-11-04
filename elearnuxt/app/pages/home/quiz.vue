<!-- create.vue -->
<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useCrud } from '~/composables/useCrud'
import QuizHomeForm from '~/components/Quizzes/QuizHomeForm.vue'
definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const router = useRouter()

const { createCrud } = useCrud('quizzes')

const save = async (data: any) => {
  await createCrud(data)

  router.push('/quizzes')
}
</script>
<template>
  <QuizHomeForm @submit="save" />
</template>
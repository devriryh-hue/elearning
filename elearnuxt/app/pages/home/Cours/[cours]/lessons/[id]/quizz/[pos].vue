<script setup lang="ts">
import { ref, onMounted } from 'vue'
import QuizHomeForm from '~/components/Quizzes/QuizHomeForm.vue'
import { useRoute } from 'vue-router'

import { useQuiz } from '~/composables/useQuiz'
import { useAttemptStore } from '~/stores/attempt'
import { useEnrolStore } from '~/stores/enrol'
definePageMeta({
  layout: 'home',
  middleware: ['auth']
})
const route = useRoute()
const quiz = ref()
const attemptStore = useAttemptStore()
const enrolStore = useEnrolStore()
const { saveattempt, finishAttempt, getQuizByLesson, getEnrollments } = useQuiz()
const attemptId = attemptStore.attempt.id

const coursId = route.params.cours

onMounted(async () => {
  quiz.value = await getQuizByLesson(Number(route.params.id), Number(route.params.pos))
})

const save = async (data: any) => {
  await saveattempt(Number(attemptId), data)
  if (quiz.value.next_position && quiz.value.next_position > 0) {
    navigateTo(`/home/Cours/${route.params.cours}/lessons/${route.params.id}/quizz/${quiz.value.next_position}`)
    
  } else if (quiz.value.next_position == 0){
    
    await finishAttempt(Number(attemptId))
    const enrol = await getEnrollments(Number(coursId))
    enrolStore.setEnrol(enrol)
    navigateTo(`/home/Cours/${route.params.cours}/lessons/fini`)
  }
}
</script>
<template>
  <QuizHomeForm v-if="quiz" :quiz="quiz" @submit="save" />
</template>
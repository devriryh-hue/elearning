<!-- edit.vue -->
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

import { useQuiz } from '~/composables/useQuiz'
import LessonHomeForm from '~/components/Lessons/LessonHomeForm.vue'

import Mybutton from '~/components/person/Mybutton.vue'
import { useAttemptStore } from '~/stores/attempt'


definePageMeta({
  layout: 'home',
  middleware: ['auth']
})
const route = useRoute()
const { $api } = useNuxtApp()

const lesson = ref()
const { start } = useQuiz()
onMounted(async () => {
  lesson.value = await $api(`/lessons/${route.params.id}`)
})

const attemptStore = useAttemptStore()

const commencer = async (id: number) => {
  const attempt = await start(id)
  attemptStore.setAttempt(attempt)
  navigateTo(`/home/Cours/${route.params.cours}/lessons/${id}/quizz/1`)
}
</script>

<template>
  <LessonHomeForm v-if="lesson" :lesson="lesson" />
  <div class="divcours">
    <Mybutton @click="commencer(lesson.id)" class="voir">
      <Icon name="tabler:edit" />
      Commencer
    </Mybutton>
  </div>

</template>
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAttemptStore } from '~/stores/attempt'
import { useQuiz } from '~/composables/useQuiz'
//import { getEnrollments } from '~/services/enrollmentService'
import { useRoute } from 'vue-router'

import { useEnrolStore } from '~/stores/enrol'
import MarkdownIt from 'markdown-it'

const md = new MarkdownIt({
  html: true,
  breaks: true,
  linkify: true
})
// Convertir les réponses en tableau
const normalizeAnswers = (value) => {
  if (!value) return []

  // Si c'est déjà un tableau
  if (Array.isArray(value)) {
    return value
  }

  // Si c'est une chaîne JSON représentant un tableau
  if (typeof value === 'string') {
    try {
      const parsed = JSON.parse(value)

      if (Array.isArray(parsed)) {
        return parsed
      }
    } catch (error) {
      // Ce n'est pas du JSON : on garde la chaîne
    }

    // Une seule réponse
    return [value]
  }

  return []
}

// Transformer Markdown en HTML
const renderMarkdown = (content) => {
  return md.render(String(content ?? ''))
}

const attemptStore = useAttemptStore()


const enrolStore = useEnrolStore()

const route = useRoute()

definePageMeta({
  layout: 'home',
  middleware: ['auth']
})

const enrol = enrolStore.enrol
const quiz = ref()
const { getAttempt, getAttemptAnswers } = useQuiz()
//const enrollment = ref()
const coursid = route.params.cours
const answers = ref([])
const quizanswers = ref([])


onMounted(async () => {
  if (attemptStore.attempt.id != null) {
    const attemptId = attemptStore.attempt.id
    quiz.value = await getAttempt(Number(attemptId))
    quizanswers.value = await getAttemptAnswers(attemptId)
    answers.value = quizanswers.value.answers
  } else {
    navigateTo(`/competence/ind`)
  }
  //enrollment.value = await getEnrollments(Number(coursid))
})
</script>
<template>
  <div class="container mt-5">
    <div class="containequizz">
      <h1 class="terminatequizz">Quiz terminé</h1>
      <p>Félicitations ! Vous avez terminé le quiz.</p>
      <p>Score: {{ quiz?.score }} / {{ quiz?.max_score }}</p>
    </div>

  </div>
  <div>
    <div class="containh3">
      <h3>
        Leçon: {{ quizanswers?.quizz?.lesson?.title }}
      </h3>
    </div>

    <div class="containscore">
      <div>
        Score: {{ quizanswers?.quizz?.score }} / {{ quizanswers?.quizz?.max_score }}
      </div>
      <div>
        Date: {{ new Date(quizanswers?.quizz?.created_at).toLocaleString() }}
      </div>
      <hr>
    </div>
  </div>

  <div>
    <div class="containanswer" v-for="answer in answers" :key="answer.id">
      <div>
        <div class="bxtxt txt">Question: </div>
        <div class="answer" v-html="answer.quiz.question"></div>

      </div>
      <div>
        <div class="bxtxt txt">Réponse: </div>
        <div class="vhtml answer">
          <div v-for="(selected, index) in normalizeAnswers(answer.selected_answer)" :key="index"
            v-html="renderMarkdown(selected)"></div>
        </div>
      </div>
      <div class="greatbx"> <span class="bxtxt txt">Correct: </span> {{ answer.is_correct ? 'Yes' : 'No' }}</div>
      <div class="greatbx" v-if="!answer.is_correct">
        <div v-for="options in answer.quiz.options">
          <div v-if="options.correct">
            <div class="bxtxtwo txtsecondaire">
              Réponse exacte:
            </div>
            <div class="answer" v-html="options.text"></div>

          </div>
        </div>
      </div>

      <div class="greatbx"> <span class="bxtxt txt">Points: </span> {{ answer.points_earned }}/{{ answer.quiz.points }}
      </div>

    </div>
  </div>
</template>
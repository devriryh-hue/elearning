<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useCrud } from '~/composables/useCrud'
import Mybutton from '~/components/person/Mybutton.vue'
import { useRoute, useRouter } from 'vue-router'
const route = useRoute()
const router = useRouter()
definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})

const quiz = ref()
const { $api } = useNuxtApp()
const lessonwithquiz = ref([])
const idcours = ref()
const idlesson = ref()
onMounted(async () => {
  lessonwithquiz.value = await $api(`/lessons/${route.params.id}/allquizzes`)
  //console.log("le quiz", quiz.value)
  const lesquiz = lessonwithquiz.value
  quiz.value = lesquiz?.quizzes
  idcours.value = lesquiz?.course_id
  idlesson.value = lesquiz?.id
})


</script>

<template>


  <div class="flybuttons">
    <Mybutton @click="router.push(`/lessons/${route.params.id}/createquiz`)" style="background-color: green;">
      <Icon name="tabler:edit" />
      Ajouter Quizzes
    </Mybutton>
    <Mybutton @click="router.push(`/home/Cours/${idcours}/lessons/${idlesson}/voir`)" style="background-color: green;">
      <Icon name="tabler:edit" />
      Commencer quizz
    </Mybutton>
  </div>

  <hr class="hr">
  <div v-for="quizItem in quiz" :key="quizItem.id" class="quizzes">
    <div class="questionposition">
      <span class="boutonposition">
        {{ quizItem.position }}

      </span>
    </div>
    <hr class="hr">
    <div>
      <span class="champform">
        Question:
      </span>
      <div v-html="quizItem.question"></div>

    </div>
    <hr class="hr">
    <div class="reponse ">
      <div class="haveIcon">
        <Icon name="tabler:circle-check" class="icon" style="background-color: var(--couleur-principale);" />
      </div>
      <div class="champform">
        Réponse:
      </div>
    </div>

    <div class="mb-3" v-for="option in quizItem.options" :key="option.id">
      <div v-if="option.correct" class="correct-option">

        <div v-html="option.text"></div>
      </div>
    </div>
    <hr v-if="quizItem.note" class="hr">
    <div v-if="quizItem.note">
      <span class="champform">
        Note:
      </span>
      <div v-html="quizItem.note"></div>
    </div>
    <hr class="hr">
    <div class="mb-3">
      <span class="champform">
        Position:
      </span>
      {{ quizItem.position }},
      <span class="champform">
        Next Position:
      </span>
      <span v-if="quizItem.next_position !== null">
        {{ quizItem.next_position }}
      </span>
      <span v-else class="" style="background-color: red; color: white; padding: 2px 5px; border-radius: 3px;">
        Aucun
      </span>
    </div>
    <hr class="hr">
    <div class="mb-3">
      <span class="champform">
        Point:
      </span>
      {{ quizItem.points }}

    </div>
    <hr class="hr">
    <Mybutton @click="router.push(`/quizzes/${quizItem.id}/edit`)" class="boutonediter_back">
      <Icon name="tabler:edit" />
      Editer
    </Mybutton>
  </div>
</template>
<style>
.reponse {
  display: flex;
}

.haveIcon {
  display: flex;
  align-items: center;
  margin-right: 5px;
}
</style>
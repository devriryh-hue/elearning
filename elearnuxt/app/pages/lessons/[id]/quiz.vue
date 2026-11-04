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
  <QuizzesShow :quiz="quiz"></QuizzesShow>
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
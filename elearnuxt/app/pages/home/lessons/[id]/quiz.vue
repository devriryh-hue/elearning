<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useCrud } from '~/composables/useCrud'
import Mybutton from '~/components/person/Mybutton.vue'
import QuizzesShow from '~/components/Quizzes/Show.vue'
import { useRoute, useRouter } from 'vue-router'
const route = useRoute()
const router = useRouter()
definePageMeta({
  layout: 'home',
  middleware: ['auth']
})

const quiz = ref()
const { $api } = useNuxtApp()
const lessonwithquiz=ref([])
const idcours =ref()
const idlesson=ref()
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
  <div>
<div>
    
    <h1 class="title">{{ lessonwithquiz.title }}</h1>

  </div>
  <div>
    <div v-html="lessonwithquiz.description"></div>
  </div>
  </div>
  
  
  
  <hr class="hr">
  <QuizzesShow :quiz="quiz"></QuizzesShow>
</template>
<style>
.reponse{
  display: flex;
}
.haveIcon{
  display: flex;
  align-items: center;
  margin-right: 5px;
}
</style>
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useCrud } from '~/composables/useCrud'
import Mybutton from '~/components/person/Mybutton.vue'
import { usePagination } from '~/composables/usePagination'
//const config = useRuntimeConfig()

definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
/**pagination */
const {
  page,
  data: quiz_answers
} = usePagination('quiz-answers')

//const quiz_answers = ref([])

//const {getquiz_answers, deleteCourse} = usequiz_answers()

const {getCrud, deleteCrud} = useCrud('quiz-answers')

const loadCrud = async () => {
    quiz_answers.value = await getCrud()
}
const editCrud = (id: number) => {
    // Rediriger vers la page d'édition du cours
    window.location.href = `/quiz_answers/${id}/edit`
}
onMounted( async()=>{
    
    await loadCrud()
})

const removeCrud = async (id: number) => {
    await deleteCrud(id)
    await loadCrud()
}
/*
inutil de faire ça, le watch dans usePagination suffit
watch(page, function test(page) {
  console.log('page', page)
  usePagination('quiz_answers')
})
*/
</script>
<template>
    
    <div
      v-for="quiz_answer in quiz_answers?.data"
      :key="quiz_answer.id"
    >

      <p>{{ quiz_answer.answer }}</p>
    
      

      <br>
      <Mybutton
        @click="editCrud(quiz_answer.id)"
        style="background-color: #ffd100;"
      >
      <Icon name="tabler:edit" />
        Modifier
      </Mybutton>
      <Mybutton
        @click="removeCrud(quiz_answer.id)"
        style="background-color: red;"
      >
      <Icon name="ant-design:delete-twotone" />
        Supprimer
      </Mybutton>

      <hr>
    </div>
    <Pagination
    v-if="quiz_answers"
    :current-page="quiz_answers.current_page"
    :last-page="quiz_answers.last_page"
    @change="page = $event"
  />
    </template>
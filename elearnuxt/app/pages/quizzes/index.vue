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
  data: quizzes
} = usePagination('quizzes')

//const quizzes = ref([])

//const {getquizzes, deleteCourse} = usequizzes()

const { getCrud, deleteCrud } = useCrud('quizzes')

const loadCrud = async () => {
  quizzes.value = await getCrud()
}
const editCrud = (id: number) => {
  // Rediriger vers la page d'édition du cours
  window.location.href = `/quizzes/${id}/edit`
}
onMounted(async () => {

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
  usePagination('quizzes')
})
*/
</script>
<template>

  <div v-for="quizze in quizzes?.data" :key="quizze.id">

    <p v-html="quizze.question"></p>



    <br>
    <Mybutton @click="editCrud(quizze.id)" style="background-color: #ffd100;">
      <Icon name="tabler:edit" />
      Modifier
    </Mybutton>
    <Mybutton @click="removeCrud(quizze.id)" style="background-color: red;">
      <Icon name="ant-design:delete-twotone" />
      Supprimer
    </Mybutton>

    <hr>
  </div>
  <Pagination v-if="quizzes" :current-page="quizzes.current_page" :last-page="quizzes.last_page"
    @change="page = $event" />
</template>
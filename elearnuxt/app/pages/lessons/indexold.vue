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
  data: lessons
} = usePagination('lessons')

//const lessons = ref([])

//const {getlessons, deleteCourse} = uselessons()

const { getCrud, deleteCrud } = useCrud('lessons')
const search = ref('')
const loadCrud = async () => {
  //lessons.value = await getCrud()
  lessons.value = await getCrud({
    search: search.value
  })
}

const editCrud = (id: number) => {
  // Rediriger vers la page d'édition du cours
  window.location.href = `/lessons/${id}/edit`
}

const gotolesson = (id: number) => {
  // Rediriger vers la page d'édition du cours
  window.location.href = `/lessons/${id}/quiz`
}

onMounted(async () => {

  await loadCrud()
})

const removeCrud = async (id: number) => {
  await deleteCrud(id)
  await loadCrud()
}
watch(search, () => {
  loadCrud()
})
/*
inutil de faire ça, le watch dans usePagination suffit
watch(page, function test(page) {
  console.log('page', page)
  usePagination('lessons')
})
*/
</script>
<template>
  <input
    v-model="search"
    type="text"
    placeholder="Rechercher une leçon..."
    class="form-control searchme"
  />

  <div v-for="lesson in lessons?.data" :key="lesson.id">

    <h3>
      {{ lesson.title }}
    </h3>


    <br>
    <Mybutton @click="editCrud(lesson.id)" style="background-color: #ffd100;">
      <Icon name="tabler:edit" />
      Modifier
    </Mybutton>
    <Mybutton @click="removeCrud(lesson.id)" style="background-color: red;">
      <Icon name="ant-design:delete-twotone" />
      Supprimer
    </Mybutton>
    <Mybutton @click="gotolesson(lesson.id)" style="background-color: green;">
      Quiz
    </Mybutton>
    <hr>
  </div>
  <Pagination v-if="lessons" :current-page="lessons.current_page" :last-page="lessons.last_page"
    @change="page = $event" />
</template>
<style>
.searchme{
  margin: 25px;
}
</style>
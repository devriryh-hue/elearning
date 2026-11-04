<script setup lang="ts">
import { ref, onMounted } from 'vue'
//import { useCrud } from '~/composables/useCrud'
import Mybutton from '~/components/person/Mybutton.vue'
import { usePagination } from '~/composables/usePagination'
//const config = useRuntimeConfig()
import { useQuiz } from '~/composables/useQuiz'
import { useRoute, useRouter } from 'vue-router'

definePageMeta({
  layout: 'home',
  middleware: ['auth']
})
/**pagination */
const {
  page,
  data: lessons
} = usePagination('lessons')

//const lessons = ref([])

//const {getlessons, deleteCourse} = uselessons()
const route = useRoute()
//const {getCrud} = useCrud('lessons')
const {getLessonByCours} = useQuiz()

const loadCrud = async () => {
    //lessons.value = await getCrud()
    lessons.value = await getLessonByCours(Number(route.params.cours))
    console.log('lessons', lessons.value)
}
const commencer = (id: number) => {
    // Rediriger vers la page d'édition du cours
    window.location.href = `/home/Cours/${route.params.cours}/lessons/${id}/voir`
    //start(id)
}
onMounted( async()=>{
    
    await loadCrud()
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
    
    <div
      v-for="lesson in lessons?.data"
      :key="lesson.id"
      class="divcours"
    >
<div>
<h3>
        {{ lesson.title }}</h3>
</div>
      
    
      <p>{{lesson.description }}</p>

      <br>
      <div class="divmybutt"
      >
      <Mybutton
        @click="commencer(lesson.id)"
        style="background-color: #ffd100;"
      >
      <Icon name="tabler:edit" />
        voir
      </Mybutton>
    </div>
      
      

      <div class="separator"></div>
    </div>
    <Pagination
    v-if="lessons"
    :current-page="lessons.current_page"
    :last-page="lessons.last_page"
    @change="page = $event"
  />
    </template>
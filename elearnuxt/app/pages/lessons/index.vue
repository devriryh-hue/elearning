<script setup lang="ts">
import { ref, reactive, onMounted, watch } from 'vue'
import { useCrud } from '~/composables/useCrud'
import Mybutton from '~/components/person/Mybutton.vue'
import { usePagination } from '~/composables/usePagination'

definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})

const search = ref('')
const selectedCourse = ref('')

// filters doit être un Ref pour être passé au composable
const filters = ref({
  search: '',
  course_id: ''
})

const {
  page,
  data: lessons,
  refresh
} = usePagination('lessons', 5, filters)

const { deleteCrud } = useCrud('lessons')
const { getCrud: getCourses } = useCrud('courses')

const courses = ref([])

const loadCourses = async () => {
  const res = await getCourses({ per_page: 1000 })
  courses.value = res.data ?? res
}

const editCrud = (id: number) => {
  window.location.href = `/lessons/${id}/edit`
}

const gotolesson = (id: number) => {
  window.location.href = `/lessons/${id}/quiz`
}

onMounted(async () => {
  await loadCourses()
})

const removeCrud = async (id: number) => {
  await deleteCrud(id)
  await refresh() // on rafraîchit la liste après suppression
}

// Dès que search ou selectedCourse changent, on met à jour filters
// et on revient à la page 1
watch([search, selectedCourse], () => {
  filters.value = {
    search: search.value,
    course_id: selectedCourse.value
  }
  page.value = 1
})
</script>

<template>
  <input
    v-model="search"
    type="text"
    placeholder="Rechercher une leçon..."
    class="form-control searchme"
  />

  <select v-model="selectedCourse" class="form-control searchme">
    <option value="">Tous les cours</option>
    <option v-for="course in courses" :key="course.id" :value="course.id">
      {{ course.title }}
    </option>
  </select>

  <div v-for="lesson in lessons?.data" :key="lesson.id">
    <h3>{{ lesson.title }}</h3>
    <br>
    <div>
      <span>Cours: </span>
      <span>
        {{ lesson.course.title }}
      </span>
    </div>
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
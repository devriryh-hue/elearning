<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import Mybutton from '~/components/person/Mybutton.vue'
import { usePagination } from '~/composables/usePagination'

definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})

const {
  page,
  data: courses
} = usePagination('courses')

const { getCrud, deleteCrud } = useCrud('courses')

const search = ref('')

const loadCourses = async () => {
  courses.value = await getCrud({
    search: search.value
  })
}

const editCourse = (id: number) => {
  window.location.href = `/courses/${id}/edit`
}

onMounted(async () => {
  await loadCourses()
})

const removeCourse = async (id: number) => {
  await deleteCrud(id)
  await loadCourses()
}

watch(search, () => {
  loadCourses()
})
</script>

<template>
  <div>
    <div class="importfile">
      <NuxtLink :to="'courses/json'" class="list-group-item">
        <Icon name="hugeicons:quiz-03" />
        Import fichier json (choices)
      </NuxtLink>
    </div>
    <div class="importfile">
      <NuxtLink :to="'courses/jsonbd'" class="list-group-item">
        <Icon name="hugeicons:quiz-03" />
        Import fichier json format BD (options)
      </NuxtLink>
    </div>
  </div>

  <input
    v-model="search"
    type="text"
    placeholder="Rechercher un cours..."
    class="form-control searchme"
  />

  <div v-for="course in courses?.data" :key="course.id">
    <h3>{{ course.title }}</h3>

    <div>
      <NuxtLink :to="course.pdf_url" class="list-group-item">
        <Icon name="heroicons:users" />
        Utilisateurs
      </NuxtLink>
    </div>
    <small>
      Prof: {{ course.teacher.name }}
    </small>

    <br>
    <Mybutton @click="editCourse(course.id)" style="background-color: #ffd100;">
      <Icon name="tabler:edit" />
      Modifier
    </Mybutton>
    <Mybutton @click="removeCourse(course.id)" style="background-color: red;">
      <Icon name="ant-design:delete-twotone" />
      Supprimer
    </Mybutton>

    <hr>
  </div>
  <Pagination v-if="courses" :current-page="courses.current_page" :last-page="courses.last_page"
    @change="page = $event" />
</template>

<style>
.importfile{
  float: right;
  margin: 10px;
  padding: 10px;
  border-radius: 5px;
  background-color: var(--couleur-tertiaire);
  color: white;
}
.searchme{
  margin: 25px;
}
</style>
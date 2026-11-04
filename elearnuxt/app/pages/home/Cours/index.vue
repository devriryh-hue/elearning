<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useCourses } from '~/composables/useCourses'
import Mybutton from '~/components/person/Mybutton.vue'
import { usePagination } from '~/composables/usePagination'
//const config = useRuntimeConfig()
//import { useRoute, useRouter } from 'vue-router'

definePageMeta({
    layout: 'home',
    middleware: ['auth']
})
/**pagination */
const {
    page,
    data: courses
} = usePagination('courses')

//const courses = ref([])
const { getCourses } = useCourses()

const loadCourses = async () => {
    courses.value = await getCourses()
}

onMounted(async () => {

    await loadCourses()
})

const getcourse = (id: number) => {
    navigateTo(`/home/Cours/${id}/lessons/lesson`)
}
/*
inutil de faire ça, le watch dans usePagination suffit
watch(page, function test(page) {
  console.log('page', page)
  usePagination('courses')
})
*/
</script>
<template>

    <div v-for="course in courses?.data" :key="course.id" class="divcours">
        <h3>{{ course.title }}</h3>
        <div>
            <p v-html="course.description" ></p>
        </div>
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
        <Mybutton class="voir" @click="getcourse(course.id)">
            Voir les lessons
        </Mybutton>

        <div class="separator"></div>
    </div>
    <Pagination v-if="courses" :current-page="courses.current_page" :last-page="courses.last_page"
        @change="page = $event" />
</template>
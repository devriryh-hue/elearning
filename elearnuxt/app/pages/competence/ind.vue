<script src setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useCourses } from '~/composables/useCourses'
import { onMounted } from 'vue'
import { useCookie } from '#app'
//import { usePaginationOLD } from '~/composables/usePaginationOLD'
import { usePagination } from '~/composables/usePagination'

/**pagination */

const {
    page,
    data: courses
} = usePagination('withlessons', 6)

const { $api } = useNuxtApp()

definePageMeta({
    layout: 'home',
    middleware: ['auth']
})
const evaluations = ref({

})

//const userd = ref(null)
const userd = useCookie('user')
const userid = userd.value.id
const openedLessons = ref({})
const openedLessonsAfaire = ref({})
const router = useRouter()
const toggleLessons = (id) => {
    openedLessons.value[id] = !openedLessons.value[id]
}
const search = ref('')
const { getCoursesWithLessons } = useCourses()
const loadCourses = async () => {
    courses.value = await getCoursesWithLessons({ search: search.value })
}

onMounted(async () => {
    evaluations.value = await $api(`evaluation/${userid}`)
    await loadCourses()
})

const toggleLessonsAfaire = (id) => {
    openedLessonsAfaire.value[id] = !openedLessonsAfaire.value[id]
}
watch(search, () => {
    loadCourses()
})
</script>

<template>
    <h3>
        Les cours déjà appris
    </h3>
    <hr class="hr">
    <div class="cont_competences">
        <div v-for="evaluation in evaluations" class="generalcompetences">
            <div class="lescours">
                <div class="h2div">
                    <h2 class="h2_title">
                        {{ evaluation.course.title }}

                    </h2>
                </div>
                <br />
                <div>
                    <EvaluationProgress :evaluation="evaluation">
                    </EvaluationProgress>
                </div>
                <br />
                <PersonMybutton type="button" @click="toggleLessons(evaluation.id)" class="afficherlesson">
                    {{ openedLessons[evaluation.id] ? 'Masquer les leçons' : 'Afficher les leçons' }}
                </PersonMybutton>
            </div>
            <LazyCoursesLessonListNote v-if="openedLessons[evaluation.id]" :evaluation="evaluation" />


        </div>
    </div>
    <h3>
        Les cours à apprendre
    </h3>
    <input v-model="search" type="text" placeholder="Rechercher une leçon..." class="form-control searchme" />
    <hr class="hr">
    <div class="cont_a_apprendre">

        <div v-for="course in courses?.data" :key="course.id" class="generalcompetences">
            <div class="lescours">
                <div class="h2div">
                    <h2 class="h2_title">
                        {{ course.title }}
                    </h2>
                </div>
                <br />
                <PersonMybutton type="button" @click="toggleLessonsAfaire(course.id)" class="afficherlesson">
                    {{ openedLessonsAfaire[course.id] ? 'Masquer les leçons' : 'Afficher les leçons' }}
                </PersonMybutton>
                <LazyCoursesLessonList v-if="openedLessonsAfaire[course.id]" :course="course" />

            </div>
        </div>
    </div>
    <Pagination v-if="courses" :current-page="courses.current_page" :last-page="courses.last_page"
        @change="page = $event" />
</template>
<style>
.cont_competences {
    display: grid;
    /* flex-direction: column; */
    gap: 20px;
    grid-template-columns: repeat(3, 1fr);
    padding: 20px;
}

.cont_a_apprendre {
    display: grid;
    /* flex-direction: column; */
    gap: 20px;
    grid-template-columns: repeat(3, 1fr);
    padding: 20px;
}

.afficherlesson {
    background-color: var(--couleur-tertiaire);
    color: black;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
}

.searchme {
    margin: 25px;
}

.leslessons {
    width: 100% !important;
}
</style>
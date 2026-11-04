<script src setup>
import { ref } from 'vue'
import { useRouter} from 'vue-router'
import { useCourses } from '~/composables/useCourses'
import { onMounted } from 'vue'
import { useCookie } from '#app'
//import { usePaginationOLD } from '~/composables/usePaginationOLD'
import { usePagination} from '~/composables/usePagination'

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

const commencera = async (courseId, lessonId) => {
    navigateTo(`/home/Cours/${courseId}/lessons/${lessonId}/voir`)
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
            <div class="leslessons" v-show="openedLessons[evaluation.id]">

                <div class="comptlessons" v-for="(lesson, index) in evaluation.course.lessons" :key="lesson.id">
                    <h3 class="h3_s">
                        {{ lesson.title }}
                    </h3>
                    <small class="notesmall">
                        {{ lesson.quiz_attemps.length }} Quiz fait
                    </small>
                    <div v-for="quiz_attempt in lesson.quiz_attemps" :key="quiz_attempt.id">
                        <div class="containnote"  v-if="quiz_attempt.score > 0">
                            Note {{ quiz_attempt.score }} / {{ quiz_attempt.max_score }}
                            <span>
                                <NuxtLink class="ahref" :to="`/home/Response/${quiz_attempt.id}/`">
                                    <Icon name="solar:eye-line-duotone" />
                                    Voir le quiz
                                </NuxtLink>
                            </span>
                        </div>

                    </div>
                    <br />
                    <div class="divcours">
                        <PersonMybutton @click="commencera(evaluation.course.id, lesson.id)" class="voir">
                            <Icon name="tabler:edit" />
                            Commencer
                        </PersonMybutton>
                        <PersonMybutton @click="router.push(`/home/lessons/${lesson.id}/quiz`)" class="voir">
                                <Icon name="streamline:class-lesson" />
                                Voir lessons
                            </PersonMybutton>
                    </div>
                    <!-- <div v-if="index < evaluation.course.lessons.length - 1" class="separator"></div> -->
                </div>
                <br>
            </div>

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
                <div class="leslessons" v-show="openedLessonsAfaire[course.id]">
                    <div class="comptlessons" v-for="(lessonap, ind) in course.lessons" :key="lessonap.id">
                        <h3 class="h3_title">
                            {{ lessonap.title }}
                        </h3>
                        <div class="divcours">
                            <PersonMybutton @click="commencera(course.id, lessonap.id)" class="voir">
                                <Icon name="tabler:edit" />
                                Commencer
                            </PersonMybutton>
                            <PersonMybutton @click="router.push(`/home/lessons/${lessonap.id}/quiz`)" class="voir">
                                <Icon name="streamline:class-lesson" />
                                Voir lessons
                            </PersonMybutton>
                        </div>
                    </div>
                </div>

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
<script setup>
const props = defineProps({
    evaluation: {
        type: Object,
        required: true
    }
})

const router = useRouter()

const commencera = (courseId, lessonId) => {
    navigateTo(`/home/Cours/${courseId}/lessons/${lessonId}/voir`)
}
const quizFait = ref(0)
</script>

<template>
    <div class="leslessons" >

                <div class="comptlessons" v-for="(lesson, index) in evaluation.course.lessons" :key="lesson.id">
                    <h3 class="h3_s">
                        {{ lesson.title }}
                    </h3>
                    <small class="notesmall">
                        
                        {{ lesson.quiz_attemps.length }} Quiz fait
                    </small>
                    <div v-for="quiz_attempt in lesson.quiz_attemps" :key="quiz_attempt.id">
                        <div class="containnote" v-if="quiz_attempt.score > 0 || quiz_attempt.status=='completed'">
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
                            <Icon name="solar:alarm-play-bold" /> 
                            Commencer
                        </PersonMybutton>
                        <PersonMybutton @click="router.push(`/home/lessons/${lesson.id}/quiz`)" class="voir">
                            <Icon name="mdi:account-school-outline" />
                            Voir lessons
                        </PersonMybutton>
                    </div>
                    <!-- <div v-if="index < evaluation.course.lessons.length - 1" class="separator"></div> -->
                </div>
                <br>
            </div>
</template>
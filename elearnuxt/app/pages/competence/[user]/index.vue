<script src setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'

const { $api } = useNuxtApp()
const route = useRoute()
const currentRoute = ref(route.path)

definePageMeta({
    layout: 'home',
    middleware: ['auth']
})
const evaluations = ref({

})
//const userd = ref(null)
//const userd = useCookie('user')
//console.log('userd', userd.value)
const userid = route.params.user
onMounted(async () => {
    evaluations.value = await $api(`evaluation/${userid}`)
})
</script>

<template>

    <div v-for="evaluation in evaluations" class="generalcompetences" >
        <div class="lescours">
            <div class="h2div" >
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
        </div>
        <div class="leslessons">

            <div class="comptlessons" v-for="(lesson, index) in evaluation.course.lessons" :key="lesson.id">
                <h3 class="h3_title">
                    {{ lesson.title }}
                </h3>
                <small class="notesmall">
                    {{ lesson.quiz_attemps.length }} Quiz fait
                </small>
                <div v-for="quiz_attempt in lesson.quiz_attemps" :key="quiz_attempt.id">
                    Note {{ quiz_attempt.score }} / {{ quiz_attempt.max_score }}
                </div>
                <br />
                <!-- <div v-if="index < evaluation.course.lessons.length - 1" class="separator"></div> -->
            </div>
            <br>
        </div>

    </div>


</template>
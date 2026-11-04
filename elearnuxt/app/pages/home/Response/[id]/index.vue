<script src setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useQuiz } from '~/composables/useQuiz';

const { getAttemptAnswers } = useQuiz();

definePageMeta({
    layout: 'home',
    //middleware: ['auth']
})

const route = useRoute()

const id = route.params.id

const answers = ref([])
const quizanswers = ref([])
const loadAnswers = async () => {
    quizanswers.value = await getAttemptAnswers(id)
    answers.value = quizanswers.value.answers
}
onMounted(async () => {
    await loadAnswers()
})

</script>
<template>
    <div>
        <div class="containh3" >
            <h3>
                Leçon: {{ quizanswers?.quizz?.lesson?.title }}
            </h3>
        </div>

        <div class="containscore" >
            <div>
                Score: {{ quizanswers?.quizz?.score }} / {{ quizanswers?.quizz?.max_score }}
            </div>
            <div>
                Date: {{ new Date(quizanswers?.quizz?.created_at).toLocaleString() }}
            </div>
            <hr>
        </div>
    </div>

    <div>
        <div class="containanswer" v-for="answer in answers" :key="answer.id">
            <div> <div class="bxtxt txt">Question: </div> <div  class="answer" v-html="answer.quiz.question"></div></div>
            <div> <div class="bxtxt txt">Réponse: </div>
                <div class="vhtml answer" v-html="answer.selected_answer">
                </div>
            </div>
            <div  class="greatbx"> <span class="bxtxt txt">Correct: </span> {{ answer.is_correct ? 'Yes' : 'No' }}</div>
            <div class="greatbx" v-if="!answer.is_correct">
                <div v-for="options in answer.quiz.options">
                    <div v-if="options.correct">
                        <div class="bxtxtwo txtsecondaire" >
                                Réponse exacte:
                        </div> 
                        <div class="answer" v-html="options.text"></div>
                    </div>
                </div>
            </div>

            <div class="greatbx" > <span class="bxtxt txt">Points: </span> {{ answer.points_earned }}/{{ answer.quiz.points }}</div>
            
        </div>
    </div>
</template>
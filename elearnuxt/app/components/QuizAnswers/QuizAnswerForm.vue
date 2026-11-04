<!-- CourseForm.vue -->
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useGetAll } from '~/composables/useGetAll'
const { getAll } = useGetAll('allQuizzes')
const quizzes = ref()

onMounted(async()=>{
  quizzes.value = await getAll()
  //console.log('users', users.value)
})
const props = defineProps({
  quiz_answer: {
    type: Object,
    default: () => ({
      answer: '',
      is_correct: '',
      quiz_id: null

    })
  }
})

const emit = defineEmits(['submit'])

const form = ref({
  answer: props.quiz_answer.answer,
  is_correct: props.quiz_answer.is_correct,
  quiz_id: props.quiz_answer.quiz_id
})

const submit = () => {
  emit('submit', form.value)
}



</script>

<template>
  <form @submit.prevent="submit">
    <div class="form-group">
      <label
        for="users"
        class="form-label"  
      >
      Quiz:
      </label>
    </div>
    <div class="form-group mb-2">
      <select v-model="form.quiz_id" id="">
        <option v-for="quiz in quizzes" :value="quiz.id">
          {{ quiz.question }}
        </option>
      </select>
    </div>
    <div class="form-group">
      <label
        for="answer"
        class="form-label"  
      >
      Réponse:
      </label>
    </div>
    <div class="form-group mb-2">
      <input v-model="form.answer"
      class="form-control"
          placeholder="Réponse"
      >
    </div>
    <div class="form-group">
      <label
        for="is_correct"
        class="form-label"  
      >
      Correcte:
      </label>
    </div>
    <div class="form-group mb-2">
      <input v-model="form.is_correct"
      class="form-control"
          placeholder="Correcte"
      >
    </div>
    
  
  
    <button type="submit"
      class="btn btn-primary"
    >
      Enregistrer
    </button>
  </form>
</template>
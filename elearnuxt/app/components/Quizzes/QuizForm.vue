<!-- CourseForm.vue -->
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useGetAll } from '~/composables/useGetAll'
const { getAll } = useGetAll('allLessons')

const lessons = ref()
const addOption = () => {
  form.value.options.push({
    text: '',
    correct: false
  })
}

const removeOption = (index: number) => {
  form.value.options.splice(index, 1)
}
onMounted(async () => {
  lessons.value = await getAll()
})
const props = defineProps({
  quiz: {
    type: Object,
    default: () => ({
      question: '',
      note: '',
      options: [],
      position: 0,
      next_position: 0,
      points: 0,
      lesson_id: null
    })
  },
  idlesson: {
    type: [Number, String],
    default: null
  }
})

const emit = defineEmits(['submit'])

const form = ref({
  question: props.quiz.question,
  note: props.quiz.note,
  options: props.quiz.options,
  position: props.quiz.position,
  next_position: props.quiz.next_position,
  points: props.quiz.points,
  lesson_id: props.idlesson ? Number(props.idlesson) : (props.quiz.lesson_id ? Number(props.quiz.lesson_id) : null)
})
const submit = () => {
  emit('submit', form.value)
}

</script>

<template>
  <form @submit.prevent="submit">
    <div class="form-group">
      <label for="users" class="form-label">
        Leçon:
      </label>
    </div>
    <div class="form-group mb-2">
      <select v-model="form.lesson_id" id="">
        <option v-for="lesson in lessons" :value="lesson.id">
          {{ lesson.title }}
        </option>
      </select>
    </div>
    <div class="form-group">
      <label for="position" class="form-label">
        Position:
      </label>
    </div>
    <div class="form-group mb-2">
      <input v-model="form.position" type="number" class="form-control" placeholder="Position">
    </div>

    <div class="form-group">
      <label for="next_position" class="form-label">
        Next Position:
      </label>
    </div>
    <div class="form-group mb-2">
      <input v-model="form.next_position" type="number" class="form-control" placeholder="Next Position">
    </div>

    <div class="form-group">
      <label for="points" class="form-label">
        Points:
      </label>
    </div>
    <div class="form-group mb-2">
      <input v-model="form.points" type="number" class="form-control" placeholder="Points">
    </div>

    <div class="form-group">
      <label for="question" class="form-label">
        Question:
      </label>
    </div>
    <div class="form-group mb-2">
      <TipRichEditor v-model="form.question" />
    </div>
    <div class="form-group">
      <label for="note" class="form-label">
        Note:
      </label>
    </div>
    <div class="form-group mb-2">
      <TipRichEditor v-model="form.note" />
    </div>
    <div class="form-group mb-2">
      <div v-for="(option, index) in form.options" :key="index" class="mb-2">
        <!-- <input v-model="option.text" class="form-control mb-1" placeholder="Option"> -->
        <TipRichEditor class="form-control mb-1" v-model="option.text" />

        <label>
          <input type="checkbox" v-model="option.correct">
          Bonne réponse
        </label>
        <PersonMybutton @click="removeOption(index)" style="background-color: red;">
          Supprimer
        </PersonMybutton>
      </div>

      <PersonMybutton type="button" @click="addOption" style="background-color: green;">
        Ajouter une option
      </PersonMybutton>
      <!-- <button type="button" @click="addOption" style="background-color: green;">
        Ajouter une option
      </button> -->
    </div>
    <button type="submit" class="btn btn-primary">
      Enregistrer
    </button>
  </form>
</template>
<!-- CourseForm.vue -->
<script setup lang="ts">
import { ref, computed, watch } from 'vue'

const props = defineProps({
  quiz: {
    type: Object,
    default: () => ({
      id: null,
      question: '',
      options: [],
      lesson_id: null
    })
  }
})

const emit = defineEmits(['submit'])

const form = ref({
  selected_answer: null as string | null,   // réponse unique
  selected_array: [] as string[]            // réponses multiples
})

// Plusieurs bonnes réponses ?
const isMultiple = computed(
  () => props.quiz.options.filter((o: any) => o.correct === true).length > 1
)

// Mélange des options + reset quand le quiz change
const shuffledOptions = ref<any[]>([])
watch(
  () => props.quiz,
  (quiz) => {
    if (quiz) {
      shuffledOptions.value = [...quiz.options].sort(() => Math.random() - 0.5)
      form.value.selected_answer = null
      form.value.selected_array = []
    }
  },
  { immediate: true }
)

const isActive = (text: string) =>
  isMultiple.value
    ? form.value.selected_array.includes(text)
    : form.value.selected_answer === text

const submit = () => {
  const data = new FormData()
  data.append('quiz_id', props.quiz.id)

  if (isMultiple.value) {
    // Envoi en tableau : selected_answer[]
    form.value.selected_array.forEach((answer) => {
      data.append('selected_answer[]', answer)
    })
  } else {
    data.append('selected_answer', form.value.selected_answer ?? '')
  }

  emit('submit', data)
}
</script>

<template>
  <form @submit.prevent="submit">
    <TipRichEditor v-model="props.quiz.question"/>
    <div v-for="option in shuffledOptions" :key="option.text" class="mb-2">
      <label class="option-response" :class="{ active: isActive(option.text) }">

        <!-- Plusieurs réponses : checkbox + tableau -->
        <input
          v-if="isMultiple"
          type="checkbox"
          :value="option.text"
          v-model="form.selected_array"
        />

        <!-- Une seule réponse : radio -->
        <input
          v-else
          type="radio"
          :value="option.text"
          v-model="form.selected_answer"
        />

        <span :class="isMultiple ? 'checkbox' : 'radio'"></span>
        <span>{{ option.text }}</span>
      </label>
    </div>

    <PersonMybutton type="submit" class="voir">
      Continuer
    </PersonMybutton>
  </form>
</template>
<style scoped>
.option-response {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px;
  border: 2px solid #ddd;
  border-radius: 10px;
  cursor: pointer;
  transition: .2s;
}

.option-response:hover {
  border-color: var(--couleur-principale);
  background: var(--couleur-quaternaire);
}

.option-response input {
  display: none;
}

/* Faux radio */
.radio {
  width: 20px;
  height: 20px;
  border: 2px solid var(--couleur-principale);
  border-radius: 50%;
  position: relative;
}

.checkbox {
  width: 20px;
  height: 20px;
  border: 2px solid var(--couleur-principale);
  border-radius: 5%;
  position: relative;
}

.option-response.active {
  border-color: var(--couleur-principale);
  background: var(--couleur-quaternaire);
}

.option-response.active .radio::after {
  content: "";
  position: absolute;
  width: 10px;
  height: 10px;
  background: var(--couleur-principale);
  border-radius: 50%;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.option-response.active .checkbox::after {
  content: "";
  position: absolute;
  width: 10px;
  height: 10px;
  background: var(--couleur-principale);
  border-radius: 5%;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
</style>
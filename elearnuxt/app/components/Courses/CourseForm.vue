<!-- CourseForm.vue -->
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useGetAll } from '../../composables/useGetAll'

const { getAll } = useGetAll('allUsers')

const teachers = ref()

onMounted(async () => {
  teachers.value = await getAll()
  //console.log('users', users.value)
})


const props = defineProps({
  course: {
    type: Object,
    default: () => ({
      title: '',
      description: '',
      pdf_url: ''
    })
  }
})

const emit = defineEmits(['submit'])

const form = ref({
  title: props.course.title,
  description: props.course.description,
  teacher_id: props.course.teacher_id,
  pdf: null as File | null
})
const fileName = ref('')

const submit = () => {
  const data = new FormData()
  data.append('title', form.value.title)
  data.append('description', form.value.description)
  data.append('teacher_id', form.value.teacher_id)
  if (form.value.pdf) {
    data.append('pdf', form.value.pdf)
  }
  emit('submit', data)
}
const onFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files?.length) {
    form.value.pdf = target.files[0]
    fileName.value = target.files[0].name
  }
}
</script>
<template>
  <form @submit.prevent="submit">
    <div class="form-group">
      <label for="users" class="form-label">
        Professeur:
      </label>
    </div>
    <div class="form-group mb-2">
      <select v-model="form.teacher_id" id="">
        <option v-for="teacher in teachers" :value="teacher.id">
          {{ teacher.name }}
        </option>
      </select>
    </div>
    <div class="form-group">
      <label for="title" class="form-label">
        Titre:
      </label>
    </div>
    <div class="form-group mb-2">
      <input v-model="form.title" class="form-control" placeholder="Titre">
    </div>
    <div class="form-group">
      <label for="description" class="form-label">
        Description:
      </label>
    </div>
    <div class="form-group mb-2">
      <TipRichEditor v-model="form.description" />
    </div>
    <input id="pdf-upload" type="file" accept=".pdf" class="d-none" @change="onFileChange">
    <div class="form-group mb-2">
      <label for="pdf-upload" class="btn btn-outline-danger">
        <Icon name="tabler:file-type-pdf" />
        Sélectionner un PDF
      </label>

      <span v-if="fileName" class="ms-2">
        {{ fileName }}
      </span>
    </div>

    <button type="submit" class="btn btn-primary">
      Enregistrer
    </button>
  </form>
</template>
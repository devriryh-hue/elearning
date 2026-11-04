<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useGetAll } from '~/composables/useGetAll'

const emit = defineEmits(['submit'])
const form = ref({
  course_id: null as number | null,
  cours_title: '',
  lesson_title: '',
  json: null as File | null
}
)
const nouveauCours = ref(false)
const { getAll } = useGetAll('allCourses')
const submit = () => {
  const data = new FormData()
  if (form.value.json) {
    data.append('json_file', form.value.json)
  }
  data.append('cours_title', form.value.cours_title)
  data.append('course_id', form.value.course_id)
  data.append('lesson_title', form.value.lesson_title)
  emit('submit', data)
}
const fileName = ref()
const courses = ref([])
const onFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files?.length) {
    form.value.json = target.files[0]
    fileName.value = target.files[0]?.name
  }
}
const newCourse = () => {
  form.value.course_id = null
  nouveauCours.value = !nouveauCours.value
}
onMounted(async () => {
  courses.value = await getAll()
  //console.log('courses', courses.value)
})
</script>
<template>
  <form @submit.prevent="submit">
    <div class="form-group mb-2">
      <select v-model="form.course_id" id="">
        <option v-for="course in courses" :value="course.id">
          {{ course.title }}
        </option>
      </select>
    </div>
    <div class="form-group mb-2">
      <button type="button" class="btn btn-outline-primary" @click="newCourse">
        <Icon name="tabler:plus" />
        Nouveau cours
      </button>
    </div>
    <div v-if="nouveauCours">
      <div class="form-group">
        <label for="cours_title" class="form-label">
          Titre du cours:
        </label>
      </div>
      <div class="form-group mb-2">
        <input v-model="form.cours_title" class="form-control" placeholder="Titre">
      </div>
    </div>

    <div class="form-group">
      <label for="lesson_title" class="form-label">
        Titre du leçon:
      </label>
    </div>
    <div class="form-group mb-2">
      <input v-model="form.lesson_title" class="form-control" placeholder="Titre">
    </div>
    <input id="json_file" type="file" accept=".json" class="d-none" @change="onFileChange">
    <div class="form-group mb-2">
      <label for="json_file" class="btn btn-outline-danger">
        <Icon name="tabler:file-type-pdf" />
        Sélectionner un fichier json
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
<!-- CourseForm.vue -->
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useGetAll } from '~/composables/useGetAll'

const props = defineProps({
  lesson: {
    type: Object,
    default: () => ({
      title: '',
      video_url: '',
      course_id: null
    })
  }
})
const { getAll } = useGetAll('allCourses')
const course = ref()
const emit = defineEmits(['submit'])

const form = ref({
  title: props.lesson.title,
  video_url: props.lesson.video_url,
  course_id: props.lesson.course_id,
  description: props.lesson.description
})

const submit = () => {
  emit('submit', form.value)
}

onMounted(async()=>{
  course.value = await getAll()
  //console.log('users', users.value)
})

</script>

<template>
  <form @submit.prevent="submit">
    <div class="form-group">
      <label
        for="users"
        class="form-label"  
      >
      Cours:
      </label>
    </div>
    <div class="form-group mb-2">
      <select v-model="form.course_id" id="">
        <option v-for="course in course" :value="course.id">
          {{ course.title }}
        </option>
      </select>
    </div>
    <div class="form-group">
      <label
        for="title"
        class="form-label"  
      >
      Titre:
      </label>
    </div>
    <div class="form-group mb-2">
      <input v-model="form.title"
      class="form-control"
          placeholder="titre"
      >
    </div>
    <div class="form-group">
      <label
        for="description"
        class="form-label"  
      >
      description:
      </label>
    </div>
    <div class="form-group mb-2">
      <TipRichEditor v-model="form.description" />
    </div>
    <div class="form-group">
      <label
        for="video_url"
        class="form-label"  
      >
      Url de la vidéo:
      </label>
    </div>
    <div class="form-group mb-2">
      <input v-model="form.video_url"
      class="form-control"
          placeholder="Url de la vidéo"
      >
    </div>
    
  
  
    <button type="submit"
      class="btn btn-primary"
    >
      Enregistrer
    </button>
  </form>
</template>
<!-- CourseForm.vue -->
<script setup lang="ts">
import { ref, onMounted } from 'vue'

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
const emit = defineEmits(['submit'])

const form = ref({
  title: props.lesson.title,
  video_url: props.lesson.video_url,
  course_id: props.lesson.course_id,
  course: props.lesson.course
})
//const course = form.value.course





</script>

<template>
  <div class="divcours">
    <div class="form-group">
      Cours:
    </div>
    <div class="form-group mb-2">
      <h3>{{ form.course.title }}</h3>
    </div>

    <div class="form-group mb-2">
      Titre:
    </div>
    <div class="form-group mb-2">
      Titre: {{ form.title }}
    </div>
  </div>
</template>
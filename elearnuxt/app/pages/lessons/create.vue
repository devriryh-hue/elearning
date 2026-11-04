<!-- create.vue -->
<script setup lang="ts">
import { useRouter } from 'vue-router'
//import { useCrud } from '../../composables/useCrud'

import { useCrud } from '~/composables/useCrud'
import LessonForm from '~/components/Lessons/LessonForm.vue'
definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const router = useRouter()

const { createCrud } = useCrud('lessons')

const save = async (data: any) => {
  await createCrud(data)

  router.push('/lessons')
}
</script>

<template>
  <LessonForm @submit="save" />
</template>
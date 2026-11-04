<!-- edit.vue -->
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useCrud } from '~/composables/useCrud'   
import LessonForm from '~/components/Lessons/LessonForm.vue'

definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const route = useRoute()
const router = useRouter()
const { $api } = useNuxtApp()
const { updateCrud } = useCrud('lessons')

const lesson = ref()

onMounted(async () => {
  lesson.value = await $api(`/lessons/${route.params.id}`)
})

const save = async (data: any) => {
  await updateCrud(Number(route.params.id), data)

 router.push('/lessons')
}
</script>

<template>
  <LessonForm
    v-if="lesson"
    :lesson="lesson"
    @submit="save"
  />
</template>
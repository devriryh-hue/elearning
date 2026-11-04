<!-- create.vue -->
<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useCrud } from '~/composables/useCrud'
import QuizForm from '~/components/Quizzes/QuizForm.vue'
definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const router = useRouter()

const { createCrud } = useCrud('quizzes')

const save = async (data: any) => {
  if (data.position != data.next_position) {
    await createCrud(data)
    router.push('/quizzes')
  }else{
    alert('le position doit être différente de next position, sinon on aura une boucle')
  }

}
</script>

<template>
  <QuizForm @submit="save" />
</template>
<!-- create.vue -->
<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useUsers } from '~/composables/useUsers'
import UserForm from '~/components/Users/UserForm.vue'
definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const router = useRouter()

const { createUser } = useUsers()

const save = async (data: any) => {
  await createUser(data)

  router.push('/users')
}
</script>

<template>
  <UserForm @submit="save" />
</template>
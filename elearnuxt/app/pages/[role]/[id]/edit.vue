<!-- edit.vue -->
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useUsers } from '~/composables/useUsers'   
import UserForm from '~/components/Users/UserForm.vue'

definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const route = useRoute()
const router = useRouter()
const { $api } = useNuxtApp()
const { updateUser } = useUsers()

const user = ref()

onMounted(async () => {
  user.value = await $api(`/users/${route.params.id}`)
})

const save = async (data: any) => {
  await updateUser(Number(route.params.id), data)

  router.push('/users')
}
</script>

<template>
  <UserForm
    v-if="user"
    :user="user"
    @submit="save"
  />
</template>
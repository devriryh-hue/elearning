<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useUsers } from '~/composables/useUsers'
import Mybutton from '~/components/person/Mybutton.vue'
//const config = useRuntimeConfig()
import { useRole } from '~/composables/useRole'


definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
/**pagination */
const {
  page,
  data: users
} = usePagination('courses')
//const users = ref([])

const { role } = useRole()
const { getUsers, deleteUser } = useUsers()

const loadUsers = async () => {
  users.value = await getUsers(role.value)
}
const editUser = (id: number) => {
  // Rediriger vers la page d'édition de l'utilisateur
  window.location.href = `/users/${id}/edit`
}
onMounted(async () => {

  await loadUsers()
})

const removeUser = async (id: number) => {
  await deleteUser(id)
  await loadUsers()
}

</script>
<template>

  <div v-for="user in users?.data" :key="user.id">
    <h3>{{ user.name }}</h3>
    <span>
      <img :src="user.avatar_url" alt="Avatar"
        style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
    </span>
    <p>{{ user.email }}</p>
    {{ user.role }}

    <br>
    <Mybutton @click="editUser(user.id)" style="background-color: #ffd100;">
      <Icon name="tabler:edit" />
      Modifier
    </Mybutton>
    <Mybutton @click="removeUser(user.id)" style="background-color: red;">
      <Icon name="ant-design:delete-twotone" />
      Supprimer
    </Mybutton>

    <hr>
  </div>
  <Pagination v-if="users" :current-page="users.current_page" :last-page="users.last_page" @change="page = $event" />
</template>
<script setup lang="ts">
import { ref } from 'vue'
//import api from '../services/api'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/authStore'
const router = useRouter()
const auth = useAuthStore()
const form = ref({
  email: '',
  password: ''
})
/*
const login = async () => {

  try {

    const response = await api.post('/login', form.value)

    localStorage.setItem('token', response.data.token)

    alert('Connecté')

  } catch (error) {

    console.error(error)
  }
}*/
const login = async () => {
  const success = await auth.login(form.value)
  if (success) {
      router.push('/courses')
    }
}
</script>

<template>

  <div>

    <h1>Login</h1>

    <form @submit.prevent="login">

      <input
        v-model="form.email"
        type="email"
        placeholder="Email"
      />

      <input
        v-model="form.password"
        type="password"
        placeholder="Password"
      />

      <button type="submit">
        Login
      </button>

    </form>

  </div>

</template>
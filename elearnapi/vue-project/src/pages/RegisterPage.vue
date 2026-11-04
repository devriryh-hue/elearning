<script setup lang="ts">
import { ref } from 'vue'
import api from '../services/api'

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const message = ref('')
const errors = ref<any>({})

const register = async () => {
  message.value = ''
  errors.value = {}

  try {
    const response = await api.post('/register', form.value)

    message.value = response.data.message

    form.value = {
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    }

  } catch (error: any) {

    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    } else {
      message.value = 'Erreur serveur'
    }

    console.error(error)
  }
}
</script>

<template>
  <div>
    <h1>Inscription</h1>

    <form @submit.prevent="register">

      <div>
        <label>Nom</label>

        <input
          v-model="form.name"
          type="text"
        />

        <p v-if="errors.name">
          {{ errors.name[0] }}
        </p>
      </div>

      <div>
        <label>Email</label>

        <input
          v-model="form.email"
          type="email"
        />

        <p v-if="errors.email">
          {{ errors.email[0] }}
        </p>
      </div>

      <div>
        <label>Mot de passe</label>

        <input
          v-model="form.password"
          type="password"
        />

        <p v-if="errors.password">
          {{ errors.password[0] }}
        </p>
      </div>

      <div>
        <label>Confirmation mot de passe</label>

        <input
          v-model="form.password_confirmation"
          type="password"
        />
      </div>

      <button type="submit">
        S'inscrire
      </button>

    </form>

    <p>{{ message }}</p>
  </div>
</template>
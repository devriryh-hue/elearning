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

const login = async () => {
  const success = await auth.login(form.value)
  if (success) {
    router.push('/courses')
  }
}

const showPassword = ref(false)
</script>

<template>

  <div class="container">
    <div class="mydivlogin">
      <div class="mylogin">
        <h1>Login</h1>

        <form @submit.prevent="login">
          <div class="form-group">
            <label for="email" class="form-label">
              Email:
            </label>
          </div>
          <div class="form-group mb-2">
            <div class="inputlogin">
              <input v-model="form.email" type="email" placeholder="Email" />

            </div>
          </div>

          <div class="form-group">
            <label for="password" class="form-label">
              Mot de passe:
            </label>
          </div>
          <div class="form-group mb-2 password-wrapper">
            <div class="inputlogin">
              <!-- <input v-model="form.password" type="password" placeholder="Mot de passe" /> -->
              <input v-model="form.password" :type="showPassword ? 'text' : 'password'" placeholder="Mot de passe" />
              <Icon :name="showPassword ? 'heroicons:home' : 'heroicons:users'" @click="showPassword = !showPassword" class="toggle-password" />
            </div>
          </div>
          <div class="form-group mb-2">
            <button type="submit">
              Login
            </button>
          </div>

        </form>
      </div>
    </div>

  </div>

</template>
<style>
.mylogin {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.mydivlogin {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.password-wrapper {
  position: relative;
}

.toggle-password {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
}
</style>
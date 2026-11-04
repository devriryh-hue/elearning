<script setup>
import { useAuthStore } from '../stores/authStore'

const auth = useAuthStore()
</script>
<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

      <router-link
        class="navbar-brand"
        to="/"
      >
        E-learning
      </router-link>

      <div class="navbar-nav">
        <div v-if="auth.isLoggedIn">
            <span style="color: white; margin-right: 10px;" >
            Bonjour {{ auth.user?.name }}
            </span>
          <router-link
          class="nav-link"
          to="/courses"
        >
          Cours
        </router-link>
          <button class="btn btn-link nav-link" @click="auth.logout">
            Logout
          </button>
        </div>
        <div v-else>    

        <router-link
          class="nav-link"
          to="/login"
        >
          Login
        </router-link>
        </div>
      </div>

    </div>
  </nav>
</template>
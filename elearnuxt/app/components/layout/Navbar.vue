<script setup>
import { useAuthStore } from '~/stores/authStore'
import Mybutton from '../person/Mybutton.vue'

const auth = useAuthStore()
const { notifications, unreadCount, fetchNotifications, markAsRead } = useNotifications()

const open = ref(false)
const dropdownRef = ref(null)
let timer = null

const startPolling = () => {
  stopPolling()
  fetchNotifications()
  timer = setInterval(fetchNotifications, 60_000) // rafraîchit chaque minute
}

const stopPolling = () => {
  if (timer) {
    clearInterval(timer)
    timer = null
  }
}

const onClickOutside = (e) => {
  if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
    open.value = false
  }
}

const openNotification = async (n) => {
  await markAsRead(n.id)
  open.value = false
  // À adapter à ta route de détail de cours
  navigateTo(`/courses/${n.data.course_id}`)
}

const formatDate = (d) => new Date(d).toLocaleDateString('fr-FR')

// onMounted : pas de timer ni d'écouteur côté serveur (SSR)
onMounted(() => {
  document.addEventListener('click', onClickOutside)

  watch(
    () => auth.isLoggedIn,
    (loggedIn) => {
      if (loggedIn) {
        startPolling()
      } else {
        stopPolling()
        notifications.value = []
        open.value = false
      }
    },
    { immediate: true }
  )
})

onBeforeUnmount(() => {
  stopPolling()
  document.removeEventListener('click', onClickOutside)
})
</script>

<template>
  <nav class="navbar navbar-expand-lg ericnav">
    <div class="container">

      <router-link class="navbar-brand" style="color: white;" to="/">
        E-learning
      </router-link>
      <router-link class="navbar-brand" style="color: white;" to="/dashboard/goals">
        Objectif
      </router-link>
      <router-link class="navbar-brand" style="color: white;" to="/competence/ind">
        Compétences/Quizz
      </router-link>

      <div class="navbar-nav">
        <div v-if="auth.isLoggedIn" class="d-flex align-items-center">

          <!-- Cloche de notifications -->
          <div ref="dropdownRef" class="position-relative me-2">
            <button
              type="button"
              class="btn btn-link nav-link position-relative"
              aria-label="Notifications"
              :aria-expanded="open"
              @click="open = !open"
            >
              <Icon name="majesticons:bell-line" />
              <span
                v-if="unreadCount > 0"
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
              >
                {{ unreadCount > 9 ? '9+' : unreadCount }}
              </span>
            </button>

            <ul
              v-if="open"
              class="dropdown-menu dropdown-menu-end show"
              style="min-width: 300px; right: 0; left: auto;"
            >
              <li
                v-if="notifications.length === 0"
                class="dropdown-item-text text-muted"
              >
                Aucune notification
              </li>
              <li v-for="n in notifications" :key="n.id">
                <a class="dropdown-item text-wrap" href="#" @click.prevent="openNotification(n)">
                  {{ n.data.message }}
                  <small class="d-block text-muted">{{ formatDate(n.created_at) }}</small>
                </a>
              </li>
            </ul>
          </div>

          <mybutton class="btn btn-link nav-link" @click="auth.logout">
            <Icon name="majesticons:logout" />
          </mybutton>
        </div>

        <div v-else>
          <router-link class="nav-link" to="/login">
            Login
          </router-link>
        </div>
      </div>

    </div>
  </nav>
</template>
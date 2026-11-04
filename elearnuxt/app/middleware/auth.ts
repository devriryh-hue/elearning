//import { useAuthStore } from '~/stores/authStore'    
export default defineNuxtRouteMiddleware(() => {
  const auth = useAuthStore()

  if (!auth.isLoggedIn) {
    return navigateTo('/login')
  }
})
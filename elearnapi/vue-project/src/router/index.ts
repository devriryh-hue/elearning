import { createRouter, createWebHistory } from 'vue-router'
import TestPage from '../pages/TestPage.vue'
import RegisterPage from '../pages/RegisterPage.vue'
import CoursesPage from '../pages/CoursesPage.vue'
import LoginPage from '../pages/LoginPage.vue'

const routes = [
    {
        path: '/test',
        component: TestPage
    },
    {
        path: '/register',
        component: RegisterPage
    },
    {
        path: '/courses',
        component: CoursesPage
    },
    {
        path: '/login',
        component: LoginPage
    }
]
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router

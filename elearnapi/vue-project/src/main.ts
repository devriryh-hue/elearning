import { createApp } from 'vue'
import { createPinia } from 'pinia'
//import store from './stores' //vuex (trop ancien pour vue3)
//ici insertion bootstrap
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'



import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(createPinia())
app.use(router)
//app.use(store)

app.mount('#app')

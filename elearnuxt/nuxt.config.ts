// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  css: [
    'bootstrap/dist/css/bootstrap.min.css'
  ],
  runtimeConfig: {
    public: {
      //a changer en mode phone ou partage réseau
      apiUrl: 'http://localhost:8001/api',
      apiBase: 'http://localhost:8001/api', // valeur par défaut
      // apiUrl: 'http://192.168.1.170:8001/api'
    }
  },
  modules: [
    '@pinia/nuxt',
    '@nuxt/icon',
    //'@nuxt/fonts',
    '@nuxt/image',
    'pinia-plugin-persistedstate/nuxt'
  ],
  icon: {
    provider: 'iconify'
  },
  /*fonts: {
    families: [
      { name: 'Poppins' }
    ]
  },*/
  //à décommenter pour utiliser le proxy
  /*devServer: {
    host: '192.168.1.170',
    port: 3000
  }*/
 devServer: {
  port: 3001
  }
})
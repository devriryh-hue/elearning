export default defineNuxtPlugin(() => {
  const config = useRuntimeConfig()
  const baseUrl = config.public.apiUrl
  const token = useCookie('token')
  const api = $fetch.create({
    baseURL: baseUrl,
    onRequest({ options }) {
      if (token.value) {
        options.headers.set(
          'Authorization',
          `Bearer ${token.value}`
        )
      }
    }
  })

  return {
    provide: { api }
  }
})
import { ref, watch } from 'vue'

export function usePageWithLesson(endpoint: string, perPage = 5) {
  const page = ref(1)
  const config = useRuntimeConfig()
    //const urlPage = ref(`http://localhost:8000/api/${endpoint}?page=${page.value}&per_page=${perPage}`)
    const urlPage = computed(
  () =>
    //`http://localhost:8000/api/${endpoint}?page=${page.value}&per_page=${perPage}`
  config.public.apiUrl + `/${endpoint}?page=${page.value}&per_page=${perPage}`
)
watch(page, (newPage) => {
  console.log('Nouvelle page :', newPage)
})
    //console.log('urlPage.value', urlPage.value)
  const { data, pending, refresh } = useFetch(
    () => urlPage.value
  )

  watch(page, () => refresh())

  return {
    page,
    data,
    pending
  }
}
import { ref, watch, computed, type Ref } from 'vue'

export function usePagination(
  endpoint: string,
  perPage = 5,
  filters: Ref<Record<string, any>> = ref({}),
) {
  const page = ref(1)
  const config = useRuntimeConfig()

  const urlPage = computed(() => {
    const params = new URLSearchParams({
      page: String(page.value),
      per_page: String(perPage)
    })
    
    Object.entries(filters.value).forEach(([key, value]) => {
      if (value !== '' && value !== null && value !== undefined) {
        params.append(key, String(value))
      }
    })

    return `${config.public.apiUrl}/${endpoint}?${params.toString()}`
  })

  const { data, pending, refresh } = useFetch(() => urlPage.value)

  // Un seul watcher : dès que la page OU les filtres changent, on refetch
  watch([page, filters], () => {
    refresh()
  }, { deep: true })

  return {
    page,
    data,
    pending,
    refresh
  }
}
export const useGetAll = (endpoint: string) => {
  const { $api } = useNuxtApp()

  const getAll = (query = {}) =>
    $api(`/${endpoint}`, {
      query
    })

  return {
    getAll
  }
}
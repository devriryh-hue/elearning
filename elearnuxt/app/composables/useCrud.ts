// composables/useCourses.ts
type Id = string | number

export const useCrud = (endpoint: string) => {
  const { $api } = useNuxtApp()

 const getCrud = (params = {}) => {
  return $api(`/${endpoint}`, {
    method: 'GET',
    query: params
  })
}
  const showCrud = (id: Id) =>
  $api(`/${endpoint}/${id}`)
  const createCrud = (formData: FormData) =>
    $api(`/${endpoint}`, {
      method: 'POST',
      body: formData
    })

const updateCrud = (id: number, data: any) => {
  
 if (data instanceof FormData) {
    data.append('_method', 'PUT')

    return $api(`/${endpoint}/${id}`, {
      method: 'POST',
      body: data
    })
  }

  return $api(`/${endpoint}/${id}`, {
    method: 'PUT',
    body: data
  })
}
  const deleteCrud = (id: number) =>
    $api(`/${endpoint}/${id}`, {
      method: 'DELETE'
    })

  return {
    showCrud,
    getCrud,
    createCrud,
    updateCrud,
    deleteCrud
  }
}
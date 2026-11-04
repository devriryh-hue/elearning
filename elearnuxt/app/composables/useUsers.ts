// composables/useUsers.ts

export const useUsers = () => {
  const { $api } = useNuxtApp()

  const getUsers = async (role: string) => {
    return await $api('/users', {
      query: {
        role
      }
    })
  }
/*
  const createUser = (data: any) =>
    $api('/users', {
      method: 'POST',
      body: data
    })
*/
const createUser = async (formData: FormData) => {
  return await $api('/users', {
      method: 'POST',
      body: formData
    })
}
  const updateUser = async (id: number, formData: FormData) =>
    await $api(`/users/${id}`, {
      method: 'PUT',
      body: formData
    })

  const deleteUser = async (id: number) =>
    await $api(`/users/${id}`, {
      method: 'DELETE'
    })

  return {
    getUsers,
    createUser,
    updateUser,
    deleteUser
  }
}
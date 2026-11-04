// app/composables/useGoals.ts
export const useGoals = () => {
  const { $api } = useNuxtApp()

  return {
    list: () => $api('/goals'),
    get: (id: string | number) => $api(`/goals/${id}`),
    create: (payload: any) => $api('/goals', { method: 'POST', body: payload }),
    remove: (id: number) => $api(`/goals/${id}`, { method: 'DELETE' }),
  }
}
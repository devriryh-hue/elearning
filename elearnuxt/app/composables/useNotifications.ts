// composables/useNotifications.ts
export const useNotifications = () => {
  const { $api } = useNuxtApp() // à appeler ici, en haut du composable
  const notifications = useState<any[]>('notifications', () => [])
  const unreadCount = computed(() => notifications.value.length)

  const fetchNotifications = async () => {
    try {
      notifications.value = await $api('/notifications')
    } catch (e) {
      console.error('Notifications :', e)
    }
  }

  const markAsRead = async (id: string) => {
    try {
      await $api(`/notifications/${id}/read`, { method: 'POST' })
      notifications.value = notifications.value.filter((n) => n.id !== id)
    } catch (e) {
      console.error('Notifications :', e)
    }
  }

  return { notifications, unreadCount, fetchNotifications, markAsRead }
}
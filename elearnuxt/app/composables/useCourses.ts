// composables/useCourses.ts

export const useCourses = () => {
  const { $api } = useNuxtApp()

  const getCourses = () => $api('/courses')
  //const getCoursesWithLessons = () => $api('/withlessons')
const getCoursesWithLessons = (params = {}) => {
  return $api(`/withlessons`, {
    method: 'GET',
    query: params
  })
}
  const createCourse = (formData: FormData) =>
    $api('/courses', {
      method: 'POST',
      body: formData
    })
  const createJson = (formData: any) => {
    if (formData instanceof FormData) {
      return $api('/uploadjson', {
        method: 'POST',
        body: formData
      })
    }
  }
  const createJsonbd = (formData: any) => {
    if (formData instanceof FormData) {
      return $api('/uploadjsonformatbd', {
        method: 'POST',
        body: formData
      })
    }
  }


  const updateCourse = (id: number, formData: FormData) =>
    $api(`/courses/${id}`, {
      method: 'PUT',
      body: formData
    })

  const deleteCourse = (id: number) =>
    $api(`/courses/${id}`, {
      method: 'DELETE'
    })

  return {
    getCourses,
    getCoursesWithLessons,
    createCourse,
    updateCourse,
    deleteCourse,
    createJson,
    createJsonbd
  }
}
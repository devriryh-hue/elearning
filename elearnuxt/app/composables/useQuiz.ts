// composables/useCourses.ts
type Id = string | number

export const useQuiz = () => {
  const { $api } = useNuxtApp()

  const start = async (id: number) =>
    await $api(`/lessons/${id}/attempts`, {
      method: 'POST',
    })
  const saveattempt = async (id: number, formData: FormData) =>
    await $api(`/attempts/${id}/answers`, {
      method: 'POST',
      body: formData
    })

  const finishAttempt = (id: number) =>
    $api(`/attempts/${id}/finish`, {
      method: 'POST'
    })
const getAttemptAnswers = (id: number) =>
    $api(`/attempts/${id}/details`, {
      method: 'GET'
    })
  const getAttempt = (id: number) =>
    $api(`/attempts/${id}`, {
      method: 'GET'
    })
  //'/cours/{courses}/alllessons'
  const getLessonByCours = async (id: number) =>
    await $api(`/cours/${id}/alllessons`);
  const getQuizByLesson = async (id: number, pos: number) =>
    await $api(`/lessons/${id}/quizzes/${pos}`);
  const getEnrollments = async (course: number) =>
    await $api(`/cours/${course}/competence`);
  /*const takeEnrollments = async (course: number) =>
    await $api(`/cours/${course}/competence`);*/
  return {
    start,
    saveattempt,
    finishAttempt,
    getAttempt,
    getLessonByCours,
    getQuizByLesson,
    getEnrollments,
    getAttemptAnswers
  }
}
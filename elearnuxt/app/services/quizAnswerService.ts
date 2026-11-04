import api from './api'

export const getQuizAnswers = () => {
    return api.get('/quiz-answers')
}

export const createQuizAnswer = (data: any) => {
    return api.post('/quiz-answers', data)
}

export const updateQuizAnswer = (id: number, data: any) => {
    return api.put(`/quiz-answers/${id}`, data)
}

export const deleteQuizAnswer = (id: number) => {
    return api.delete(`/quiz-answers/${id}`)
}
import api from './api'

export const getQuiz = () => {
    return api.get('/quizzes')
}

export const createQuiz = (data: any) => {
    return api.post('/quizzes', data)
}

export const updateQuiz = (id: number, data: any) => {
    return api.put(`/quizzes/${id}`, data)
}

export const deleteQuiz = (id: number) => {
    return api.delete(`/quizzes/${id}`)
}
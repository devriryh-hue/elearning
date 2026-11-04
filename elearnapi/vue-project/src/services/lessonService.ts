import api from './api'

export const getLessons = () => {
    return api.get('/lessons')
}

export const createLesson = (data: any) => {
    return api.post('/lessons', data)
}

export const updateLesson = (id: number, data: any) => {
    return api.put(`/lessons/${id}`, data)
}

export const deleteLesson = (id: number) => {
    return api.delete(`/lessons/${id}`)
}
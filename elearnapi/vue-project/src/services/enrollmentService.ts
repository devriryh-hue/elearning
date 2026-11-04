import api from './api'

export const getEnrollments = () => {
    return api.get('/enrollments')
}

export const createEnrollment = (data: any) => {
    return api.post('/enrollments', data)
}

export const updateEnrollment = (id: number, data: any) => {
    return api.put(`/enrollments/${id}`, data)
}

export const deleteEnrollment = (id: number) => {
    return api.delete(`/enrollments/${id}`)
}
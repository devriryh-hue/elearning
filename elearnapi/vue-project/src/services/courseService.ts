import api from './api'

export const getCourses = () => {
    return api.get('/courses')
}

export const createCourse = (data: any) => {
    return api.post('/courses', data)
}

export const updateCourse = (id: number, data: any) => {
    return api.put(`/courses/${id}`, data)
}

export const deleteCourse = (id: number) => {
    return api.delete(`/courses/${id}`)
}
export const useRole = () => {
    const route = useRoute()

    const role = computed(() => route.params.role as string)

    const title = computed(() => ({
        students: 'Étudiants',
        professors: 'Professeurs',
        directors: 'Directeurs',
        secretaries: 'Secrétaires'
    })[role.value] ?? 'Utilisateurs')

    return {
        role,
        title
    }
}
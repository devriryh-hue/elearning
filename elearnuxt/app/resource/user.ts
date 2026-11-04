// resources/users.ts

export default {
    endpoint: '/users',

    singular: 'Utilisateur',

    plural: 'Utilisateurs',

    primaryKey: 'id',

    roles: {

        students: {

            label: 'Étudiants',

            color: 'blue',

            icon: 'graduation-cap',

            query: {
                role: 'student'
            }

        },

        professors: {

            label: 'Professeurs',

            color: 'green',

            icon: 'book',

            query: {
                role: 'professor'
            }

        }

    }

}
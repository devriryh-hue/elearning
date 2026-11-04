
export const sidebar = [
  {
    title: 'Dashboard',
    icon: 'heroicons:home',
    children: [
      {
        title: 'Quizz totalités',
        to: '/dashboard/quiz',
        icon: 'carbon:course',
      },
      {
        title: 'Objectif',
        to: '/dashboard/goals',
        icon: 'tabler:building-broadcast-tower-filled',
      }
    ]
  },
  {
    title: 'Objectifs',
    icon: 'tabler:building-broadcast-tower-filled',
    children: [
      {
        title: 'Liste',
        to: '/goals',
        icon: 'tabler:building-broadcast-tower-filled',
      },
      {
        title: 'Ajouter',
        to: '/goals/new',
        icon: 'mdi:plus-box-multiple',
      }
    ]
  },
  {
    title: 'Cours',
    icon: 'hugeicons:course',
    children: [
      {
        title: 'Liste',
        to: '/courses',
        icon: 'mdi:school',
      },
      {
        title: 'Ajouter',
        to: '/courses/create',
        icon: 'mdi:plus-box-multiple',
      }
    ]
  },
  {
    title: 'Utilisateurs',
    icon: 'heroicons:users',
    children: [
      {
        title: 'Listes',
        to: '/users',
        icon: 'heroicons:users',
      },
      {
        title: 'Ajouter',
        to: '/users/create',
        icon: 'mdi:plus-box-multiple',
      }
    ]
  },
  {
    title: 'Professeurs',
    icon: 'mdi:school',
    children: [
      {
        title: 'Listes',
        to: '/professor',
        icon: 'mdi:school',
      },
      {
        title: 'Ajouter',
        to: '/professor/create',
        icon: 'mdi:plus-box-multiple',
      }
    ]
  },
  {
    title: 'Etudiants',
    icon: 'mdi:school',
    children: [
      {
        title: 'Listes',
        to: '/student',
        icon: 'mdi:school',
      },
      {
        title: 'Ajouter',
        to: '/student/create',
        icon: 'mdi:plus-box-multiple',
      }
    ]
  },
  {
    title: 'Quizzes',
    icon: 'hugeicons:quiz-03',
    children: [
      {
        title: 'Liste',
        to: '/quizzes',
        icon: 'ic:baseline-list'
      },
      {
        title: 'Ajouter',
        to: '/quizzes/create',
        icon: 'ic:baseline-add'
      }
    ]
  },
  {
    title: 'Lessons',
    icon: 'streamline:class-lesson',
    children: [
      {
        title: 'Liste',
        to: '/lessons',
        icon: 'ic:baseline-list'
      },
      {
        title: 'Ajouter',
        to: '/lessons/create',
        icon: 'ic:baseline-add'
      }
    ]
  },
  {
    title: 'Quiz Answers',
    icon: 'fluent:quiz-48-regular',
    children: [
      {
        title: 'Liste',
        to: '/quiz_answers',
        icon: 'ic:baseline-list'
      },
      {
        title: 'Ajouter',
        to: '/quiz_answers/create',
        icon: 'ic:baseline-add'
      }
    ]
  }
]
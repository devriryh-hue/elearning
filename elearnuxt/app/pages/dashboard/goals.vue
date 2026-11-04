<!-- app/pages/dashboard/goals.vue -->
<script setup lang="ts">
const { $api } = useNuxtApp()
definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const { data, pending, error } = await useAsyncData(
  'dashboard-goals',
  () => $api('/dashboard/goals'),
  { server: false },
)

const selectedId = ref<number | null>(null)

// Sélectionne le premier objectif par défaut
watchEffect(() => {
  if (data.value?.goals?.length && selectedId.value === null) {
    selectedId.value = data.value.goals[0].id
  }
})
const router = useRouter()
const selected = computed(() =>
  data.value?.goals.find((g: any) => g.id === selectedId.value),
)

const trendText = (t: number) => (t > 0 ? `▲ +${t} pts` : t < 0 ? `▼ ${t} pts` : '= stable')
const trendClass = (t: number) => (t > 0 ? 'text-success' : t < 0 ? 'text-danger' : 'text-muted')

const commencer = (courseId: number, lessonId: number) => {
  // Implémente la logique pour commencer la leçon
  //console.log(`Commencer le cours ${courseId}, leçon ${lessonId}`)
  navigateTo(`/home/Cours/${courseId}/lessons/${lessonId}/voir`)
}

</script>

<template>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Mes objectifs</h2>
      <NuxtLink to="/goals/new" class="btn btn-primary">Nouvel objectif</NuxtLink>
    </div>

    <p v-if="pending">Chargement…</p>
    <p v-else-if="error" class="text-danger">Impossible de charger le tableau de bord.</p>

    <template v-else-if="data">
      <p v-if="!data.goals.length" class="text-muted">
        Aucun objectif pour le moment. Crée-en un pour suivre ta progression.
      </p>

      <template v-else>
        <!-- Résumé -->
        <div class="row g-3 mb-4">
          <div class="col-6 col-md-3">
            <div class="card text-center p-3">
              <div class="fs-3 fw-bold">{{ data.summary.active }}</div>
              <small>Objectifs actifs</small>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="card text-center p-3">
              <div class="fs-3 fw-bold">{{ data.summary.average_progress }}%</div>
              <small>Progression moyenne</small>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="card text-center p-3">
              <div class="fs-3 fw-bold">{{ data.summary.mastered }}</div>
              <small>Maîtrisés</small>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="card text-center p-3" :class="{ 'border-danger': data.summary.late > 0 }">
              <div class="fs-3 fw-bold" :class="{ 'text-danger': data.summary.late > 0 }">
                {{ data.summary.late }}
              </div>
              <small>En retard</small>
            </div>
          </div>
        </div>

        <!-- À faire aujourd'hui -->
        <div class="card p-3 mb-4">
          <h5>À faire aujourd'hui</h5>
          <p v-if="!data.today.length" class="text-muted mb-0">Rien d'urgent, bravo !</p>
          <ul v-else class="list-group list-group-flush">
            <li v-for="l in data.today" :key="l.lesson_id"
              class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-bold">{{ l.title }}</div>
                <small class="text-muted">
                  {{ l.course }} · {{ l.goal }} · {{ goalReason(l) }}
                </small>
              </div>
              <div class="forBadge">
                <div class="mb-1">
                  <span class="badge" :class="GOAL_STATUS[l.status].cls">
                    <span class="icone_back" >
                      <Icon name="mdi:alarm-snooze" />{{ GOAL_STATUS[l.status].label }}
                    </span>
                  </span>
                </div>
                <div class="mb-1">
                  <span class="badge commencer" @click="commencer(l.course_id, l.lesson_id)">
                    <span class="icone_back" >
                      <Icon name="solar:alarm-play-bold" /> 
                    Faire le quizz
                    </span>
                  </span>
                </div>
                <div class="mb-1">
                  <span @click="router.push(`/home/lessons/${l.lesson_id}/quiz`)" class="badge voir">
                     <span class="icone_back" >
                      <Icon name="mdi:account-school-outline" /> 
                    Voir lessons
                    </span>
                </span>
                </div>
              </div>
            </li>
          </ul>
        </div>

        <!-- Cartes des objectifs -->
        <div class="row g-3 mb-4">
          <div v-for="g in data.goals" :key="g.id" class="col-12 col-md-6 col-xl-4">
            <div class="card p-3 h-100" role="button" :class="g.id === selectedId ? 'border-2 border-primary' : ''"
              @click="selectedId = g.id">
              <h6 class="d-flex justify-content-between">
                {{ g.title }}
                <span v-if="g.mastered" class="badge bg-success">Maîtrisé</span>
              </h6>

              <div class="progress mb-2">
                <div class="progress-bar" :style="{ width: g.progress + '%' }">{{ g.progress }}%</div>
              </div>

              <ClientOnly>
                <GoalSparkline :points="g.evolution" />
              </ClientOnly>

              <div class="d-flex justify-content-between small mt-2">
                <span :class="trendClass(g.trend)">{{ trendText(g.trend) }} sur 7 j</span>
                <span :class="g.days_left !== null && g.days_left < 0 ? 'text-danger' : 'text-muted'">
                  {{ goalDeadlineText(g.days_left) }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Détail de l'objectif sélectionné -->
        <div v-if="selected" class="card p-3">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">{{ selected.title }}</h4>
            <NuxtLink :to="`/goals/${selected.id}`" class="btn btn-outline-secondary btn-sm">
              Détail complet
            </NuxtLink>
          </div>

          <div class="row g-4">
            <div class="col-12 col-lg-6">
              <h6>Évolution sur 8 semaines</h6>
              <ClientOnly>
                <GoalEvolutionChart :points="selected.evolution" />
              </ClientOnly>
            </div>

            <div class="col-12 col-lg-6">
              <h6>Effort contre besoin</h6>
              <ClientOnly>
                <EffortChart :items="selected.courses" />
              </ClientOnly>
            </div>
          </div>

          <h6 class="mt-4">Points faibles</h6>
          <p v-if="!selected.weak.length" class="text-muted mb-0">Rien à signaler.</p>
          <ul v-else class="list-group list-group-flush">
            <li v-for="l in selected.weak" :key="l.lesson_id"
              class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-bold">{{ l.title }}</div>
                <small class="text-muted">{{ l.course }} · {{ goalReason(l) }}</small>
              </div>
              <div class="forBadge">
                <div class="mb-1">
                  <span class="badge" :class="GOAL_STATUS[l.status].cls">
                    <span class="icone_back" >
                      <Icon name="mdi:alarm-snooze" />{{ GOAL_STATUS[l.status].label }}
                    </span>
                  </span>
                </div>
                <div class="mb-1">
                  <span class="badge commencer" @click="commencer(l.course_id, l.lesson_id)">
                    <span class="icone_back" >
                      <Icon name="solar:alarm-play-bold" /> 
                    Faire le quizz
                    </span>
                  </span>
                </div>
                <div class="mb-1">
                  <span @click="router.push(`/home/lessons/${l.lesson_id}/quiz`)" class="badge voir">
                     <span class="icone_back" >
                      <Icon name="mdi:account-school-outline" /> 
                    Voir lessons
                    </span>
                </span>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </template>
    </template>
  </div>
</template>
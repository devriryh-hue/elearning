<!-- app/pages/goals/[id].vue -->
<script setup lang="ts">
const route = useRoute()
const { get, remove } = useGoals()
definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const { data, pending, error } = await useAsyncData(
  `goal-${route.params.id}`,
  () => get(route.params.id as string),
  { server: false },
)

const STATUS: Record<string, { label: string; cls: string }> = {
  not_started: { label: 'Pas commencé', cls: 'bg-secondary' },
  learning:    { label: 'En apprentissage', cls: 'bg-info' },
  to_review:   { label: 'À revoir', cls: 'bg-warning text-dark' },
  stale:       { label: 'À rafraîchir', cls: 'bg-dark' },
  mastered:    { label: 'Maîtrisé', cls: 'bg-success' },
}

const reason = (l: any) => {
  switch (l.status) {
    case 'not_started': return 'Jamais fait'
    case 'learning':    return `${l.attempts} / ${l.required} tentatives`
    case 'to_review':   return `Moyenne ${l.recent_avg}% pour une cible de ${l.target}%`
    case 'stale':       return 'Non revu depuis plus de 30 jours'
    default:            return ''
  }
}

const deadlineText = computed(() => {
  const d = data.value?.days_left
  if (d === null || d === undefined) return 'Sans délai'
  return d < 0 ? `En retard de ${Math.abs(d)} j` : `${d} j restants`
})

const onDelete = async () => {
  if (!confirm('Supprimer cet objectif ?')) return
  await remove(data.value.goal.id)
  await navigateTo('/goals')
}
</script>

<template>
  <div class="container py-4">
    <NuxtLink to="/goals" class="small">&larr; Mes objectifs</NuxtLink>

    <p v-if="pending" class="mt-3">Chargement…</p>
    <p v-else-if="error" class="text-danger mt-3">Objectif introuvable.</p>

    <template v-else-if="data">
      <div class="d-flex justify-content-between align-items-start mt-2 mb-3">
        <div>
          <h2 class="mb-1">{{ data.goal.title }}</h2>
          <p v-if="data.goal.description" class="text-muted mb-0">{{ data.goal.description }}</p>
        </div>
        <button class="btn btn-outline-danger btn-sm" @click="onDelete">Supprimer</button>
      </div>

      <!-- Progression globale -->
      <div class="card p-3 mb-4">
        <div class="d-flex justify-content-between mb-1">
          <strong>Progression de l'objectif</strong>
          <span :class="data.days_left !== null && data.days_left < 0 ? 'text-danger' : 'text-muted'">
            {{ deadlineText }}
          </span>
        </div>
        <div class="progress" style="height: 22px">
          <div class="progress-bar" :style="{ width: data.progress + '%' }">{{ data.progress }}%</div>
        </div>
        <span v-if="data.mastered" class="badge bg-success mt-2 align-self-start">Objectif maîtrisé</span>
      </div>

      <!-- Points faibles -->
      <div class="card p-3 mb-4">
        <h5>À travailler en priorité</h5>
        <p v-if="!data.weak.length" class="text-muted mb-0">Rien à signaler, bravo !</p>
        <ul v-else class="list-group list-group-flush">
          <li v-for="l in data.weak" :key="l.lesson_id"
            class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-bold">{{ l.title }}</div>
              <small class="text-muted">{{ l.course }} · {{ reason(l) }}</small>
            </div>
            <span class="badge" :class="STATUS[l.status].cls">{{ STATUS[l.status].label }}</span>
          </li>
        </ul>
      </div>

      <!-- Effort contre besoin -->
      <div class="card p-3 mb-4">
        <h5>Effort contre besoin</h5>
        <p class="text-muted small">
          Tentatives faites par cours, comparées à ce que l'objectif demande.
          Une barre orange très longue signale du sur-entraînement ; très courte, un cours négligé.
        </p>
        <ClientOnly>
          <EffortChart :items="data.courses" />
        </ClientOnly>
      </div>

      <!-- Détail par cours -->
      <h5>Détail par cours</h5>
      <div v-for="c in data.courses" :key="c.course_id" class="card p-3 mb-3">
        <div class="d-flex justify-content-between mb-1">
          <strong>{{ c.title }}</strong>
          <small class="text-muted">importance {{ c.weight }}</small>
        </div>
        <div class="progress mb-2">
          <div class="progress-bar" :style="{ width: c.progress + '%' }">{{ c.progress }}%</div>
        </div>

        <details>
          <summary>{{ c.lessons.length }} leçon(s)</summary>
          <ul class="list-unstyled mt-2 mb-0">
            <li v-for="l in c.lessons" :key="l.lesson_id"
              class="d-flex justify-content-between align-items-center py-1">
              <span>{{ l.title }}
                <small class="text-muted">({{ l.attempts }}/{{ l.required }} · {{ l.recent_avg }}%)</small>
              </span>
              <span class="badge" :class="STATUS[l.status].cls">{{ STATUS[l.status].label }}</span>
            </li>
          </ul>
        </details>
      </div>
    </template>
  </div>
</template>
<!-- app/pages/goals/index.vue -->
<script setup lang="ts">
const { list } = useGoals()
const { data: goals, pending } = await useAsyncData('goals', () => list(), { server: false })
definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const deadlineText = (d: number | null) => {
  if (d === null) return 'Sans délai'
  if (d < 0) return `En retard de ${Math.abs(d)} j`
  return `${d} j restants`
}
</script>

<template>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Mes objectifs</h2>
      <NuxtLink to="/goals/new" class="btn btn-primary">Nouvel objectif</NuxtLink>
    </div>

    <p v-if="pending">Chargement…</p>
    <p v-else-if="!goals?.length" class="text-muted">Aucun objectif pour le moment.</p>

    <div class="row g-3">
      <div v-for="g in goals" :key="g.id" class="col-12 col-md-6">
        <NuxtLink :to="`/goals/${g.id}`" class="text-decoration-none text-reset">
          <div class="card p-3 h-100">
            <h5 class="d-flex justify-content-between">
              {{ g.title }}
              <span v-if="g.mastered" class="badge bg-success">Maîtrisé</span>
            </h5>
            <div class="progress mb-2">
              <div class="progress-bar" :style="{ width: g.progress + '%' }">{{ g.progress }}%</div>
            </div>
            <small :class="g.days_left !== null && g.days_left < 0 ? 'text-danger' : 'text-muted'">
              {{ deadlineText(g.days_left) }}
            </small>
          </div>
        </NuxtLink>
      </div>
    </div>
  </div>
</template>
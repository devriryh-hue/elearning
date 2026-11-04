<!-- pages/dashboard.vue -->
<script setup lang="ts">
const { $api } = useNuxtApp()
definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
// server: false évite les soucis de cookies/session pendant le rendu serveur
const { data, pending, error } = await useAsyncData(
  'dashboard',
  () => $api('/dashboard'),
  { server: false },
)

const formatDate = (d: string) => new Date(d).toLocaleDateString('fr-FR')
</script>

<template>
  <div class="container py-4">
    <h2 class="mb-4">Mon tableau de bord</h2>

    <p v-if="pending">Chargement…</p>
    <p v-else-if="error" class="text-danger">Impossible de charger le tableau de bord.</p>

    <template v-else-if="data">
      <!-- Résumé -->
      <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
          <div class="card text-center p-3">
            <div class="fs-3 fw-bold">{{ data.summary.courses_total }}</div>
            <small>Cours suivis</small>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card text-center p-3">
            <div class="fs-3 fw-bold">{{ data.summary.courses_completed }}</div>
            <small>Cours terminés</small>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card text-center p-3">
            <div class="fs-3 fw-bold">{{ data.summary.attempts_total }}</div>
            <small>Quiz faits</small>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card text-center p-3">
            <div class="fs-3 fw-bold">{{ data.summary.average_percent }}%</div>
            <small>Score moyen</small>
          </div>
        </div>
      </div>

      <!-- Un bloc par cours -->
      <div class="row g-3">
        <div v-for="c in data.courses" :key="c.course_id" class="col-12 col-lg-6">
          <div class="card p-3 h-100">
            <h5 class="d-flex justify-content-between">
              {{ c.title }}
              <span v-if="c.completed" class="badge bg-success">Terminé</span>
            </h5>

            <div class="progress mb-2" role="progressbar" :aria-valuenow="c.progress">
              <div class="progress-bar" :style="{ width: c.progress + '%' }">
                {{ c.progress }}%
              </div>
            </div>

            <ul class="list-inline small mb-3">
              <li class="list-inline-item">{{ c.attempts_count }} quiz fait(s)</li>
              <li class="list-inline-item">Meilleur : {{ c.best_percent }}%</li>
              <li class="list-inline-item">Moyenne : {{ c.average_percent }}%</li>
              <li v-if="c.last_percent !== null" class="list-inline-item">
                Dernier : {{ c.last_percent }}%
              </li>
            </ul>

            <ClientOnly>
              <ScoreChart v-if="c.history.length" :history="c.history" />
              <p v-else class="text-muted">Aucun quiz terminé pour ce cours.</p>
            </ClientOnly>

            <details v-if="c.history.length" class="mt-3">
              <summary>Détail des tentatives</summary>
              <ul class="list-unstyled mt-2 mb-0">
                <li v-for="h in c.history" :key="h.id">
                  {{ formatDate(h.date) }} · {{ h.lesson }} :
                  <strong>{{ h.score }} / {{ h.max_score }}</strong> ({{ h.percent }}%)
                </li>
              </ul>
            </details>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>
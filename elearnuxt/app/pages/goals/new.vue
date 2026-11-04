<!-- app/pages/goals/new.vue -->
<script setup lang="ts">
const { $api } = useNuxtApp()
const { create } = useGoals()
definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const form = reactive({ title: '', description: '', deadline: '' })
const selected = ref<Record<number, { required_attempts: number; target_percent: number; weight: number }>>({})
const errors = ref<Record<string, string[]>>({})
const submitting = ref(false)

// Adapte si ton endpoint /courses est paginé
const { data: courses } = await useAsyncData('goal-courses', async () => {
  const res: any = await $api('/allCourses')
  return (Array.isArray(res) ? res : res.data) as { id: number; title: string }[]
}, { server: false })

const toggle = (id: number) => {
  if (selected.value[id]) delete selected.value[id]
  else selected.value[id] = { required_attempts: 3, target_percent: 80, weight: 1 }
}

const submit = async () => {
  errors.value = {}

  if (!form.title.trim() || Object.keys(selected.value).length === 0) {
    errors.value = { title: ['Un titre et au moins un cours sont requis.'] }
    return
  }

  submitting.value = true
  try {
    const res: any = await create({
      title: form.title,
      description: form.description || null,
      deadline: form.deadline || null,
      courses: Object.entries(selected.value).map(([id, v]) => ({ course_id: Number(id), ...v })),
    })
    await navigateTo(`/goals/${res.id}`)
  } catch (e: any) {
    errors.value = e?.data?.errors ?? { form: ['Une erreur est survenue.'] }
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <div class="container py-4" style="max-width: 760px">
    <h2 class="mb-4">Nouvel objectif</h2>

    <div v-if="Object.keys(errors).length" class="alert alert-danger">
      <ul class="mb-0">
        <li v-for="(msgs, field) in errors" :key="field">{{ msgs[0] }}</li>
      </ul>
    </div>

    <div class="mb-3">
      <label class="form-label">Titre</label>
      <input v-model="form.title" class="form-control" placeholder="Ex : Poste développeur AZMG" />
    </div>

    <div class="mb-3">
      <label class="form-label">Description / notes</label>
      <textarea v-model="form.description" rows="2" class="form-control"
        placeholder="Ex : Laravel, Nuxt, Nginx, intégration IA…" />
    </div>

    <div class="mb-4">
      <label class="form-label">Date limite (optionnelle)</label>
      <input v-model="form.deadline" type="date" class="form-control" />
    </div>

    <h5>Cours liés à cet objectif</h5>
    <p class="text-muted small">
      Pour chaque cours : combien de tentatives par leçon, quel score à atteindre, et son importance.
    </p>

    <p v-if="!courses">Chargement des cours…</p>

    <div v-for="c in courses" :key="c.id" class="card mb-2 p-2">
      <div class="form-check">
        <input :id="`c${c.id}`" class="form-check-input" type="checkbox"
          :checked="!!selected[c.id]" @change="toggle(c.id)" />
        <label class="form-check-label fw-bold" :for="`c${c.id}`">{{ c.title }}</label>
      </div>

      <div v-if="selected[c.id]" class="row g-2 mt-1">
        <div class="col-4">
          <label class="form-label small mb-0">Tentatives / leçon</label>
          <input v-model.number="selected[c.id].required_attempts" type="number" min="1" max="20" class="form-control form-control-sm" />
        </div>
        <div class="col-4">
          <label class="form-label small mb-0">Score cible (%)</label>
          <input v-model.number="selected[c.id].target_percent" type="number" min="1" max="100" class="form-control form-control-sm" />
        </div>
        <div class="col-4">
          <label class="form-label small mb-0">Importance (1-10)</label>
          <input v-model.number="selected[c.id].weight" type="number" min="1" max="10" class="form-control form-control-sm" />
        </div>
      </div>
    </div>

    <button class="btn btn-primary mt-3" :disabled="submitting" @click="submit">
      {{ submitting ? 'Enregistrement…' : 'Créer l\'objectif' }}
    </button>
  </div>
</template>
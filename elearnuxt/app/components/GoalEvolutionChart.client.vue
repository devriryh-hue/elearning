<!-- app/components/GoalEvolutionChart.client.vue -->
<script setup lang="ts">
import { Line } from 'vue-chartjs'
import {
  Chart as ChartJS, Title, Tooltip, Legend,
  LineElement, PointElement, LinearScale, CategoryScale, Filler,
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, LinearScale, CategoryScale, Filler)

const props = defineProps<{ points: { date: string; progress: number }[] }>()

const data = computed(() => ({
  labels: props.points.map((p) => new Date(p.date).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' })),
  datasets: [{
    label: 'Progression (%)',
    data: props.points.map((p) => p.progress),
    borderColor: '#ff5b3a',
    backgroundColor: 'rgba(255, 91, 58, 0.15)',
    fill: true,
    tension: 0.3,
  }],
}))

const options = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: { legend: { display: false } },
  scales: { y: { min: 0, max: 100, ticks: { callback: (v: any) => `${v}%` } } },
}
</script>

<template>
  <div v-if="points.length" style="height: 240px">
    <Line :data="data" :options="options" />
  </div>
</template>
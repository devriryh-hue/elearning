<!-- app/components/GoalSparkline.client.vue -->
<script setup lang="ts">
import { Line } from 'vue-chartjs'
import {
  Chart as ChartJS, LineElement, PointElement,
  LinearScale, CategoryScale, Filler,
} from 'chart.js'

ChartJS.register(LineElement, PointElement, LinearScale, CategoryScale, Filler)

const props = defineProps<{ points: { date: string; progress: number }[] }>()

const data = computed(() => ({
  labels: props.points.map((p) => p.date),
  datasets: [{
    data: props.points.map((p) => p.progress),
    borderColor: '#ff5b3a',
    backgroundColor: 'rgba(255, 91, 58, 0.15)',
    fill: true,
    tension: 0.3,
    pointRadius: 0,
    borderWidth: 2,
  }],
}))

const options = {
  responsive: true,
  maintainAspectRatio: false,
  animation: false,
  plugins: { legend: { display: false }, tooltip: { enabled: false } },
  scales: { x: { display: false }, y: { display: false, min: 0, max: 100 } },
}
</script>

<template>
  <div style="height: 48px">
    <Line :data="data" :options="options" />
  </div>
</template>
<!-- components/ScoreChart.vue -->
<script setup lang="ts">
import { Line } from 'vue-chartjs'
import {
  Chart as ChartJS, Title, Tooltip, Legend,
  LineElement, PointElement, LinearScale, CategoryScale,
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, LinearScale, CategoryScale)

interface Attempt {
  lesson: string | null
  score: number
  max_score: number
  percent: number
  date: string
}

const props = defineProps<{ history: Attempt[] }>()

const data = computed(() => ({
  labels: props.history.map((h) => new Date(h.date).toLocaleDateString('fr-FR')),
  datasets: [
    {
      label: 'Score (%)',
      data: props.history.map((h) => h.percent),
      borderColor: '#ff5b3a',
      backgroundColor: '#ff5b3a',
      tension: 0.3,
    },
  ],
}))

const options = computed(() => ({
  responsive: true,
  maintainAspectRatio: false,
  scales: { y: { min: 0, max: 100, ticks: { callback: (v: number) => `${v}%` } } },
  plugins: {
    tooltip: {
      callbacks: {
        label: (ctx: any) => {
          const h = props.history[ctx.dataIndex]
          return `${h.score} / ${h.max_score} (${h.percent}%)`
        },
        afterLabel: (ctx: any) => props.history[ctx.dataIndex].lesson ?? '',
      },
    },
  },
}))
</script>

<template>
  <div style="height: 220px">
    <Line :data="data" :options="options" />
  </div>
</template>
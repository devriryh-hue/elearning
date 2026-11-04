<!-- app/components/EffortChart.client.vue -->
<script setup lang="ts">
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS, Title, Tooltip, Legend,
  BarElement, CategoryScale, LinearScale,
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

interface Item { title: string; attempts: number; required: number }
const props = defineProps<{ items: Item[] }>()

const data = computed(() => ({
  labels: props.items.map((i) => i.title),
  datasets: [
    { label: 'Tentatives faites', data: props.items.map((i) => i.attempts), backgroundColor: '#ff5b3a' },
    { label: 'Tentatives requises', data: props.items.map((i) => i.required), backgroundColor: '#9aa5b1' },
  ],
}))

const options = computed(() => ({
  indexAxis: 'y' as const,          // barres horizontales : les titres longs restent lisibles
  responsive: true,
  maintainAspectRatio: false,
  scales: { x: { beginAtZero: true, ticks: { precision: 0 } } },
}))

const height = computed(() => `${Math.max(180, props.items.length * 70)}px`)
</script>

<template>
  <div v-if="items.length" :style="{ height }">
    <Bar :data="data" :options="options" />
  </div>
</template>
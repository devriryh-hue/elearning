<script setup>
const props = defineProps({
  evaluation: {
    type: Object,
    default: () => ({
      progress: 0
    })
  }
})
const progress = ref(props.evaluation.progress)
</script>

<template>
  <div class="progress">
    <div
      class="progress-bar"
      :style="{ width: progress + '%' }"
    >
      {{ progress }}%
    </div>
  </div>
</template>

<style scoped>
.progress {
  width: 300px;
  height: 25px;
  background: var(--couleur-quaternaire);
  border-radius: 8px;
  overflow: hidden;
}

.progress-bar {
  height: 100%;
  background: var(--couleur-secondaire);
  color: var(--couleur-fond);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: width .3s ease;
}
</style>
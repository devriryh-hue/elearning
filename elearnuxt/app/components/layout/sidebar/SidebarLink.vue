<script setup lang="ts">
defineProps<{
  to: string
  icon?: string
}>()
</script>

<template>
  <NuxtLink
    :to="to"
    class="list-group-item list-group-item-action"
  >
    <Icon
      v-if="icon"
      :name="icon"
      class="me-2"
    />

    <slot />
  </NuxtLink>
</template>
<script setup lang="ts">
const props = defineProps<{
  title: string
  icon?: string
}>()

const open = ref(false)
</script>

<template>
  <div class="navclass" >

    <button
      class="navclass list-group-item list-group-item-action d-flex justify-content-between align-items-center"
      @click="open = !open"
    >
      <span class="hovernav" >

        <Icon
          v-if="icon"
          :name="icon"
          class="me-2"
        />

        {{ title }}

      </span>

      <Icon
        :name="open
          ? 'heroicons:chevron-up'
          : 'heroicons:chevron-down'"
      />

    </button>

    <Transition name="fade">

      <div
        v-show="open"
        class="ps-4"
      >
        <slot />
      </div>

    </Transition>

  </div>
</template>
<script setup lang="ts">
const props = defineProps({
  currentPage: {
    type: Number,
    required: true
  },
  lastPage: {
    type: Number,
    required: true
  }
})

const emit = defineEmits<{
  change: [page: number]
}>()

const goToPage = (page: number) => {
  if (
    page >= 1 &&
    page <= props.lastPage &&
    page !== props.currentPage
  ) {
    console.log('goToPage', page)
    emit('change', page)
  }
}
</script>

<template>
  <nav v-if="lastPage > 1">
    <ul class="pagination">

      <!-- Précédent -->
      <li
        class="page-item"
        :class="{ disabled: currentPage === 1 }"
      >
        <button
          class="page-link"
          @click="goToPage(currentPage - 1)"
        >
          Précédent
        </button>
      </li>

      <!-- Numéros -->
      <li
        v-for="page in lastPage"
        :key="page"
        class="page-item"
        :class="{ active: page === currentPage }"
      >
        <button
          class="page-link"
          @click="goToPage(page)"
        >
          {{ page }}
        </button>
      </li>

      <!-- Suivant -->
      <li
        class="page-item"
        :class="{ disabled: currentPage === lastPage }"
      >
        <button
          class="page-link"
          @click="goToPage(currentPage + 1)"
        >
          Suivant
        </button>
      </li>

    </ul>
  </nav>
</template>
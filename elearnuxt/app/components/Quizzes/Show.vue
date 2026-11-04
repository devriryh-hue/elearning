<script setup lang="ts">
import { useRouter } from 'vue-router'
const router = useRouter()
const props = defineProps({
  quiz: {
    type: Array,
    default: () => []
  }
})
</script>
<template>
    <div v-for="quizItem in quiz" :key="quizItem.id" class="quizzes">
    <div class="questionposition">
      <span class="boutonposition">
        {{ quizItem.position }}

      </span>
    </div>
    <hr class="hr">
    <div>
      <span class="champform">
        Question:
      </span>
      <!-- <div >{{ quizItem.question }}</div> -->
<TipRichEditor v-model="quizItem.question"/>
    </div>
    <hr class="hr">
    <div class="reponse ">
      <div class="haveIcon">
        <Icon name="tabler:circle-check" class="icon" style="background-color: var(--couleur-principale);" />
      </div>
      <div class="champform">
        Réponse:
      </div>
    </div>

    <div class="mb-3" v-for="option in quizItem.options" :key="option.id">
      <div v-if="option.correct" class="correct-option">

        <!-- <div >{{ option.text }}</div> -->
        <TipRichEditor v-model="option.text"/>
      </div>
    </div>
    <hr v-if="quizItem.note" class="hr">
    <div v-if="quizItem.note">
      <span class="champform">
        Note:
      </span>
      <div v-html="quizItem.note"></div>
    </div>
    <hr class="hr">
    <div class="mb-3">
      <span class="champform">
        Position:
      </span>
      {{ quizItem.position }},
      <span class="champform">
        Next Position:
      </span>
      <span v-if="quizItem.next_position !== null">
        {{ quizItem.next_position }}
      </span>
      <span v-else class="" style="background-color: red; color: white; padding: 2px 5px; border-radius: 3px;">
        Aucun
      </span>
    </div>
    <hr class="hr">
    <div class="mb-3">
      <span class="champform">
        Point:
      </span>
      {{ quizItem.points }}

    </div>
    <hr class="hr">
    <Mybutton @click="router.push(`/quizzes/${quizItem.id}/edit`)" class="boutonediter_back">
      <Icon name="tabler:edit" />
      Editer
    </Mybutton>
  </div>
</template>
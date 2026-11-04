<script setup lang="ts">
import { useRouter } from 'vue-router';

const router = useRouter();



</script>
<template>
  <section class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white" id="herosection">
    <div class="container mx-auto px-6 py-24">
      <div class="max-w-3xl">
        <h1 class="text-5xl font-bold mb-6">
          Développez vos compétences avec nos quiz interactifs
        </h1>
        <p class="text-xl mb-8 text-blue-100">
          Laravel, Nuxt, Vue.js, PHP, DevOps et bien plus encore.
          Testez vos connaissances et progressez à votre rythme.
        </p>
        <div class="flex gap-4">
          <PersonMybutton class="voir" @click="router.push('/home/cours')">
            Commencer maintenant
          </PersonMybutton>
        </div>
      </div>
    </div>
  </section>
</template>
<style>
#herosection {
  background: url('/images/hero.jpg');
  background-size: cover;
  height: 35vh;
  padding-top: 25px;
}
</style>

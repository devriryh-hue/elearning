<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Slotest from '@/components/Slotest.vue'
import { useTest } from '@/composables/testComposables.js'

const {test} = useTest()


onMounted(() => {
})
</script>

<template>

  <div >

    <h1>TEST</h1>
    <div>
      <Slotest>
      <template v-slot:toast="props">
        <h2>
            Test de props sur slot
        </h2>
        <div>
          {{ props.tust }}
        </div>
      </template>
      <template v-slot:fonct="{testfonctionslot}">
        <h2>
            Test de fonction sur slot
        </h2>
        <div>
          {{ testfonctionslot('tonga lty le izy a') }}
        </div>
      </template>
      </Slotest>
    </div>
    <div>
        <h2>
            Test de composable
        </h2>
        <div>
            {{ test }}
        </div>
    </div>
   

  </div>

</template>
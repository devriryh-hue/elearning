<!-- layouts/home.vue -->

<script setup lang="ts">
import Navbar from '~/components/layout/Navbar.vue'
import '~/assets/homespec.css'
</script>

<template>
  <div class="dashboard-layout">
    <Navbar />

    <div class="container-fluid ericbody">
      <div class="row">

        <div class="col-md-12 col-lg-12 ericcontent">
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>
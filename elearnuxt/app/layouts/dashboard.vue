<!-- layouts/dashboard.vue -->

<script setup lang="ts">
import Navbar from '~/components/layout/Navbar.vue'
//import Sidebar from '~/components/layout/sidebar/Sidebar.vue'
import Sidebar from '~/components/layout/sidebar/SidebarSenior.vue'
import "~/assets/backspec.css"
</script>

<template>
  <div class="dashboard-layout">
    
    <Navbar />
    <div class="container-fluid ericbody">
      <div class="row">
        <div class="col-md-3 col-lg-2">
          <Sidebar />
        </div>

        <div class="col-md-9 col-lg-10 ericcontent">
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>
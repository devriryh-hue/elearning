<script setup lang="ts">
import { sidebar } from '~/data/sidebar'
import SidebarLink from '~/components/layout/sidebar/SidebarLink.vue'
import SidebarGroup from '~/components/layout/sidebar/SidebarGroup.vue'
import { ref } from 'vue'
const isOpen = ref(false)
</script>
<template>
  <button class="menu-btn" @click="isOpen = !isOpen">
    ☰
  </button>
  <aside class="sidebar" :class="{ open: isOpen }">
    <div class="list-group">
      <template v-for="item in sidebar" :key="item.title">
        <SidebarLink class="navclass" v-if="item.to" :to="item.to" :icon="item.icon">
          {{ item.title }}
        </SidebarLink>
        <SidebarGroup class="navclass" v-else :title="item.title" :icon="item.icon">
          <SidebarLink class="navclass" v-for="child in item.children" :icon="child.icon" :key="child.to" :to="child.to">
            {{ child.title }}
          </SidebarLink>
        </SidebarGroup>
      </template>
    </div>
  </aside>
</template>
<style>
.navclass{
        background-color: var(--couleur-secondaire);
    color: white;
}
.navclass:hover{
        background-color: var(--couleur-tertiaire) !important;
    color: white !important;
}
.hovernav:hover{
    font-size: 15px;
    /*font-weight: bold;*/
}
</style>
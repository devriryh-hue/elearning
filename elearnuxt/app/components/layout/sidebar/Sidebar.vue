<script setup lang="ts">
import SidebarLink from '~/components/layout/sidebar/SidebarLink.vue'
import SidebarGroup from '~/components/layout/sidebar/SidebarGroup.vue'
</script>
<template>

<div class="list-group">

    <SidebarLink
        to="/dashboard"
        icon="heroicons:home"
        class="navclass"
    >
        Dashboard
    </SidebarLink>

    <SidebarGroup
        title="Cours"
        icon="heroicons:academic-cap"
        class="navclass"
    >

        <SidebarLink to="/courses"class="navclass">
            Liste
        </SidebarLink>

        <SidebarLink to="/courses/create" class="navclass">
            Ajouter
        </SidebarLink>

        <SidebarLink to="/categories" class="navclass">
            Catégories
        </SidebarLink>

    </SidebarGroup>

    <SidebarGroup
        title="Utilisateurs"
        icon="heroicons:users"
    >

        <SidebarLink to="/students" class="navclass">
            Étudiants
        </SidebarLink>

        <SidebarLink to="/teachers" class="navclass">
            Professeurs
        </SidebarLink>

    </SidebarGroup>

</div>

</template>

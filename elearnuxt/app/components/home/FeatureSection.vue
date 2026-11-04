<script setup lang="ts">
import "~/assets/feature-section.css"
</script>
<template>
    <div class="sectionbody">
        <div>
            <header class="header">
                <p class="header_subtitle">Elearning quizz, apprendre et s'auto-évaluer</p>
                <h1 class="header_title">Powered by Dev Riryh</h1>
                <p class="header_description">Fullstack apprentissage
                    Maitrisez tout les nécessaires, pour faire un site</p>
            </header>

            <main class="card_grid">
                <section class="card cyan">
                    <h2 class="card_title">Nuxt</h2>
                    <p class="card_content">Maitrisez le Front-end, faire de beau site réactive avec Nuxt

                    </p>
                    
                        <NuxtImg src="/images/badge-1.svg" alt="Supervisor" class="card_img"/>
                </section>

                <section class="card red">
                    <h2 class="card_title">Laravel</h2>
                    <p class="card_content">Maitrisez le Back-end, allant de débutant à senior</p>
                    <NuxtImg src="/images/badge-2.svg" alt="Team Builder" class="card_img"/>
                </section>

                <section class="card blue">
                    <h2 class="card_title">Pinia</h2>
                    <p class="card_content">L'un des apprentissages clés de pinia</p>
                    <NuxtImg src="/images/badge-3.svg" alt="Karma" class="card_img"/>
                </section>

                <section class="card orange">
                    <h2 class="card_title">Vuejs</h2>
                    <p class="card_content">Maitrisez vuejs, pour façonner de meilleur site réactive</p>
                    <NuxtImg src="/images/badge-4.svg" alt="Calculator" class="card_img"/>
                </section>
            </main>
        </div>

    </div>
</template>

<script setup>
const props = defineProps({
    course: {
        type: Object,
        required: true
    }
})

const router = useRouter()

const commencera = (courseId, lessonId) => {
    navigateTo(`/home/Cours/${courseId}/lessons/${lessonId}/voir`)
}
</script>

<template>
    <div class="leslessons">
        <div class="comptlessons" v-for="lesson in course.lessons" :key="lesson.id">
            <h3 class="h3_title">
                {{ lesson.title }}
            </h3>
            <div class="divcours">
                <PersonMybutton @click="commencera(course.id, lesson.id)" class="voir">
                    <Icon name="solar:alarm-play-bold" /> 
                    Commencer
                </PersonMybutton>
                <PersonMybutton @click="router.push(`/home/lessons/${lesson.id}/quiz`)" class="voir">
                    <Icon name="mdi:account-school-outline" />
                    Voir lessons
                </PersonMybutton>
            </div>
        </div>
    </div>
</template>
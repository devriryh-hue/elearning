<script setup lang="ts">
import { useCourses } from '~/composables/useCourses'
import { useRoute, useRouter } from 'vue-router'

definePageMeta({
  layout: 'dashboard',
  middleware: ['auth']
})
const { createJson } = useCourses()
const router = useRouter()
/*const response = ref({
    success: false,
    message: ''
})*/
const save = async(data:any)=>{
     const response=await createJson(data)
    if(response.success){
        router.push('/courses')
    }
}
</script>
<template>
    <UploadJson @submit="save" />
</template>
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useGetAll } from '~/composables/useGetAll'

const props = defineProps({
  lesson: {
    type: Object,
    default: () => ({
      title: '',
      video_url: '',
      course_id: null
    })
  }
})
</script>
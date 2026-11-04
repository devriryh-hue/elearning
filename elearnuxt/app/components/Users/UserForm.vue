<!-- CourseForm.vue -->
<script setup lang="ts">
const props = defineProps({
  user: {
    type: Object,
    default: () => ({
      name: '',
      email: '',
      avatar_url: ''
    })
  }
})

const emit = defineEmits(['submit'])

const form = ref({
  name: props.user.name,
  email: props.user.email,
  avatar: null as File | null
})

const submit = () => {
  const data = new FormData()

  data.append('name', form.value.name)
  data.append('email', form.value.email)

  if (form.value.avatar) {
    data.append('avatar', form.value.avatar)
  }

  emit('submit', data)
}

const onFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement

  if (target.files?.length) {
    form.value.avatar = target.files[0]
  }
}
</script>

<template>
  <form @submit.prevent="submit">
    <div class="form-group mb-2">
      <input
        type="file"
        class="d-none"
        id="avatar-upload"
        @change="onFileChange"
      >
      <label
      for="avatar-upload"
      class="btn btn-outline-danger spec_img"
    >
    <img :src="props.user.avatar_url" v-if="props.user.avatar_url" />
      <Icon name="carbon:drop-photo" />
    </label>
    </div>
    <div class="form-group">
      <label
        for="email"
        class="form-label"  
      >
      Nom:
      </label>
    </div>
    <div class="form-group mb-2">
      <input v-model="form.name"
      class="form-control"
          placeholder="Nom"
      >
    </div>
    <div class="form-group">
      <label
        for="email"
        class="form-label"  
      >
      Email:
      </label>
    </div>
    <div class="form-group mb-2">
      <input
        v-model="form.email"
        type="email"
        class="form-control"
        placeholder="Email"
      >
    </div>
    
    <button type="submit"
      class="btn btn-primary"
    >
      Enregistrer
    </button>
  </form>
</template>
<style>
.spec_img {
  display: flex;
  align-items: center;
  justify-content: center;
}
.spec_img img {
  max-width: 50px;
  max-height: 50px;
  margin-right: 10px;
}
</style>
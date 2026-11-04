<script setup lang="ts">
import { watch } from 'vue'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
  content: props.modelValue,
  extensions: [StarterKit],

  onUpdate({ editor }) {
    emit('update:modelValue', editor.getHTML())
  }
})

watch(
  () => props.modelValue,
  (value) => {
    if (!editor.value) return

    const current = editor.value.getHTML()

    if (current !== value) {
      editor.value.commands.setContent(value, false)
    }
  }
)
</script>

<template>
  <EditorContent :editor="editor" />
</template>
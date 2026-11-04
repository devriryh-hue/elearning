<script setup lang="ts">
import { watch } from 'vue'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'

const props = defineProps({
  modelValue: { type: String, default: '' }
})
const emit = defineEmits(['update:modelValue'])

const escapeHtml = (s: string) =>
  s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')

// Texte brut (avec tiques) -> HTML sûr pour Tiptap.
// Si c'est déjà du HTML produit par Tiptap, on le laisse tel quel.
const toEditorHtml = (text: string) => {
  if (!text) return ''
  if (/^\s*<(p|ul|ol|pre|h[1-6]|blockquote)\b/i.test(text)) return text

  const html = escapeHtml(text)
    .replace(/`([^`]+)`/g, '<code>$1</code>')
    .replace(/\n/g, '<br>')
  return `<p>${html}</p>`
}

const editor = useEditor({
  content: toEditorHtml(props.modelValue),
  extensions: [StarterKit],
  onUpdate({ editor }) {
    emit('update:modelValue', editor.getHTML())
  }
})

watch(
  () => props.modelValue,
  (value) => {
    //console.log("value", value)
    if (!editor.value) return
    const next = toEditorHtml(value)
    if (editor.value.getHTML() !== next) {
      editor.value.commands.setContent(next, false)
    }
  }
)
</script>

<template>
  <EditorContent :editor="editor" />
</template>
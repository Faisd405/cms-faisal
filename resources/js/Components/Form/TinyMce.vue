<script setup>
import Editor from '@tinymce/tinymce-vue'
import { onMounted, ref, watch } from 'vue'

const input = ref(null)

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    }
})

const emit = defineEmits(['update:modelValue'])

watch(
    () => input.value,
    (value) => {
        emit('update:modelValue', value)
    }
)

onMounted(() => {
    input.value = props.modelValue
})

const linkTinyMce = '/vendor/tinymce/tinymce.min.js'

const editorConfig = {
    selector: '#about',
    plugins:
        'code paste autoresize autosave link image image media wordcount lists table preview',
    menubar: false,
    branding: false,
    toolbar:
        'undo redo | fontsizeselect formatselect | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent blockquote | link image media'
}
</script>

<template>
    <Editor
        v-model="input"
        :init="editorConfig"
        :tinymce-script-src="linkTinyMce"
    />
</template>

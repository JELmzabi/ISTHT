<script setup>
import { computed } from 'vue'

defineProps({
  data: {
    type: [Object, String, Array],
    required: true
  }
})

function formatJson(input) {
  try {
    return typeof input === 'string'
      ? JSON.stringify(JSON.parse(input), null, 2)
      : JSON.stringify(input, null, 2)
  } catch (e) {
    return 'Invalid JSON'
  }
}

</script>

<template>
  <div dir="ltr" class="bg-gray-100 text-slate-800 text-sm p-4 rounded overflow-auto">
    <pre>{{ formatJson(data) }}</pre>
  </div>
</template>

<style scoped>
pre {
  white-space: pre-wrap;
  word-break: break-word;
  font-family: ui-monospace, monospace;
}
</style>
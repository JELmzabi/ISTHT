<template>
  <Modal :show="show" @close="close">
    <!-- Modal Container -->
  <div class="rounded-2xl w-full p-6 relative">
    <!-- Close button (optional) -->
    <button
      @click="close"
      class="absolute top-3 right-3 text-gray-400 hover:text-gray-600"
      aria-label="Close"
    >
      ✕
    </button>

    <!-- Header -->
    <div class="mb-4">
      <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
    </div>

    <!-- Body -->
    <div class="mb-6">
      <p class="text-sm text-gray-500">{{ message }}</p>
    </div>

    <!-- Footer -->
    <div class="flex justify-end space-x-3">
      <button
        @click="close"
        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition"
      >
        Annuler
      </button>
      <button
        @click="confirm"
        :disabled="processing"
        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 transition"
      >
        Confirmer
      </button>
    </div>
  </div>
  </Modal>
</template>

<script setup>
import { ref } from 'vue'
import Modal from './Modal.vue'

const props = defineProps({
  show: Boolean,
  title: String,
  message: String,
  onConfirm: Function,
})

const emit = defineEmits(['close'])

const processing = ref(false)

function close() {
  emit('close')
}

function confirm() {
  close();
  props.onConfirm();
}
</script>

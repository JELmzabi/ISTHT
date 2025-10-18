<template>
  <Modal :show="show" @close="close">
    <!-- Modal Container -->
    <div class="rounded-2xl w-full p-6 relative bg-white">
      <!-- Close button -->
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
          :class="['px-4 py-2 rounded-lg transition', typeClasses]"
        >
          Confirmer
        </button>
      </div>
    </div>
  </Modal>
</template>

<script setup>
import { ref, computed } from 'vue'
import Modal from './Modal.vue'

const props = defineProps({
  show: Boolean,
  title: String,
  message: String,
  type: {
    type: String,
    default: 'danger', // default style
    validator: value => ['info', 'warning', 'danger'].includes(value),
  },
  onConfirm: Function,
})

const emit = defineEmits(['close'])
const processing = ref(false)

function close() {
  emit('close')
}

function confirm() {
  close()
  props.onConfirm?.()
}

// Dynamic button classes based on type
const typeClasses = computed(() => {
  switch (props.type) {
    case 'info':
      return 'bg-blue-600 text-white hover:bg-blue-700'
    case 'warning':
      return 'bg-yellow-500 text-white hover:bg-yellow-600'
    case 'danger':
    default:
      return 'bg-red-600 text-white hover:bg-red-700'
  }
})
</script>

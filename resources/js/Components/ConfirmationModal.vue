<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
  >
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 relative">
      <!-- Close button -->
      <button
        @click="close"
        class="absolute top-3 right-3 text-gray-400 hover:text-gray-600"
        aria-label="Close"
      >
        ✕
      </button>

      <!-- Header -->
      <div class="flex items-center gap-3 mb-4">
        <component
          :is="iconComponent"
          class="h-6 w-6"
          :class="iconColor"
        />
        <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
      </div>

      <!-- Message -->
      <p class="text-gray-600 mb-6" v-html="message"></p>

      <!-- Footer -->
      <div class="flex justify-end space-x-3">
        <button
          @click="close"
          class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
        >
          Annuler
        </button>
        <button
          @click="confirm"
          :disabled="processing"
          :class="['px-4 py-2 rounded-lg text-white transition-colors', confirmClasses]"
        >
          {{ confirmLabel }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ExclamationCircleIcon, ExclamationTriangleIcon, InformationCircleIcon } from '@heroicons/vue/24/outline'
import { ref, computed } from 'vue'
// import { ExclamationTriangleIcon, InformationCircleIcon, AlertTriangleIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  show: Boolean,
  title: String,
  message: String,
  type: {
    type: String,
    default: 'danger',
    validator: value => ['info', 'warning', 'danger'].includes(value),
  },
  confirmLabel: {
    type: String,
    default: 'Confirmer',
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

// Button and icon styles based on type
const confirmClasses = computed(() => {
  switch (props.type) {
    case 'info':
      return 'bg-blue-600 hover:bg-blue-700'
    case 'warning':
      return 'bg-yellow-500 hover:bg-yellow-600'
    case 'danger':
    default:
      return 'bg-red-600 hover:bg-red-700'
  }
})

const iconColor = computed(() => {
  switch (props.type) {
    case 'info':
      return 'text-blue-500'
    case 'warning':
      return 'text-yellow-500'
    case 'danger':
    default:
      return 'text-red-500'
  }
})

const iconComponent = computed(() => {
  switch (props.type) {
    case 'info':
      return InformationCircleIcon
    case 'warning':
      return ExclamationCircleIcon
    case 'danger':
    default:
      return ExclamationTriangleIcon
  }
})
</script>

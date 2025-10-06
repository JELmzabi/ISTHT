<template>
  <li>
    <Link 
      :href="href"
      class="flex items-center px-3 py-2 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors group"
      :class="{
        'bg-blue-50 text-blue-600 border-r-2 border-blue-600': active,
        'text-gray-700 hover:bg-gray-100': !active
      }"
    >
      <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icon" />
      </svg>
      <span class="flex-1 truncate">{{ label }}</span>
      <span v-if="badge" class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded-full">
        {{ badge }}
      </span>
    </Link>
  </li>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  href: {
    type: String,
    required: true
  },
  active: {
    type: Boolean,
    default: false
  },
  icon: {
    type: String,
    required: true
  },
  label: {
    type: String,
    required: true
  },
  badge: {
    type: [String, Number],
    default: null
  }
})
</script>
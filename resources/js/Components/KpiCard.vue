<template>
  <div
    class="bg-white rounded-2xl shadow-sm p-4 flex items-center space-x-4 transition hover:shadow-md"
  >
    <div :class="['p-3 rounded-xl', colorClass]">
      <component :is="iconComponent" class="w-6 h-6 text-white" />
    </div>
    <div>
      <p class="text-sm text-gray-500">{{ title }}</p>
      <h3 class="text-xl font-semibold text-gray-800">{{ value }}</h3>
    </div>
  </div>
</template>

<script setup>
import * as HeroIcons from '@heroicons/vue/24/outline'
import { computed } from 'vue'

const props = defineProps({
  title: String,
  value: [String, Number],
  icon: String,
  color: String,
})

const iconComponent = computed(() => HeroIcons[props.icon])

const colorClass = computed(() => {
  const map = {
    blue: 'bg-blue-500',
    green: 'bg-green-500',
    orange: 'bg-orange-500',
    purple: 'bg-purple-500',
    red: 'bg-red-500',
  }
  return map[props.color] || 'bg-gray-500'
})
</script>

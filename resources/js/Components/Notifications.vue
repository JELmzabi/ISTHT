<script setup>
import { Head, InfiniteScroll, Link, router, usePage } from '@inertiajs/vue3';
import { onClickOutside } from '@vueuse/core';
import { computed, ref, useTemplateRef } from 'vue'

const page = usePage();

const scrollContainer = useTemplateRef('scrollContainer', null)
const unreadCount = computed(() => page.props.notifications_count)
const notifications = computed(() => page.props.notifications)


const open = ref(false)

function toggleDropdown() {
  open.value = !open.value
}

// Format timestamp
function formatDate(date) {
  return new Date(date).toLocaleString()
}

// Mark as read
function markAsRead(id) {
  router.post(route('notifications.read', id), {}, {
    preserveState: true,
  })
}

onClickOutside(scrollContainer, event => {
  open.value = false;
})
</script>

<template>
  <div class="relative">
    <!-- Bell Icon -->
    <button @click="toggleDropdown" class="relative focus:outline-none">
      <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z"/>
      </svg>

      <!-- Unread badge -->
      <span v-if="unreadCount > 0"
            class="absolute -top-2 right-3 inline-flex items-center justify-center px-1 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
        {{ unreadCount }}
      </span>
    </button>

    <!-- Dropdown -->
    <div v-show="open"
         class="absolute right-0 mt-2 w-[350px] bg-white shadow-lg rounded-lg z-50 h-96"
         ref="scrollContainer">
         <h2 class="p-4 text-lg font-semibold text-gray-800 border-b border-gray-400">Notifications</h2>
      <ul class="overflow-auto h-full">
        <InfiniteScroll data="notifications">
        <li v-for="notification in notifications.data" :key="notification.id"
            class="border-b border-slate-300 last:border-b-0">
          <Link :href="route('notifications.read', notification.id)" class="px-4 py-3 hover:bg-blue-100 bg-blue-50 flex justify-between items-center" :class="{ '!bg-white': notification.read_at }">
            <div>
              <p class="text-sm text-gray-800" v-html="notification.data.message"></p>
              <p class="text-xs text-gray-500">{{ formatDate(notification.created_at) }}</p>
            </div>
          </Link>
        </li>
        </InfiniteScroll>
      </ul>

      <div v-if="!notifications.data.length && !loading" class="text-center py-4 text-gray-400">
        No notifications
      </div>
    </div>
  </div>
</template>

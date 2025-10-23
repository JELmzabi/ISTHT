<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ModalLink } from '@inertiaui/modal-vue'
import { DocumentChartBarIcon, CubeIcon, ClipboardDocumentListIcon } from '@heroicons/vue/24/outline'

// List of available reports
const reports = [
  {
    name: 'Cardex',
    description: 'Consultez et téléchargez le rapport de mouvement de stock (Cardex).',
    href: route('cardex.create'),
    icon: DocumentChartBarIcon,
    bgColor: 'bg-blue-100 text-blue-600',
    hasModal: true
  },
 
]
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Les Rapports" />

    <div class="space-y-6 p-4">
      <!-- Page Header -->
      <div>
        <h2 class="text-2xl font-semibold text-gray-800">Les Rapports</h2>
        <p class="text-gray-500 text-sm mt-1">
          Téléchargez ou consultez les rapports détaillés de votre activité.
        </p>
      </div>

      <!-- Report Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <component :is="report.hasModal ? ModalLink : Link"
          v-for="report in reports"
          :href="report.href"
          :key="report.href"
          class="bg-white shadow-sm rounded-2xl p-5 border border-gray-100 hover:shadow-md transition"
        >
          <div class="flex items-center gap-4">
            <div :class="['p-3 rounded-xl', report.bgColor]">
              <component :is="report.icon" class="h-6 w-6" />
            </div>
            <h3 class="text-lg font-semibold text-gray-800">{{ report.name }}</h3>
          </div>

        </component>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

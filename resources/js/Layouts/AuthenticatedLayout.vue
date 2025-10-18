<template>
  <div class="min-h-screen bg-gray-100 flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
          <!-- Left side -->
          <div class="flex items-center space-x-3">
            <!-- Sidebar toggle (mobile) -->
            <button 
              @click="isSidebarOpen = !isSidebarOpen"
              class="md:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100"
            >
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>

            <h1 class="text-lg sm:text-xl font-semibold">Gestion Stock</h1>
          </div>

          <!-- Right side -->
          <div class="flex items-center space-x-4">
            <!-- Active page badge -->
            <span class="hidden sm:block text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
              {{ currentPageTitle }}
            </span>

            <!-- User info -->
            <div class="flex items-center space-x-2">
              <span class="text-sm text-gray-700">{{ $page.props.auth.user.name }}</span>
              <img
                class="h-8 w-8 rounded-full object-cover border"
                :src="$page.props.auth.user.profile_photo_url"
                :alt="$page.props.auth.user.name"
              />
            </div>
          </div>
        </div>
      </div>
    </nav>

    <div class="flex flex-1 relative">
      <!-- Sidebar -->
      <aside
        :class="[
          'bg-white shadow-sm w-64 fixed md:static inset-y-0 left-0 transform transition-transform duration-300 ease-in-out',
          isSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'
        ]"
        class="sidebar-scrollbar"
      >
        <div class="h-full overflow-y-auto py-4">
          <div class="px-4 py-2 border-b border-gray-200 mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Menu Principal</h2>
          </div>

          <!-- Dynamic Navigation -->
          <nav class="px-3">
            <template v-for="(section, sIndex) in sidebarSections" :key="sIndex">
              <div
                v-if="section.label"
                class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-6 mb-2"
              >
                {{ section.label }}
              </div>

              <ul class="space-y-1">
                <li v-for="item in section.items" :key="item.href">
                  <Link
                    :href="item.href"
                    class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors group"
                    :class="{
                      'bg-blue-50 text-blue-600 border-r-2 border-blue-600 font-semibold': $page.url.startsWith(item.match),
                      'text-gray-600 hover:text-gray-900': !$page.url.startsWith(item.match),
                    }"
                  >
                    <div
                      class="flex items-center justify-center w-8 h-8 rounded-lg mr-3 group-hover:bg-opacity-70 transition-colors"
                      :class="item.bgColor"
                    >
                      <component :is="item.icon" class="w-5 h-5" />
                    </div>
                    <span>{{ item.name }}</span>

                    <span
                      v-if="item.badge && fournisseursCount"
                      class="ml-auto bg-blue-100 text-blue-600 text-xs font-medium px-2 py-1 rounded-full"
                    >
                      {{ fournisseursCount }}
                    </span>
                  </Link>
                </li>
              </ul>
            </template>
          </nav>
        </div>
      </aside>

      <!-- Overlay for mobile -->
      <div
        v-if="isSidebarOpen"
        @click="isSidebarOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
      ></div>

      <!-- Main content -->
      <main class="flex-1 p-4 md:p-6 lg:p-8 transition-all duration-300">
        <div class="mb-6">
          <!-- Dynamic header -->
          <h2 class="text-xl font-semibold text-gray-800">{{ currentPageTitle }}</h2>
          <p class="text-gray-500 text-sm">{{ currentPageDescription }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 min-h-[calc(100vh-12rem)]">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import {
  HomeIcon,
  CubeIcon,
  TruckIcon,
  ClipboardDocumentListIcon,
  UserGroupIcon,
  ArrowPathIcon,
  UserIcon,
  Cog6ToothIcon,
  DocumentChartBarIcon,
  ArrowUpCircleIcon,
  DocumentIcon
} from '@heroicons/vue/24/outline'

const isSidebarOpen = ref(false)
const page = usePage()

// Optional supplier count
const fournisseursCount = computed(() => page.props.fournisseursStats?.total || null)

// Sidebar definition
const sidebarSections = [
  {
    label: null,
    items: [
      { name: 'Tableau de bord', href: '/dashboard', match: '/dashboard', icon: HomeIcon, bgColor: 'bg-blue-100 text-blue-600' },
      { name: 'Articles', href: '/articles', match: '/articles', icon: CubeIcon, bgColor: 'bg-green-100 text-green-600' },
    ]
  },
  {
    label: 'Gestion des Achats',
    items: [
      { name: 'Bons de Commande', href: '/achats/bon-commandes', match: '/achats/bon-commandes', icon: ClipboardDocumentListIcon, bgColor: 'bg-purple-100 text-purple-600' },
      { name: 'Fournisseurs', href: '/achats/fournisseurs', match: '/achats/fournisseurs', icon: TruckIcon, bgColor: 'bg-orange-100 text-orange-600', badge: true },
      { name: 'Bons de Réception', href: '/achats/bon-receptions', match: '/achats/bon-receptions', icon: DocumentChartBarIcon, bgColor: 'bg-teal-100 text-teal-600' },
    ]
  },
  {
    label: 'Gestion du Stock',
    items: [
      { name: 'Entrées Stock', href: '/stock/entrees', match: '/stock/entrees', icon: ArrowPathIcon, bgColor: 'bg-green-100 text-green-600' },
      // { name: 'Inventaire', href: '/inventaire', match: '/inventaire', icon: ClipboardDocumentListIcon, bgColor: 'bg-indigo-100 text-indigo-600' },
      { name: 'Sorties Stock', href: '/stock/sorties', match: '/stock/sorties', icon: ArrowPathIcon, bgColor: 'bg-amber-100 text-amber-600' },
    ]
  },
  {
    label: 'Demmande des Articles',
    items: [
      { name: 'Les Demandes', href: '/demandes', match: '/demandes', icon: ArrowUpCircleIcon, bgColor: 'bg-gray-100 text-gray-600' },
    ]
  },
  {
    label: 'Fiches Techniques',
    items: [
      { name: 'Pédagogiques', href: '/fiches-techniques/pedagogique', match: '/fiches-techniques/pedagogique', icon: DocumentIcon, bgColor: 'bg-purple-100 text-purple-600' },
      { name: 'Collectivité', href: '/fiches-techniques/collectivite', match: '/fiches-techniques/collectivite', icon: DocumentIcon, bgColor: 'bg-rose-100 text-rose-600' },

    ]
  },
  {
    label: 'Gestion des Utilisateurs',
    items: [
      { name: 'Utilisateurs', href: '/users', match: '/users', icon: UserGroupIcon, bgColor: 'bg-blue-100 text-blue-600' },
    ]
  },
  {
    label: 'Administration',
    items: [
      { name: 'Profil', href: '/profile', match: '/profile', icon: UserIcon, bgColor: 'bg-gray-100 text-gray-600' },
      { name: 'Paramètres', href: '/parametres', match: '/parametres', icon: Cog6ToothIcon, bgColor: 'bg-red-100 text-red-600' },
    ]
  },
]

// Dynamic page titles and descriptions
const pageTitles = {
  '/dashboard': 'Tableau de Bord',
  '/articles': 'Référentiel des Articles',
  '/achats/fournisseurs': 'Gestion des Fournisseurs',
  '/achats/bon-commandes': 'Bons de Commande',
  '/achats/bon-receptions': 'Bons de Réception',
  // '/inventaire': 'Inventaire du Stock',
  '/stock/sorties': 'Sorties de Stock',
  '/demandes': 'Mes Demandes',
  '/users': 'Gestion des Utilisateurs',
  '/profile': 'Mon Profil',
  '/parametres': 'Paramètres du Système',
  '/fiches-techniques/collectivite': 'Fiches Techniques Pédagogiques',
}

const pageDescriptions = {
  '/dashboard': 'Vue d\'ensemble de votre activité et statistiques',
  '/articles': 'Gestion du catalogue et des références articles',
  '/achats/fournisseurs': 'Gérez vos partenaires fournisseurs et leurs informations',
  '/achats/bon-commandes': 'Créez et suivez vos bons de commande',
  '/achats/bon-receptions': 'Enregistrez et validez les réceptions de marchandises',
  // '/inventaire': 'Consultez et gérez les niveaux de stock',
  '/stock/sorties': 'suivez vos sorties de stock',
  '/users': 'Gérez les utilisateurs de l\'application',
  '/profile': 'Gérez vos informations personnelles',
  '/parametres': 'Configurez les paramètres de l\'application'
}

const currentPageTitle = computed(() => {
  const currentUrl = page.url
  return Object.entries(pageTitles).find(([path]) => currentUrl.startsWith(path))?.[1] || 'Gestion Stock'
})

const currentPageDescription = computed(() => {
  const currentUrl = page.url
  return Object.entries(pageDescriptions).find(([path]) => currentUrl.startsWith(path))?.[1] || 'Interface de gestion de stock et d\'achats'
})
</script>

<style scoped>
.sidebar-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.sidebar-scrollbar::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 4px;
}
.sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
  EyeIcon,
  PlusIcon,
  ClipboardDocumentListIcon,
  DocumentArrowDownIcon,
  MagnifyingGlassIcon,
  PencilIcon,
  QuestionMarkCircleIcon,
  XCircleIcon,
  CheckCircleIcon,
  ClockIcon,
  CheckBadgeIcon,
} from '@heroicons/vue/24/outline'
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { ModalLink } from '@inertiaui/modal-vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import Dump from '@/Components/Dump.vue';
import { getSortieStatutInfo, getSortieTypeInfo } from '@/Utils/labels';

const props = defineProps({
  sorties: Object,
  filters: Object,
  stats: Object
})

function formatDate(date) {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

const filters = ref({
  search: props.filters.search || '',
  statut: props.filters.statut || '',
  start_date: props.filters.start_date || '',
  end_date: props.filters.end_date || '',
})

function resetFilters() {
  filters.value = { search: '', statut: '', start_date: '', end_date: '' }
  router.get(route('sortie-stocks.index'))
}

function applyFilters() {
  router.get(route('sortie-stocks.index'), filters.value)
}

const showDelivredModal = ref(false)
const sortieIdToDelivred = ref(null)

function openDelivredModal(id) {
  showDelivredModal.value = true
  sortieIdToDelivred.value = id
}

function delivredSortie() {
  const sortieStock = sortieIdToDelivred.value;
  router.put(route('sortie-stocks.livrer', sortieStock), {
    onSuccess: () => alert('Sortie Delevrée avec succès !'),
    onError: (e) => alert('Erreur lors de la livraison : ' + e.message),
  })
}

</script>

<template>
  <AuthenticatedLayout>
    <Head title="Sorties de Stock" />
    

    <div class="space-y-6">
      <!-- Header -->
      <div class="bg-gradient-to-r from-blue-600 to-purple-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
          <div class="flex-1">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Sorties de Stock</h1>
            <p class="text-blue-100 text-lg opacity-90">Suivez et gérez toutes les sorties de stock</p>
          </div>
          <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            <!-- <Link
              class="bg-blue-500 text-white px-6 py-3 rounded-xl hover:bg-blue-400 flex items-center justify-center gap-3 font-semibold border border-blue-400 transition-all"
            >
              <DocumentArrowDownIcon class="h-5 w-5" />
              Exporter
            </Link> -->
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filtrer les sorties</h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
            <div class="relative">
              <input
                v-model="filters.search"
                type="text"
                placeholder="N° ou motif..."
                class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 focus:ring-blue-500 focus:border-blue-500"
              />
              <MagnifyingGlassIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
            <select
              v-model="filters.statut"
              class="w-full border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Tous</option>
              <option value="cree">Créée</option>
              <option value="en_attente">En attente</option>
              <option value="validee">Validée</option>
              <option value="annulee">Annulée</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
            <input
              v-model="filters.start_date"
              type="date"
              class="w-full border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
            <input
              v-model="filters.end_date"
              type="date"
              class="w-full border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        </div>

        <div class="mt-5 flex flex-col sm:flex-row justify-end gap-3">
          <button
            @click="resetFilters"
            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all"
          >
            Réinitialiser
          </button>

          <button
            @click="applyFilters"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all flex items-center gap-2"
          >
            <MagnifyingGlassIcon class="w-4 h-4" />
            Rechercher
          </button>
        </div>
      </div>

      <!-- Stats -->
   <div class="mt-6">
  <!-- Stats Section -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Total sorties -->
    <div class="bg-white p-5 rounded-xl border shadow-sm flex items-center gap-4">
      <div class="bg-blue-100 p-3 rounded-full">
        <ClipboardDocumentListIcon class="w-6 h-6 text-blue-600" />
      </div>
      <div>
        <p class="text-gray-500 text-sm">Total sorties</p>
        <p class="text-2xl font-bold text-blue-600">{{ stats.total }}</p>
      </div>
    </div>

    <!-- En attente -->
    <div class="bg-white p-5 rounded-xl border shadow-sm flex items-center gap-4">
      <div class="bg-yellow-100 p-3 rounded-full">
        <ClockIcon class="w-6 h-6 text-yellow-600" />
      </div>
      <div>
        <p class="text-gray-500 text-sm">En attente</p>
        <p class="text-2xl font-bold text-yellow-600">{{ stats.en_attente }}</p>
      </div>
    </div>

    <!-- Validées -->
    <div class="bg-white p-5 rounded-xl border shadow-sm flex items-center gap-4">
      <div class="bg-green-100 p-3 rounded-full">
        <CheckCircleIcon class="w-6 h-6 text-green-600" />
      </div>
      <div>
        <p class="text-gray-500 text-sm">Validées</p>
        <p class="text-2xl font-bold text-green-600">{{ stats.validee }}</p>
      </div>
    </div>

    <!-- Annulées -->
    <div class="bg-white p-5 rounded-xl border shadow-sm flex items-center gap-4">
      <div class="bg-red-100 p-3 rounded-full">
        <XCircleIcon class="w-6 h-6 text-red-600" />
      </div>
      <div>
        <p class="text-gray-500 text-sm">Annulées</p>
        <p class="text-2xl font-bold text-red-600">{{ stats.annulee }}</p>
      </div>
    </div>
  </div>
</div>


      <!-- Table -->
      <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Demandeur</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Créée par</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="sortie in sorties.data" :key="sortie.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ sortie.numero }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <span :class="`px-2 py-1 rounded text-xs font-medium ${getSortieTypeInfo(sortie.type).color}`">
                        {{ getSortieTypeInfo(sortie.type).label }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ sortie.demandeur }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ sortie.date }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ sortie.notes || '-' }}</td>
                <td class="px-6 py-4">
                    <span :class="`px-2 py-1 rounded text-xs font-medium ${getSortieStatutInfo(sortie.statut).color}`" >
                        {{ getSortieStatutInfo(sortie.statut).label }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ sortie.created_by }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex space-x-2">
                    <ModalLink
                        :href="route('sortie-stocks.show', sortie.id)"
                      class="text-blue-600 hover:text-blue-900 p-1"
                      title="Voir détails"
                    >
                      <EyeIcon class="h-5 w-5" />
                    </ModalLink>
                  
                    <button
                      @click="openDelivredModal(sortie.id)"
                      class="text-green-600 hover:text-green-900 p-1"
                      title="Livrer la sortie"
                      v-if="sortie.statut === 'attente_livraison'"
                    >
                      <CheckBadgeIcon class="h-5 w-5" />
                    </button>

                    <Link
                      :href="route('sortie-stocks.show.approve', sortie.id)"
                      class="text-orange-600 hover:text-orange-900 p-1"
                      title="Approuver la sortie"
                      v-if="sortie.statut === 'attente_validation'"
                    >
                      <QuestionMarkCircleIcon class="h-5 w-5" />
                    </Link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="sorties.data.length === 0" class="text-center py-12">
          <ClipboardDocumentListIcon class="mx-auto h-12 w-12 text-gray-400" />
          <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune sortie trouvée</h3>
          <p class="mt-1 text-sm text-gray-500">Créez votre première sortie de stock.</p>
          <div class="mt-6">
            
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="sorties.links && sorties.links.length > 1" class="bg-white px-6 py-3 border-t border-gray-200">
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-700">
              Affichage de {{ sorties.from }} à {{ sorties.to }} sur {{ sorties.total }} résultats
            </div>
            <div class="flex space-x-2">
              <template v-for="link in sorties.links" :key="link.label">
                <Link
                  v-if="link.url"
                  :href="link.url"
                  :class="[
                    'px-3 py-1 rounded-lg text-sm font-medium',
                    link.active
                      ? 'bg-blue-600 text-white'
                      : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                  ]"
                  v-html="link.label"
                />
                <span
                  v-else
                  class="px-3 py-1 rounded-lg text-sm font-medium bg-gray-100 text-gray-400 cursor-not-allowed"
                  v-html="link.label"
                />
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>

    <ConfirmationModal
      type="info"
      :show="showDelivredModal"
      title="Livrer la sortie"
      message="Êtes-vous sûr de vouloir livrer cette sortie ?"
      :onConfirm="delivredSortie"
      @close="showDelivredModal = false"
    />

  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
  EyeIcon,
  PlusIcon,
  ClipboardDocumentListIcon,
  MagnifyingGlassIcon,
  PencilIcon,
  DocumentTextIcon,
  QuestionMarkCircleIcon
} from '@heroicons/vue/24/outline'
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { ModalLink } from '@inertiaui/modal-vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import { getChefCommandeStatutInfo } from '@/Utils/labels.js'; // 👈 You can reuse your demande version and rename

const props = defineProps({
  chefCommandes: Object,
  filters: Object,
});

function formatDate(date) {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

const filters = ref({
  search: '',
  status: '',
  start_date: '',
  end_date: '',
})

function resetFilters() {
  filters.value = { search: '', status: '', start_date: '', end_date: '' }
  router.get(route('chef-commandes.index'))
}

function applyFilters() {
  router.get(route('chef-commandes.index'), filters.value)
}

const showCancelModal = ref(false)
const chefCommandeIdToCancel = ref(null)

function openCancelModal(id) {
  chefCommandeIdToCancel.value = id
  showCancelModal.value = true
}

function cancelChefCommande() {
  return router.delete(route('chef-commandes.cancel', chefCommandeIdToCancel.value), {}, {
    onSuccess: () => {
      alert('Bon de commande annulé avec succès !')
    },
    onError: (errors) => {
      alert('Impossible d’annuler le bon de commande : ' + errors.message)
    }
  })
}

const getStatutColor = (statut) => getChefCommandeStatutInfo(statut).color;
const getStatutLabel = (statut) => getChefCommandeStatutInfo(statut).label;
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Bons de Commande" />

    <div class="space-y-6">
      <!-- Header -->
      <div class="bg-gradient-to-r from-blue-600 to-purple-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
          <div class="flex-1">
            <h1 class="text-3xl font-bold mb-2">Bons de Commande</h1>
            <p class="text-indigo-100 text-lg opacity-90">Gérez et suivez tous vos bons de commande</p>
          </div>
          <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            <ModalLink
              :href="route('chef-commandes.create')"
              class="bg-white text-indigo-600 px-6 py-3 rounded-xl hover:bg-indigo-50 flex items-center justify-center gap-3 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl"
            >
              <PlusIcon class="h-5 w-5" />
              Nouveau Bon
            </ModalLink>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filtrer les bons de commande</h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
            <div class="relative">
              <input
                v-model="filters.search"
                type="text"
                placeholder="N° de bon ou fournisseur..."
                class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 focus:ring-indigo-500 focus:border-indigo-500"
              />
              <MagnifyingGlassIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
            <select
              v-model="filters.status"
              class="w-full border border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500"
            >
              <option value="">Tous</option>
              <option value="EN_ATTENTE">En attente</option>
              <option value="VALIDEE">Validé</option>
              <option value="REFUSEE">Refusé</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
            <input
              v-model="filters.start_date"
              type="date"
              class="w-full border border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
            <input
              v-model="filters.end_date"
              type="date"
              class="w-full border border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500"
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
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all flex items-center gap-2"
          >
            <MagnifyingGlassIcon class="w-4 h-4" />
            Rechercher
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fournisseur</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
              </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="bc in chefCommandes.data" :key="bc.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                  {{ bc.reference || bc.id }}
                </td>

                <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                  {{ formatDate(bc.date_bon) }}
                </td>

                <td class="px-6 py-4 whitespace-nowrap">{{ bc.fournisseur?.nom || '---' }}</td>

                <td class="px-6 py-4 whitespace-nowrap">{{ bc.total }} MAD</td>

                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="[
                      'px-2 py-1 text-xs font-medium rounded-full',
                      getStatutColor(bc.statut)
                    ]"
                  >
                    {{ getStatutLabel(bc.statut) }}
                  </span>
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex space-x-2">
                    <ModalLink
                      :href="route('chef-commandes.show', bc.id)"
                      class="text-blue-600 hover:text-blue-900 p-1"
                      title="Voir détails"
                    >
                      <EyeIcon class="h-5 w-5" />
                    </ModalLink>

                    <ModalLink
                      :href="route('chef-commandes.edit', bc.id)"
                      class="text-green-600 hover:text-green-900 p-1"
                      title="Modifier"
                    >
                      <PencilIcon class="h-5 w-5" />
                    </ModalLink>

                    <a
                      class="text-purple-600 hover:text-purple-900 p-1 cursor-pointer"
                      :href="bc.pdf_url"
                      :class="!bc.pdf_url ? '!cursor-not-allowed opacity-50 pointer-events-none' : ''"
                      title="Télécharger PDF"
                      target="_blank"
                    >
                      <DocumentTextIcon class="h-5 w-5" />
                    </a>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty message -->
        <div v-if="chefCommandes.data.length === 0" class="text-center py-12">
          <div class="text-gray-500">
            <ClipboardDocumentListIcon class="mx-auto h-12 w-12" />
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun bon de commande trouvé</h3>
            <p class="mt-1 text-sm text-gray-500">Commencez par créer votre premier bon de commande.</p>
            <div class="mt-6">
              <ModalLink
                :href="route('chef-commandes.create')"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"
              >
                <PlusIcon class="h-4 w-4 mr-2" />
                Nouveau bon
              </ModalLink>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="chefCommandes.links && chefCommandes.links.length > 1" class="bg-white px-6 py-3 border-t border-gray-200">
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-700">
              Affichage de {{ chefCommandes.from }} à {{ chefCommandes.to }} sur {{ chefCommandes.total }} résultats
            </div>
            <div class="flex space-x-2">
              <template v-for="link in chefCommandes.links" :key="link.label">
                <Link
                  v-if="link.url"
                  :href="link.url"
                  :class="[
                    'px-3 py-1 rounded-lg text-sm font-medium',
                    link.active 
                      ? 'bg-indigo-600 text-white' 
                      : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                  ]"
                  v-html="link.label"
                />
                <span
                  v-else
                  :class="[
                    'px-3 py-1 rounded-lg text-sm font-medium bg-gray-100 text-gray-400 cursor-not-allowed'
                  ]"
                  v-html="link.label"
                />
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>

    <ConfirmationModal
      :show="showCancelModal"
      title="Annuler le bon de commande"
      message="Êtes-vous sûr de vouloir annuler ce bon de commande ?"
      :onConfirm="cancelChefCommande"
      @close="showCancelModal = false"
    />
  </AuthenticatedLayout>
</template>

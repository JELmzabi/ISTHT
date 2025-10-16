<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    EyeIcon,
    PlusIcon,
    ClipboardDocumentListIcon,
    ClockIcon,
    CheckCircleIcon,
    XCircleIcon,
    DocumentArrowDownIcon,
    MagnifyingGlassIcon,
    PencilIcon
} from '@heroicons/vue/24/outline'
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { ModalLink } from '@inertiaui/modal-vue';
import Dump from '@/Components/Dump.vue';

const props = defineProps({
    demandes: Object,
    filters: Object,
})

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
  router.get(route('demandes.mes-demandes'))
}

function applyFilters() {
  router.get(route('demandes.mes-demandes'), filters.value)
}


function getStatutColor(statut) {
  switch (statut) {
    case 'en_attente':
      return 'bg-yellow-100 text-yellow-700';
    case 'approuvee':
      return 'bg-green-100 text-green-700';
    case 'rejetee':
      return 'bg-red-100 text-red-700';
    case 'annulee':
      return 'bg-gray-100 text-gray-600';
    default:
      return 'bg-gray-100 text-gray-600';
  }
}

function getStatutLabel(statut) {
  switch (statut) {
    case 'en_attente':
      return 'En attente';
    case 'approuvee':
      return 'Approuvée';
    case 'rejetee':
      return 'Rejetée';
    case 'annulee':
      return 'Annulée';
    default:
      return statut;
  }
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Mes Demandes" />

        <div class="space-y-6">
            <!-- Header with actions -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-700 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <h1 class="text-3xl md:text-4xl font-bold mb-2">Mes Demandes</h1>
                        <p class="text-blue-100 text-lg opacity-90">Suivez et gérez toutes vos demandes</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <ModalLink
                            :href="route('demandes.create')"
                            class="bg-white text-blue-600 px-6 py-3 rounded-xl hover:bg-blue-50 flex items-center justify-center gap-3 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl"
                        >
                            <PlusIcon class="h-5 w-5" />
                            Nouvelle Demande
                        </ModalLink>
                        <Link
                            class="bg-blue-500 text-white px-6 py-3 rounded-xl hover:bg-blue-400 flex items-center justify-center gap-3 transition-all duration-200 font-semibold border border-blue-400"
                        >
                            <DocumentArrowDownIcon class="h-5 w-5" />
                            Exporter
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Search Filter -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Filtrer les demandes</h3>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                        <div class="relative">
                            <input
                                v-model="filters.search"
                                type="text"
                                placeholder="N° de demande ou objet..."
                                class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                            />
                            <MagnifyingGlassIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select
                            v-model="filters.status"
                            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">Tous</option>
                            <option value="EN_ATTENTE">En attente</option>
                            <option value="VALIDEE">Validée</option>
                            <option value="REFUSEE">Refusée</option>
                        </select>
                    </div>

                    <!-- Date début -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
                        <input
                            v-model="filters.start_date"
                            type="date"
                            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>

                    <!-- Date fin -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
                        <input
                            v-model="filters.end_date"
                            type="date"
                            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                </div>

                <!-- Buttons -->
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


            <!-- Table -->
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Articles</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Validateur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Validation date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="demande in demandes.data" :key="demande.id" class="hover:bg-gray-50">
                                <!-- ID -->
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ demande.numero || demande.id }}
                                </td>

                                <!-- Date -->
                                <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                    {{ formatDate(demande.date_demande) }}
                                </td>

                                <!-- Statut -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="[
                                        'px-2 py-1 text-xs font-medium rounded-full',
                                        getStatutColor(demande.statut)
                                        ]"
                                    >
                                        {{ getStatutLabel(demande.statut) }}
                                    </span>
                                </td>

                                <!-- Articles -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ demande.articles_count }}
                                </td>

                                <!-- valideur -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class='px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full' >
                                        {{ demande.validateur || 'Non validé' }}
                                    </span>
                                </td>

                                <!-- Validation date -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ demande.date_validation ? formatDate(demande.date_validation) : '------' }}
                                </td>
                                

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <ModalLink
                                            :href="route('demandes.show', demande.id)"
                                            class="text-blue-600 hover:text-blue-900 p-1"
                                            title="Voir détails"
                                        >
                                            <EyeIcon class="h-5 w-5" />
                                        </ModalLink>

                                        <ModalLink
                                            :href="route('demandes.edit', demande.id)"
                                            class="text-blue-600 hover:text-blue-900 p-1"
                                            title="Modifier"
                                        >
                                            <PencilIcon class="h-5 w-5" />
                                        </ModalLink>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Message vide -->
                <div v-if="demandes.data.length === 0" class="text-center py-12">
                    <div class="text-gray-500">
                        <ClipboardDocumentListIcon class="mx-auto h-12 w-12" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune demande trouvée</h3>
                        <p class="mt-1 text-sm text-gray-500">Commencez par créer votre première demande.</p>
                        <div class="mt-6">
                            <ModalLink
                                :href="route('demandes.create')"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                            >
                                <PlusIcon class="h-4 w-4 mr-2" />
                                Nouvelle demande
                            </ModalLink>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="demandes.links && demandes.links.length > 1" class="bg-white px-6 py-3 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-700">
                            Affichage de {{ demandes.from }} à {{ demandes.to }} sur {{ demandes.total }} résultats
                        </div>
                        <div class="flex space-x-2">
                            <template v-for="link in demandes.links" :key="link.label">
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
    </AuthenticatedLayout>
</template>

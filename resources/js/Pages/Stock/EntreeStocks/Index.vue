<template>
    <AuthenticatedLayout>
        <Head title="Gestion des Entrées de Stock" />

        <div class="space-y-6">
            <!-- En-tête avec boutons d'action -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-700 rounded-2xl p-6 text-white">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <h1 class="text-3xl md:text-4xl font-bold mb-2">Entrées de Stock</h1>
                        <p class="text-blue-100 text-lg opacity-90">Gestion complète des entrées en stock et validation</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <!-- <Link
                            :href="route('entree-stocks.create')"
                            class="bg-white text-blue-600 px-6 py-3 rounded-xl hover:bg-blue-50 flex items-center justify-center gap-3 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl"
                        >
                            <PlusIcon class="h-5 w-5" />
                            Nouvelle Entrée
                        </Link> -->
                        <button
                            @click="exportData"
                            class="bg-blue-500 text-white px-6 py-3 rounded-xl hover:bg-blue-400 flex items-center justify-center gap-3 transition-all duration-200 font-semibold border border-blue-400"
                        >
                            <DocumentArrowDownIcon class="h-5 w-5" />
                            Exporter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cartes de statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Entrées</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats?.total || 0 }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-xl">
                            <DocumentTextIcon class="h-8 w-8 text-blue-600" />
                        </div>
                    </div>
                    <!-- <div class="mt-4 flex items-center text-sm text-green-600">
                        <ArrowTrendingUpIcon class="h-4 w-4 mr-1" />
                        <span>+12% ce mois</span>
                    </div> -->
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">En Attente</p>
                            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ stats?.attente_validation || 0 }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-xl">
                            <ClockIcon class="h-8 w-8 text-yellow-600" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div 
                                class="bg-yellow-500 h-2 rounded-full" 
                                :style="{ width: stats.total ? `${(stats.attente_validation / stats.total) * 100}%` : '0%' }"
                            ></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Validées</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ stats?.valide || 0 }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-xl">
                            <CheckBadgeIcon class="h-8 w-8 text-green-600" />
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-green-600">
                        <CheckCircleIcon class="h-4 w-4 mr-1" />
                        <span>{{ stats.total ? `${Math.round((stats.valide / stats.total) * 100)}%` : '0%' }} du total</span>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Annulées</p>
                            <p class="text-3xl font-bold text-red-600 mt-2">{{ stats?.annule || 0 }}</p>
                        </div>
                        <div class="p-3 bg-red-100 rounded-xl">
                            <XCircleIcon class="h-8 w-8 text-red-600" />
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-500">
                        Dernière annulation: {{ lastCancelled }}
                    </div>
                </div>
            </div>

            <!-- Filtres et Recherche -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <FunnelIcon class="h-5 w-5 text-blue-600" />
                        Filtres et Recherche
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                            <select 
                                v-model="filters.statut" 
                                class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                            >
                                <option value="">Tous les statuts</option>
                                <option value="attente_validation">🟡 En attente</option>
                                <option value="valide">🟢 Validé</option>
                                <option value="annule">🔴 Annulé</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                            <input 
                                v-model="filters.date_debut" 
                                type="date" 
                                class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                            <input 
                                v-model="filters.date_fin" 
                                type="date" 
                                class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                            <div class="relative">
                                <input 
                                    v-model="filters.search" 
                                    type="text" 
                                    placeholder="N° entrée, fournisseur..."
                                    class="w-full border border-gray-300 rounded-xl p-3 pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                >
                                <MagnifyingGlassIcon class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" />
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mt-6">
                        <div class="text-sm text-gray-500">
                            {{ entreeStocks?.total || 0 }} entrée(s) trouvée(s)
                        </div>
                        <div class="flex gap-3">
                            <button
                                @click="resetFilters"
                                class="px-6 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition-all duration-200"
                            >
                                <ArrowPathIcon class="h-4 w-4" />
                                Réinitialiser
                            </button>
                            <button
                                @click="applyFilters"
                                class="px-6 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 flex items-center gap-2 transition-all duration-200"
                            >
                                <FunnelIcon class="h-4 w-4" />
                                Appliquer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des entrées de stock -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <QueueListIcon class="h-6 w-6 text-blue-600" />
                            Historique des Entrées
                        </h3>
                        <div class="flex items-center gap-4">
                            <div class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full border">
                                {{ entreeStocks?.total || 0 }} résultat(s)
                            </div>
                            <select 
                                v-model="itemsPerPage" 
                                @change="updatePagination"
                                class="border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="10">10/page</option>
                                <option value="20">20/page</option>
                                <option value="50">50/page</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tableau -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    <div class="flex items-center gap-2">
                                        <span>N° Entrée</span>
                                        <button @click="sortBy('numero_affichage')" class="text-gray-400 hover:text-gray-600">
                                            <ArrowsUpDownIcon class="h-4 w-4" />
                                        </button>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    Fournisseur
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    Date
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    Articles
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    Quantité
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    Montant
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    Statut
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr 
                                v-for="entree in entreeStocks?.data || []" 
                                :key="entree.id" 
                                class="hover:bg-blue-50/30 transition-all duration-200 group"
                            >
                                <!-- Numéro Entrée -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div :class="getStatusIconBg(entree.statut)" class="p-2 rounded-lg">
                                            <DocumentTextIcon class="h-5 w-5 text-white" />
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 group-hover:text-blue-700 transition-colors">
                                                {{ entree.numero_affichage }}
                                            </div>
                                            <div v-if="entree.bon_reception" class="text-sm text-blue-600 flex items-center gap-1">
                                                <DocumentIcon class="h-3 w-3" />
                                                {{ entree.bon_reception.numero }}
                                            </div>
                                            <div v-else class="text-sm text-gray-400">
                                                Manuelle
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Fournisseur -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center border-2 border-white shadow-sm">
                                                <BuildingStorefrontIcon class="h-5 w-5 text-blue-600" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">
                                                {{ entree.fournisseur?.nom_affichage || entree.fournisseur?.raison_sociale || 'Non spécifié' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ entree.fournisseur?.code_fournisseur || '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Date -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">{{ formatDate(entree.date_entree) }}</div>
                                    <div class="text-xs text-gray-500">{{ formatTime(entree.created_at) }}</div>
                                </td>

                                <!-- Articles -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                            <CubeIcon class="h-4 w-4 mr-1" />
                                            {{ entree.lignes_entree?.length || 0 }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Quantité -->
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ calculateTotalQuantite(entree) }}
                                    </div>
                                    <div class="text-xs text-gray-500">unités</div>
                                </td>

                                <!-- Montant -->
                                <td class="px-6 py-4 text-right">
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ formatCurrency(calculateTotalMontant(entree)) }}
                                    </div>
                                    <div class="text-xs text-gray-500">TTC</div>
                                </td>

                                <!-- Statut -->
                                <td class="px-6 py-4 text-center">
                                    <span :class="getStatutBadgeClass(entree.statut)" 
                                          class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold transition-all duration-200">
                                        <component :is="getStatutIcon(entree.statut)" class="h-4 w-4 mr-1.5" />
                                        {{ getStatutLabel(entree.statut) }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex justify-center items-center gap-1">
                                        <!-- Voir détails -->
                                        <button
                                            @click="openDetailModal(entree)"
                                            class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-xl transition-all duration-200 group/tooltip relative"
                                            title="Voir détails"
                                        >
                                            <EyeIcon class="h-5 w-5" />
                                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded py-1 px-2 opacity-0 group-hover/tooltip:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                Voir détails
                                            </div>
                                        </button>

                                        <!-- Valider -->
                                        <Link
                                            v-if="entree.statut === 'attente_validation'"
                                            :href="route('entree-stocks.valider', entree.id)"
                                            class="p-2 text-green-600 hover:text-green-800 hover:bg-green-100 rounded-xl transition-all duration-200 group/tooltip relative"
                                            as="button"
                                            method="post"
                                            title="Valider l'entrée"
                                        >
                                            <CheckIcon class="h-5 w-5" />
                                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded py-1 px-2 opacity-0 group-hover/tooltip:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                Valider
                                            </div>
                                        </Link>

                                        <!-- Annuler -->
                                        <button
                                            v-if="entree.statut === 'attente_validation'"
                                            @click="openAnnulationModal(entree)"
                                            class="p-2 text-red-600 hover:text-red-800 hover:bg-red-100 rounded-xl transition-all duration-200 group/tooltip relative"
                                            title="Annuler l'entrée"
                                        >
                                            <XMarkIcon class="h-5 w-5" />
                                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded py-1 px-2 opacity-0 group-hover/tooltip:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                Annuler
                                            </div>
                                        </button>

                                        <!-- PDF -->
                                        <Link
                                            :href="route('entree-stocks.download-pdf', entree.id)"
                                            class="p-2 text-purple-600 hover:text-purple-800 hover:bg-purple-100 rounded-xl transition-all duration-200 group/tooltip relative"
                                            title="Télécharger PDF"
                                        >
                                            <DocumentArrowDownIcon class="h-5 w-5" />
                                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded py-1 px-2 opacity-0 group-hover/tooltip:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                Télécharger PDF
                                            </div>
                                        </Link>

                                        <!-- Menu contextuel -->
                                        <!-- <div class="relative">
                                            <button
                                                @click="toggleContextMenu(entree.id)"
                                                class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-all duration-200"
                                            >
                                                <EllipsisVerticalIcon class="h-5 w-5" />
                                            </button>
                                            <div 
                                                v-if="contextMenuId === entree.id"
                                                class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-10"
                                            >
                                                <Link 
                                                    :href="route('entree-stocks.show', entree.id)"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                >
                                                    <EyeIcon class="h-4 w-4" />
                                                    Détails complets
                                                </Link>
                                                <Link 
                                                    :href="route('entree-stocks.edit', entree.id)"
                                                    v-if="entree.statut === 'attente_validation'"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm text-blue-600 hover:bg-blue-50"
                                                >
                                                    <PencilIcon class="h-4 w-4" />
                                                    Modifier
                                                </Link>
                                                <button
                                                    @click="duplicateEntry(entree)"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm text-green-600 hover:bg-green-50 w-full text-left"
                                                >
                                                    <DocumentDuplicateIcon class="h-4 w-4" />
                                                    Dupliquer
                                                </button>
                                            </div>
                                        </div> -->
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- État vide -->
                <div v-if="!entreeStocks?.data || entreeStocks.data.length === 0" class="text-center py-16">
                    <div class="text-gray-400">
                        <InboxIcon class="mx-auto h-24 w-24 text-gray-300 mb-4" />
                        <h3 class="text-xl font-medium text-gray-900 mb-2">Aucune entrée de stock</h3>
                        <p class="text-gray-500 max-w-md mx-auto mb-6">
                            {{ filters.statut || filters.date_debut || filters.date_fin || filters.search ? 
                               'Aucun résultat ne correspond à vos critères de recherche.' : 
                               'Commencez par créer votre première entrée de stock pour gérer vos approvisionnements.' }}
                        </p>
                        <Link
                            :href="route('entree-stocks.create')"
                            class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-colors"
                        >
                            <PlusIcon class="h-5 w-5" />
                            Créer une entrée
                        </Link>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="entreeStocks?.links && entreeStocks.links.length > 3" class="bg-white px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-gray-700">
                            Affichage de {{ entreeStocks.from }} à {{ entreeStocks.to }} sur {{ entreeStocks.total }} entrées
                        </div>
                        <div class="flex items-center gap-1">
                            <template v-for="link in entreeStocks.links" :key="link.label">
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    :class="[
                                        'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 border',
                                        link.active 
                                            ? 'bg-blue-600 text-white border-blue-600 shadow-sm' 
                                            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 hover:border-gray-400'
                                    ]"
                                    v-html="link.label"
                                />
                                <span
                                    v-else
                                    :class="[
                                        'px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-400 border border-gray-300 cursor-not-allowed'
                                    ]"
                                    v-html="link.label"
                                />
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals (garder les mêmes modaux que précédemment) -->
       <div v-if="showDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-6xl shadow-lg rounded-md bg-white">
                <!-- En-tête du modal -->
                <div class="flex items-center justify-between pb-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Détails de l’entrée de stock
                    </h3>
                    <button 
                        @click="closeDetailModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!-- Header -->

                <!-- Stock Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">
                    <div class="bg-white rounded-xl shadow p-4">
                        <h2 class="font-semibold text-gray-800 mb-2">Informations générales</h2>
                        <p><strong>Numéro :</strong> {{ selectedEntree.numero }}</p>
                        <p><strong>Date d’entrée :</strong> {{ formatDate(selectedEntree.date_entree) }}</p>
                        <p><strong>Référence bon de réception :</strong> {{ selectedEntree.bon_reception.numero }}</p>
                    </div>

                    <div class="bg-white rounded-xl shadow p-4">
                        <h2 class="font-semibold text-gray-800 mb-2">Fournisseur</h2>
                        <p><strong>Nom :</strong> {{ selectedEntree.fournisseur.nom }}</p>
                        <p><strong>Téléphone :</strong> {{ selectedEntree.fournisseur.telephone }}</p>
                        <p><strong>Email :</strong> {{ selectedEntree.fournisseur.email }}</p>
                        <p><strong>Adresse :</strong> {{ selectedEntree.fournisseur.adresse }}</p>
                    </div>
                </div>

                <!-- Articles Table -->
                <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="min-w-full border-collapse">
                    <thead class="bg-gray-100 border-b">
                    <tr class="text-gray-700 text-sm">
                        <th class="py-3 px-4 text-left">Code Article</th>
                        <th class="py-3 px-4 text-left">Désignation</th>
                        <th class="py-3 px-4 text-center">Quantité</th>
                        <th class="py-3 px-4 text-right">Prix unitaire (DH)</th>
                        <th class="py-3 px-4 text-right">Montant HT</th>
                        <th class="py-3 px-4 text-right">TVA (%)</th>
                        <th class="py-3 px-4 text-right">Montant TTC</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr class="text-sm border-b hover:bg-gray-50" v-for="ligne in selectedEntree.lignes_entree" :key="ligne.id">
                        <td class="py-3 px-4">{{ ligne.article.reference }}</td>
                        <td class="py-3 px-4">{{ ligne.article.designation }}</td>
                        <td class="py-3 px-4 text-center">{{ ligne.quantite }}</td>
                        <td class="py-3 px-4 text-right">{{ formatCurrency(ligne.prix_unitaire) }}</td>
                        <td class="py-3 px-4 text-right">{{  formatCurrency(ligne.prix_total)  }}</td>
                        <td class="py-3 px-4 text-right">{{ ligne.taux_tva ?? 0 }}%</td>
                        <td class="py-3 px-4 text-right">{{formatCurrency(ligne.total_ttc) }}</td>
                    </tr>
                    </tbody>

                    <tfoot class="bg-gray-100 font-semibold text-gray-800">
                    <tr>
                        <td></td>
                        <td colspan="2" class="py-3 px-4 text-right">Total</td>
                        <td></td>
                        <td class="py-3 px-4 text-right">{{formatCurrency(calculateTotalMontant(selectedEntree))}}</td>
                        <td></td>
                        <td class="py-3 px-4 text-right">{{formatCurrency(calculateTotalMontantTtc(selectedEntree))}}</td>
                    </tr>
                    </tfoot>
                </table>
                </div>
            </div>
        </div>

        <Modal :show="showValidationModal" @close="closeValidationModal">
            <!-- Contenu du modal de validation -->
        </Modal>

        <Modal :show="showAnnulationModal" @close="closeAnnulationModal">
            <!-- Contenu du modal d'annulation -->
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import { ref, watch, computed } from 'vue';
import { 
    PlusIcon,
    DocumentTextIcon,
    CheckBadgeIcon,
    ClockIcon,
    XCircleIcon,
    EyeIcon,
    CheckIcon,
    XMarkIcon,
    DocumentArrowDownIcon,
    BuildingStorefrontIcon,
    InboxIcon,
    FunnelIcon,
    MagnifyingGlassIcon,
    ArrowPathIcon,
    QueueListIcon,
    ArrowsUpDownIcon,
    DocumentIcon,
    CubeIcon,
    EllipsisVerticalIcon,
    PencilIcon,
    DocumentDuplicateIcon,
    ArrowTrendingUpIcon,
    CheckCircleIcon
} from '@heroicons/vue/24/outline';
import Dump from '@/Components/Dump.vue';

// Props
const props = defineProps({
    entreeStocks: {
        type: Object,
        default: () => ({ data: [], links: [] })
    },
    filters: {
        type: Object,
        default: () => ({})
    },
    stats: {
        type: Object,
        default: () => ({})
    },
});

const calculateTotalMontantTtc = (entree) => {
    if (!entree.lignes_entree) return 0;
    return entree.lignes_entree.reduce((total, ligne) => total + parseFloat(ligne.total_ttc), 0);
};

// États
const filters = ref({
    statut: props.filters?.statut || '',
    date_debut: props.filters?.date_debut || '',
    date_fin: props.filters?.date_fin || '',
    search: props.filters?.search || '',
});

const itemsPerPage = ref(10);
const contextMenuId = ref(null);
const showDetailModal = ref(false);
const showValidationModal = ref(false);
const showAnnulationModal = ref(false);
const selectedEntree = ref(null);

// Computed
const lastCancelled = computed(() => {
    const cancelled = props.entreeStocks?.data?.find(entree => entree.statut === 'annule');
    return cancelled ? formatDate(cancelled.updated_at) : 'Aucune';
});

// Méthodes
const applyFilters = () => {
    router.get(route('entree-stocks.index'), {
        ...filters.value,
        per_page: itemsPerPage.value
    }, {
        preserveState: true,
        replace: true,
    });
};

const resetFilters = () => {
    filters.value = {
        statut: '',
        date_debut: '',
        date_fin: '',
        search: '',
    };
    applyFilters();
};

const updatePagination = () => {
    applyFilters();
};

const sortBy = (field) => {
    router.get(route('entree-stocks.index'), {
        ...filters.value,
        sort: field,
        direction: filters.value.direction === 'asc' ? 'desc' : 'asc'
    }, {
        preserveState: true,
        replace: true,
    });
};

const toggleContextMenu = (id) => {
    contextMenuId.value = contextMenuId.value === id ? null : id;
};

const exportData = () => {
    // Implémentation de l'export
    console.log('Export des données');
};

const duplicateEntry = (entree) => {
    if (confirm(`Voulez-vous dupliquer l'entrée ${entree.numero_affichage} ?`)) {
        // Logique de duplication
        console.log('Duplication de:', entree);
    }
};

// Méthodes utilitaires (garder les mêmes que précédemment)
const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('fr-FR');
};

const formatTime = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
};

const formatCurrency = (amount) => {
    if (!amount) return '0,00 DH';
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'MAD' }).format(amount);
};

const calculateTotalQuantite = (entree) => {
    if (!entree.lignes_entree) return 0;
    return entree.lignes_entree.reduce((total, ligne) => total + parseFloat(ligne.quantite), 0);
};

const calculateTotalMontant = (entree) => {
    if (!entree.lignes_entree) return 0;
    return entree.lignes_entree.reduce((total, ligne) => total + parseFloat(ligne.prix_total), 0);
};

const getStatutLabel = (statut) => {
    const labels = {
        'attente_validation': 'En attente',
        'valide': 'Validé',
        'annule': 'Annulé'
    };
    return labels[statut] || statut;
};

const getStatutBadgeClass = (statut) => {
    const classes = {
        'attente_validation': 'bg-yellow-100 text-yellow-800 border border-yellow-200',
        'valide': 'bg-green-100 text-green-800 border border-green-200',
        'annule': 'bg-red-100 text-red-800 border border-red-200'
    };
    return classes[statut] || 'bg-gray-100 text-gray-800 border border-gray-200';
};

const getStatutIcon = (statut) => {
    const icons = {
        'attente_validation': ClockIcon,
        'valide': CheckBadgeIcon,
        'annule': XCircleIcon
    };
    return icons[statut] || ClockIcon;
};

const getStatusIconBg = (statut) => {
    const classes = {
        'attente_validation': 'bg-yellow-500',
        'valide': 'bg-green-500',
        'annule': 'bg-red-500'
    };
    return classes[statut] || 'bg-gray-500';
};

// Méthodes des modaux (garder les mêmes)
const openDetailModal = (entree) => {
    selectedEntree.value = entree;
    showDetailModal.value = true;
    contextMenuId.value = null;
};

const closeDetailModal = () => {
    showDetailModal.value = false;
    selectedEntree.value = null;
};

const openValidationModal = (entree) => {
    selectedEntree.value = entree;
    showValidationModal.value = true;
    contextMenuId.value = null;
};

const closeValidationModal = () => {
    showValidationModal.value = false;
};

const openAnnulationModal = (entree) => {
    selectedEntree.value = entree;
    showAnnulationModal.value = true;
    contextMenuId.value = null;
};

const closeAnnulationModal = () => {
    showAnnulationModal.value = false;
};

// Watch
watch(filters, (value) => {
    // Debounce pour éviter trop de requêtes
    clearTimeout(window.filterTimeout);
    window.filterTimeout = setTimeout(() => {
        applyFilters();
    }, 500);
}, { deep: true });

// Fermer le menu contextuel en cliquant ailleurs
document.addEventListener('click', (e) => {
    if (!e.target.closest('.relative')) {
        contextMenuId.value = null;
    }
});
</script>

<style scoped>
/* Styles d'animation supplémentaires */
.hover-lift:hover {
    transform: translateY(-2px);
}

.gradient-border {
    background: linear-gradient(white, white) padding-box,
                linear-gradient(45deg, #3B82F6, #8B5CF6) border-box;
    border: 1px solid transparent;
}

/* Animation de pulse pour les statuts */
@keyframes pulse-glow {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.pulse-glow {
    animation: pulse-glow 2s ease-in-out infinite;
}
</style>
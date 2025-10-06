<template>
    <AuthenticatedLayout>
        <Head title="Gestion des Entrées de Stock" />

        <div class="space-y-6">
            <!-- En-tête -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Entrées de Stock</h1>
                    <p class="text-gray-600">Gestion des entrées en stock</p>
                </div>
                <div class="flex space-x-3">
                    <Link
                        :href="route('entree-stocks.create')"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-2 transition-colors"
                    >
                        <PlusIcon class="h-5 w-5" />
                        Nouvelle entrée
                    </Link>
                </div>
            </div>

            <!-- Statistiques -->
            <section class="grid md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-gray-500">Total Entrées</div>
                    <div class="mt-2 flex items-center justify-between">
                        <div class="text-3xl font-bold text-gray-800">{{ stats?.total || 0 }}</div>
                        <DocumentTextIcon class="w-6 h-6 text-gray-600" />
                    </div>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-gray-500">En Attente</div>
                    <div class="mt-2 flex items-center justify-between">
                        <div class="text-3xl font-bold text-yellow-700">{{ stats?.attente_validation || 0 }}</div>
                        <ClockIcon class="w-6 h-6 text-yellow-600" />
                    </div>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-gray-500">Validées</div>
                    <div class="mt-2 flex items-center justify-between">
                        <div class="text-3xl font-bold text-green-700">{{ stats?.valide || 0 }}</div>
                        <CheckBadgeIcon class="w-6 h-6 text-green-600" />
                    </div>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-gray-500">Annulées</div>
                    <div class="mt-2 flex items-center justify-between">
                        <div class="text-3xl font-bold text-red-700">{{ stats?.annule || 0 }}</div>
                        <XCircleIcon class="w-6 h-6 text-red-600" />
                    </div>
                </div>
            </section>

            <!-- Filtres -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select v-model="filters.statut" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les statuts</option>
                            <option value="attente_validation">En attente</option>
                            <option value="valide">Validé</option>
                            <option value="annule">Annulé</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                        <input v-model="filters.date_debut" type="date" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                        <input v-model="filters.date_fin" type="date" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button
                        @click="resetFilters"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 mr-2 transition-colors"
                    >
                        Réinitialiser
                    </button>
                </div>
            </div>

            <!-- Liste des entrées de stock -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Historique des Entrées</h3>
                        <div class="text-sm text-gray-500">
                            {{ entreeStocks?.total || 0 }} résultat(s)
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Entrée</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Réception</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fournisseur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Entrée</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Articles</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantité Totale</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="entree in entreeStocks?.data || []" :key="entree.id" class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ entree.numero_affichage }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div v-if="entree.bon_reception" class="text-sm text-blue-600 font-medium">
                                        {{ entree.bon_reception.numero }}
                                    </div>
                                    <div v-else class="text-sm text-gray-400">
                                        Manuelle
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <img v-if="entree.fournisseur?.logo_url" :src="entree.fournisseur.logo_url" 
                                                class="h-8 w-8 rounded-full object-cover border border-gray-200">
                                            <div v-else class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <BuildingStorefrontIcon class="h-4 w-4 text-gray-400" />
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ entree.fournisseur?.nom_affichage || entree.fournisseur?.raison_sociale || 'Non spécifié' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(entree.date_entree) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ entree.lignes_entree?.length || 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                    {{ calculateTotalQuantite(entree) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="text-right font-medium">
                                        {{ formatCurrency(calculateTotalMontant(entree)) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getStatutBadgeClass(entree.statut)" 
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                        {{ getStatutLabel(entree.statut) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button
                                            @click="openDetailModal(entree)"
                                            class="text-blue-600 hover:text-blue-900 p-1 rounded-lg hover:bg-blue-50 transition-colors"
                                            title="Voir détails"
                                        >
                                            <EyeIcon class="h-4 w-4" />
                                        </button>
                                        <button
                                            v-if="entree.statut === 'attente_validation'"
                                            @click="openValidationModal(entree)"
                                            class="text-green-600 hover:text-green-900 p-1 rounded-lg hover:bg-green-50 transition-colors"
                                            title="Valider l'entrée"
                                        >
                                            <CheckIcon class="h-4 w-4" />
                                        </button>
                                        <button
                                            v-if="entree.statut === 'attente_validation'"
                                            @click="openAnnulationModal(entree)"
                                            class="text-red-600 hover:text-red-900 p-1 rounded-lg hover:bg-red-50 transition-colors"
                                            title="Annuler l'entrée"
                                        >
                                            <XMarkIcon class="h-4 w-4" />
                                        </button>
                                        <Link
                                            :href="route('entree-stocks.download-pdf', entree.id)"
                                            class="text-purple-600 hover:text-purple-900 p-1 rounded-lg hover:bg-purple-50 transition-colors"
                                            title="Télécharger PDF"
                                        >
                                            <DocumentArrowDownIcon class="h-4 w-4" />
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Message vide -->
                <div v-if="!entreeStocks?.data || entreeStocks.data.length === 0" class="text-center py-16">
                    <div class="text-gray-500">
                        <InboxIcon class="mx-auto h-20 w-20 text-gray-300" />
                        <h3 class="mt-4 text-xl font-medium text-gray-900">Aucune entrée de stock</h3>
                        <p class="mt-2 text-gray-500">
                            {{ filters.statut || filters.date_debut || filters.date_fin ? 
                               'Aucun résultat pour vos critères de recherche.' : 
                               'Commencez par créer votre première entrée de stock.' }}
                        </p>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="entreeStocks?.links && entreeStocks.links.length > 1" class="bg-white px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-gray-700">
                            Affichage de {{ entreeStocks.from }} à {{ entreeStocks.to }} sur {{ entreeStocks.total }} résultats
                        </div>
                        <div class="flex space-x-1">
                            <template v-for="link in entreeStocks.links" :key="link.label">
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    :class="[
                                        'px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                                        link.active 
                                            ? 'bg-blue-600 text-white border border-blue-600' 
                                            : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'
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

        <!-- Modal de Détail -->
        <Modal :show="showDetailModal" @close="closeDetailModal" max-width="6xl">
            <div class="bg-white p-6 rounded-lg max-h-[90vh] overflow-y-auto">
                <!-- En-tête du modal -->
                <div class="flex items-center justify-between mb-6 pb-4 border-b">
                    <div class="flex items-center gap-3">
                        <div :class="getStatusBannerClass(selectedEntree?.statut)" class="px-3 py-1 rounded-full text-sm font-medium">
                            {{ getStatutLabel(selectedEntree?.statut) }}
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">
                            Entrée de Stock {{ selectedEntree?.numero_affichage }}
                        </h2>
                    </div>
                    <button @click="closeDetailModal" class="text-gray-400 hover:text-gray-600">
                        <XMarkIcon class="h-6 w-6" />
                    </button>
                </div>

                <!-- Contenu du détail -->
                <div v-if="selectedEntree" class="space-y-6">
                    <!-- Bannière de statut -->
                    <div :class="getStatusBannerClass(selectedEntree.statut)" class="rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <component :is="getStatusIcon(selectedEntree.statut)" class="h-6 w-6" />
                                <div>
                                    <h3 class="font-semibold">{{ getStatusTitle(selectedEntree.statut) }}</h3>
                                    <p class="text-sm opacity-90">{{ getStatusDescription(selectedEntree.statut) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Informations principales -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Carte Informations Générales -->
                            <div class="bg-gray-50 rounded-lg border border-gray-200">
                                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                                    <h3 class="text-lg font-semibold text-gray-900">Informations Générales</h3>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-500 mb-1">Numéro Entrée</label>
                                                <p class="text-lg font-semibold text-gray-900">{{ selectedEntree.numero_affichage }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-500 mb-1">Date d'Entrée</label>
                                                <p class="text-gray-900">{{ formatDate(selectedEntree.date_entree) }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-500 mb-1">Créé par</label>
                                                <p class="text-gray-900">{{ selectedEntree.created_by?.name || 'Non spécifié' }}</p>
                                            </div>
                                        </div>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-500 mb-1">Bon de Réception</label>
                                                <div v-if="selectedEntree.bon_reception" class="flex items-center gap-2">
                                                    <DocumentTextIcon class="h-4 w-4 text-blue-500" />
                                                    <span class="text-blue-600 font-medium">
                                                        {{ selectedEntree.bon_reception.numero }}
                                                    </span>
                                                </div>
                                                <p v-else class="text-gray-500">Entrée manuelle</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-500 mb-1">Date de Création</label>
                                                <p class="text-gray-900">{{ formatDateTime(selectedEntree.created_at) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Carte Fournisseur -->
                            <div class="bg-gray-50 rounded-lg border border-gray-200">
                                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                                    <h3 class="text-lg font-semibold text-gray-900">Fournisseur</h3>
                                </div>
                                <div class="p-6">
                                    <div v-if="selectedEntree.fournisseur" class="flex items-start gap-4">
                                        <div class="flex-shrink-0">
                                            <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center border-2 border-white shadow-sm">
                                                <BuildingStorefrontIcon class="h-8 w-8 text-gray-400" />
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-lg font-semibold text-gray-900">
                                                {{ selectedEntree.fournisseur.raison_sociale || selectedEntree.fournisseur.nom }}
                                            </h4>
                                            <div class="mt-2 space-y-1 text-sm text-gray-600">
                                                <div v-if="selectedEntree.fournisseur.adresse" class="flex items-center gap-2">
                                                    <MapPinIcon class="h-4 w-4" />
                                                    <span>{{ selectedEntree.fournisseur.adresse }}</span>
                                                </div>
                                                <div v-if="selectedEntree.fournisseur.telephone" class="flex items-center gap-2">
                                                    <PhoneIcon class="h-4 w-4" />
                                                    <span>{{ selectedEntree.fournisseur.telephone }}</span>
                                                </div>
                                                <div v-if="selectedEntree.fournisseur.email" class="flex items-center gap-2">
                                                    <EnvelopeIcon class="h-4 w-4" />
                                                    <span>{{ selectedEntree.fournisseur.email }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="text-center py-8 text-gray-500">
                                        <BuildingStorefrontIcon class="mx-auto h-12 w-12 mb-3" />
                                        <p>Aucun fournisseur associé</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Carte Articles -->
                            <div class="bg-gray-50 rounded-lg border border-gray-200">
                                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-gray-900">Articles Reçus</h3>
                                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                                            {{ selectedEntree.lignes_entree?.length || 0 }} articles
                                        </span>
                                    </div>
                                </div>
                                <div class="overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Article</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Quantité</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Prix Unitaire</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">TVA</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="ligne in selectedEntree.lignes_entree" :key="ligne.id" class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4">
                                                    <div>
                                                        <div class="font-medium text-gray-900">
                                                            {{ ligne.article?.designation || 'Article non trouvé' }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            Réf: {{ ligne.article?.reference || 'N/A' }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                        {{ formatNumber(ligne.quantite) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-right text-gray-900">
                                                    {{ formatCurrency(ligne.prix_unitaire) }}
                                                </td>
                                                <td class="px-6 py-4 text-right text-gray-900">
                                                    {{ formatCurrency(ligne.montant_tva) }}
                                                </td>
                                                <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                                    {{ formatCurrency(ligne.prix_total) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="bg-gray-50">
                                            <tr>
                                                <td colspan="4" class="px-6 py-4 text-right font-semibold text-gray-900">Total HT</td>
                                                <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                                    {{ formatCurrency(calculateDetailTotalHT(selectedEntree)) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="px-6 py-4 text-right font-semibold text-gray-900">Total TVA</td>
                                                <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                                    {{ formatCurrency(calculateDetailTotalTVA(selectedEntree)) }}
                                                </td>
                                            </tr>
                                            <tr class="bg-gray-100">
                                                <td colspan="4" class="px-6 py-4 text-right font-bold text-gray-900 text-lg">Total TTC</td>
                                                <td class="px-6 py-4 text-right font-bold text-gray-900 text-lg">
                                                    {{ formatCurrency(calculateDetailTotalTTC(selectedEntree)) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar Résumé -->
                        <div class="space-y-6">
                            <!-- Carte Résumé -->
                            <div class="bg-gray-50 rounded-lg border border-gray-200">
                                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                                    <h3 class="text-lg font-semibold text-gray-900">Résumé</h3>
                                </div>
                                <div class="p-6 space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Quantité totale</span>
                                        <span class="font-semibold text-gray-900">{{ calculateDetailTotalQuantite(selectedEntree) }} unités</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Nombre d'articles</span>
                                        <span class="font-semibold text-gray-900">{{ selectedEntree.lignes_entree?.length || 0 }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Total HT</span>
                                        <span class="font-semibold text-gray-900">{{ formatCurrency(calculateDetailTotalHT(selectedEntree)) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Total TVA</span>
                                        <span class="font-semibold text-gray-900">{{ formatCurrency(calculateDetailTotalTVA(selectedEntree)) }}</span>
                                    </div>
                                    <div class="border-t pt-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg font-semibold text-gray-900">Total TTC</span>
                                            <span class="text-lg font-bold text-blue-600">{{ formatCurrency(calculateDetailTotalTTC(selectedEntree)) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Carte Notes -->
                            <div v-if="selectedEntree.notes" class="bg-gray-50 rounded-lg border border-gray-200">
                                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                                    <h3 class="text-lg font-semibold text-gray-900">Notes</h3>
                                </div>
                                <div class="p-6">
                                    <p class="text-gray-700 whitespace-pre-wrap">{{ selectedEntree.notes }}</p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="bg-gray-50 rounded-lg border border-gray-200">
                                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                                    <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                                </div>
                                <div class="p-6 space-y-3">
                                    <Link
                                        :href="route('entree-stocks.download-pdf', selectedEntree.id)"
                                        class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 flex items-center justify-center gap-2 transition-colors"
                                    >
                                        <DocumentArrowDownIcon class="h-5 w-5" />
                                        Télécharger PDF
                                    </Link>
                                    <button
                                        v-if="selectedEntree.statut === 'attente_validation'"
                                        @click="openValidationModal(selectedEntree)"
                                        class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center justify-center gap-2 transition-colors"
                                    >
                                        <CheckIcon class="h-5 w-5" />
                                        Valider l'entrée
                                    </button>
                                    <button
                                        v-if="selectedEntree.statut === 'attente_validation'"
                                        @click="openAnnulationModal(selectedEntree)"
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center justify-center gap-2 transition-colors"
                                    >
                                        <XMarkIcon class="h-5 w-5" />
                                        Annuler l'entrée
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Modal>

        <!-- Modal de Validation -->
        <Modal :show="showValidationModal" @close="closeValidationModal">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <CheckIcon class="h-6 w-6 text-green-600" />
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Valider l'Entrée de Stock
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Êtes-vous sûr de vouloir valider l'entrée de stock <strong>{{ selectedEntree?.numero_affichage }}</strong> ? Cette action :
                            </p>
                            <ul class="mt-3 text-sm text-gray-600 space-y-2">
                                <li class="flex items-center gap-2">
                                    <CheckCircleIcon class="h-4 w-4 text-green-500" />
                                    Marquera cette entrée comme validée
                                </li>
                                <li class="flex items-center gap-2">
                                    <CheckCircleIcon class="h-4 w-4 text-green-500" />
                                    Mettra à jour les stocks des articles concernés
                                </li>
                                <li class="flex items-center gap-2">
                                    <CheckCircleIcon class="h-4 w-4 text-green-500" />
                                    Enregistrera les mouvements de stock
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button
                    @click="validerEntree"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                >
                    Valider
                </button>
                <button
                    @click="closeValidationModal"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                >
                    Annuler
                </button>
            </div>
        </Modal>

        <!-- Modal d'Annulation -->
        <Modal :show="showAnnulationModal" @close="closeAnnulationModal">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <XMarkIcon class="h-6 w-6 text-red-600" />
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Annuler l'Entrée de Stock
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Êtes-vous sûr de vouloir annuler l'entrée de stock <strong>{{ selectedEntree?.numero_affichage }}</strong> ? Cette action :
                            </p>
                            <ul class="mt-3 text-sm text-gray-600 space-y-2">
                                <li class="flex items-center gap-2">
                                    <ExclamationTriangleIcon class="h-4 w-4 text-red-500" />
                                    Marquera cette entrée comme annulée
                                </li>
                                <li class="flex items-center gap-2">
                                    <ExclamationTriangleIcon class="h-4 w-4 text-red-500" />
                                    N'affectera pas les stocks des articles
                                </li>
                                <li class="flex items-center gap-2">
                                    <ExclamationTriangleIcon class="h-4 w-4 text-red-500" />
                                    Ne pourra pas être annulée facilement
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button
                    @click="annulerEntree"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                >
                    Annuler l'entrée
                </button>
                <button
                    @click="closeAnnulationModal"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                >
                    Retour
                </button>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import { ref, watch } from 'vue';
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
    MapPinIcon,
    PhoneIcon,
    EnvelopeIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon
} from '@heroicons/vue/24/outline';

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

// États
const filters = ref({
    statut: props.filters?.statut || '',
    date_debut: props.filters?.date_debut || '',
    date_fin: props.filters?.date_fin || '',
});

const showDetailModal = ref(false);
const showValidationModal = ref(false);
const showAnnulationModal = ref(false);
const selectedEntree = ref(null);

// Watch pour les filtres
watch(filters, (value) => {
    router.get(route('entree-stocks.index'), value, {
        preserveState: true,
        replace: true,
    });
}, { deep: true });

// Méthodes utilitaires
const resetFilters = () => {
    filters.value = {
        statut: '',
        date_debut: '',
        date_fin: '',
    };
};

const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('fr-FR');
};

const formatDateTime = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleString('fr-FR');
};

const formatCurrency = (amount) => {
    if (!amount) return '0,00 DH';
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'MAD' }).format(amount);
};

const formatNumber = (number) => {
    return new Intl.NumberFormat('fr-FR').format(number);
};

const calculateTotalQuantite = (entree) => {
    if (!entree.lignes_entree) return 0;
    return entree.lignes_entree.reduce((total, ligne) => total + parseFloat(ligne.quantite), 0);
};

const calculateTotalMontant = (entree) => {
    if (!entree.lignes_entree) return 0;
    return entree.lignes_entree.reduce((total, ligne) => total + parseFloat(ligne.prix_total), 0);
};

// Méthodes pour les détails
const calculateDetailTotalQuantite = (entree) => {
    if (!entree?.lignes_entree) return 0;
    return entree.lignes_entree.reduce((total, ligne) => total + parseFloat(ligne.quantite), 0);
};

const calculateDetailTotalHT = (entree) => {
    if (!entree?.lignes_entree) return 0;
    return entree.lignes_entree.reduce((total, ligne) => 
        total + (parseFloat(ligne.quantite) * parseFloat(ligne.prix_unitaire)), 0);
};

const calculateDetailTotalTVA = (entree) => {
    if (!entree?.lignes_entree) return 0;
    return entree.lignes_entree.reduce((total, ligne) => total + parseFloat(ligne.montant_tva), 0);
};

const calculateDetailTotalTTC = (entree) => {
    if (!entree?.lignes_entree) return 0;
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
        'attente_validation': 'bg-yellow-100 text-yellow-800',
        'valide': 'bg-green-100 text-green-800',
        'annule': 'bg-red-100 text-red-800'
    };
    return classes[statut] || 'bg-gray-100 text-gray-800';
};

const getStatusBannerClass = (statut) => {
    const classes = {
        'attente_validation': 'bg-yellow-50 border border-yellow-200 text-yellow-800',
        'valide': 'bg-green-50 border border-green-200 text-green-800',
        'annule': 'bg-red-50 border border-red-200 text-red-800'
    };
    return classes[statut] || 'bg-gray-50 border border-gray-200 text-gray-800';
};

const getStatusIcon = (statut) => {
    const icons = {
        'attente_validation': ClockIcon,
        'valide': CheckBadgeIcon,
        'annule': XCircleIcon
    };
    return icons[statut] || ClockIcon;
};

const getStatusTitle = (statut) => {
    const titles = {
        'attente_validation': 'En attente de validation',
        'valide': 'Entrée validée',
        'annule': 'Entrée annulée'
    };
    return titles[statut] || 'Statut inconnu';
};

const getStatusDescription = (statut) => {
    const descriptions = {
        'attente_validation': 'Cette entrée est en attente de validation par un responsable.',
        'valide': 'Cette entrée a été validée et les stocks ont été mis à jour.',
        'annule': 'Cette entrée a été annulée et ne sera pas prise en compte dans les stocks.'
    };
    return descriptions[statut] || '';
};

// Méthodes des modaux
const openDetailModal = (entree) => {
    selectedEntree.value = entree;
    showDetailModal.value = true;
};

const closeDetailModal = () => {
    showDetailModal.value = false;
    selectedEntree.value = null;
};

const openValidationModal = (entree) => {
    selectedEntree.value = entree;
    showValidationModal.value = true;
};

const closeValidationModal = () => {
    showValidationModal.value = false;
};

const openAnnulationModal = (entree) => {
    selectedEntree.value = entree;
    showAnnulationModal.value = true;
};

const closeAnnulationModal = () => {
    showAnnulationModal.value = false;
};

// Actions
const validerEntree = () => {
    if (!selectedEntree.value) return;
    
    router.put(route('entree-stocks.update', selectedEntree.value.id), {
        statut: 'valide'
    }, {
        preserveScroll: true,
        onSuccess: () => {
            closeValidationModal();
            closeDetailModal();
            // Recharger les données
            router.reload({ only: ['entreeStocks', 'stats'] });
        }
    });
};

const annulerEntree = () => {
    if (!selectedEntree.value) return;
    
    router.put(route('entree-stocks.update', selectedEntree.value.id), {
        statut: 'annule'
    }, {
        preserveScroll: true,
        onSuccess: () => {
            closeAnnulationModal();
            closeDetailModal();
            // Recharger les données
            router.reload({ only: ['entreeStocks', 'stats'] });
        }
    });
};
</script>

<style scoped>
/* Styles pour améliorer l'apparence des modaux */
.max-h-\[90vh\] {
    max-height: 90vh;
}
</style>
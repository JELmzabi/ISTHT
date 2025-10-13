<template>
    <AuthenticatedLayout>
        <Head title="Gestion des Bons de Réception" />

        <div class="space-y-6">
            <!-- En-tête avec statistiques -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-700 rounded-2xl p-6 text-white">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <h1 class="text-3xl md:text-4xl font-bold mb-2">Bons de Réception</h1>
                        <p class="text-blue-100 text-lg opacity-90">Gestion complète des réceptions de marchandises</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <button
                            @click="showCreateModal = true"
                            class="bg-white text-blue-600 px-6 py-3 rounded-xl hover:bg-blue-50 flex items-center justify-center gap-3 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl"
                        >
                            <PlusIcon class="h-5 w-5" />
                            Nouvelle Réception
                        </button>
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
                            <p class="text-gray-600 text-sm font-medium">Total Réceptions</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats?.total || 0 }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-xl">
                            <DocumentTextIcon class="h-8 w-8 text-blue-600" />
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Réceptions Complètes</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ stats?.complets || 0 }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-xl">
                            <CheckBadgeIcon class="h-8 w-8 text-green-600" />
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Réceptions Partielles</p>
                            <p class="text-3xl font-bold text-orange-600 mt-2">{{ stats?.partiels || 0 }}</p>
                        </div>
                        <div class="p-3 bg-orange-100 rounded-xl">
                            <ClipboardDocumentListIcon class="h-8 w-8 text-orange-600" />
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Avec Facture</p>
                            <p class="text-3xl font-bold text-purple-600 mt-2">{{ stats?.avec_facture || 0 }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-xl">
                            <DocumentChartBarIcon class="h-8 w-8 text-purple-600" />
                        </div>
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type Réception</label>
                            <select 
                                v-model="filters.type_reception" 
                                class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                            >
                                <option value="">Tous les types</option>
                                <option value="complet">🟢 Complets</option>
                                <option value="partiel">🟡 Partiels</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                            <select 
                                v-model="filters.statut" 
                                class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            >
                                <option value="">Tous les statuts</option>
                                <option value="valide">✅ Validé</option>
                                <option value="brouillon">📝 Brouillon</option>
                                <option value="annule">❌ Annulé</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Responsable</label>
                            <select 
                                v-model="filters.responsable_reception_id" 
                                class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            >
                                <option value="">Tous les responsables</option>
                                <option v-for="magasinier in magasiniers" :key="magasinier.id" :value="magasinier.id">
                                    {{ magasinier.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                            <div class="relative">
                                <input 
                                    v-model="filters.search" 
                                    type="text" 
                                    placeholder="N° réception, fournisseur..."
                                    class="w-full border border-gray-300 rounded-xl p-3 pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                >
                                <MagnifyingGlassIcon class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" />
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mt-6">
                        <div class="text-sm text-gray-500">
                            {{ bonReceptions?.total || 0 }} réception(s) trouvée(s)
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

            <!-- Commandes en attente -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <ClockIcon class="h-6 w-6 text-blue-600" />
                                Commandes en Attente de Livraison
                            </h3>
                            <p class="text-gray-600 text-sm mt-1">Commandes validées en attente de réception complète</p>
                        </div>
                        <div class="text-sm text-blue-600 bg-white px-3 py-1 rounded-full border">
                            {{ commandesEnAttente?.length || 0 }} commande(s)
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    N° Commande
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
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    Reçue
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    Reste
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    Progression
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="commande in commandesEnAttente" :key="commande.id" class="hover:bg-blue-50/30 transition-all duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-900">{{ commande.reference }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ formatDate(commande.date_mise_ligne) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center border-2 border-white shadow-sm">
                                                <BuildingStorefrontIcon class="h-5 w-5 text-blue-600" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">
                                                {{ commande.fournisseur?.nom_affichage || commande.fournisseur?.raison_sociale || 'Non spécifié' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ commande.fournisseur?.contact || '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ formatDate(commande.date_mise_ligne) }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                        <CubeIcon class="h-4 w-4 mr-1" />
                                        {{ commande.articles?.length || 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-semibold text-gray-900">
                                    {{ commande.quantite_totale_commandee || 0 }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-green-600 font-semibold">
                                    {{ commande.quantite_totale_recue || 0 }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-orange-100 text-orange-800 border border-orange-200">
                                        {{ commande.reste_a_recevoir || 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div 
                                                class="h-2.5 rounded-full transition-all duration-300" 
                                                :class="getProgressBarClass(commande.pourcentage_recu)"
                                                :style="{ width: Math.min(commande.pourcentage_recu || 0, 100) + '%' }"
                                            ></div>
                                        </div>
                                        <span class="ml-2 text-xs font-medium text-gray-700">
                                            {{ Math.min(commande.pourcentage_recu || 0, 100) }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button
                                        @click="openCreateForm(commande.id)"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200 font-medium text-sm"
                                    >
                                        <PlusIcon class="h-4 w-4 mr-1" />
                                        Créer réception
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="!commandesEnAttente || commandesEnAttente.length === 0" class="text-center py-16">
                    <DocumentCheckIcon class="mx-auto h-16 w-16 text-green-500 mb-4" />
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Toutes les commandes sont livrées</h3>
                    <p class="text-gray-500">Aucune commande en attente de livraison.</p>
                </div>
            </div>

            <!-- Liste des bons de réception -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <QueueListIcon class="h-6 w-6 text-blue-600" />
                            Historique des Réceptions
                        </h3>
                        <div class="flex items-center gap-4">
                            <div class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full border">
                                {{ bonReceptions?.total || 0 }} résultat(s)
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    N° Réception
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    Commande
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    Fournisseur
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    Date
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    Type
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-100/50">
                                    Documents
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
                                v-for="reception in bonReceptions?.data || []" 
                                :key="reception.id" 
                                class="hover:bg-blue-50/30 transition-all duration-200 group"
                            >
                                <!-- Numéro Réception -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div :class="getStatusIconBg(reception.statut)" class="p-2 rounded-lg">
                                            <DocumentTextIcon class="h-5 w-5 text-white" />
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 group-hover:text-blue-700 transition-colors">
                                                {{ reception.numero_affichage || reception.numero }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Par {{ reception.created_by?.name || 'Système' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Commande -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div v-if="reception.bon_commande" class="text-blue-600 font-medium">
                                        {{ reception.bon_commande.reference }}
                                    </div>
                                    <div v-else class="text-gray-400 text-sm">
                                        Sans commande
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
                                                {{ reception.fournisseur?.nom_affichage || reception.fournisseur?.raison_sociale || 'Non spécifié' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ reception.fournisseur?.ville || '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Date -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">
                                        {{ formatDate(reception.date_reception) }}
                                    </div>
                                </td>

                                <!-- Type -->
                                <td class="px-6 py-4 text-center">
                                    <span :class="getTypeBadgeClass(reception.type_reception)" 
                                          class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold transition-all duration-200">
                                        <component :is="getTypeIcon(reception.type_reception)" class="h-4 w-4 mr-1.5" />
                                        {{ getTypeLabel(reception.type_reception) }}
                                    </span>
                                </td>

                                <!-- Documents -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button 
                                            v-if="reception.fichier_bonlivraison" 
                                            @click="downloadBonLivraison(reception)"
                                            class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-xl transition-all duration-200 group/tooltip relative"
                                            title="Télécharger BL"
                                        >
                                            <DocumentTextIcon class="h-5 w-5" />
                                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded py-1 px-2 opacity-0 group-hover/tooltip:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                Bon de Livraison
                                            </div>
                                        </button>
                                        <button v-else class="p-2 text-gray-300" title="Aucun BL">
                                            <DocumentTextIcon class="h-5 w-5" />
                                        </button>
                                        
                                        <button 
                                            v-if="reception.fichier_facture" 
                                            @click="downloadFacture(reception)"
                                            class="p-2 text-green-600 hover:text-green-800 hover:bg-green-100 rounded-xl transition-all duration-200 group/tooltip relative"
                                            title="Télécharger Facture"
                                        >
                                            <DocumentChartBarIcon class="h-5 w-5" />
                                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded py-1 px-2 opacity-0 group-hover/tooltip:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                Facture
                                            </div>
                                        </button>
                                        <button v-else class="p-2 text-gray-300" title="Aucune facture">
                                            <DocumentChartBarIcon class="h-5 w-5" />
                                        </button>
                                    </div>
                                </td>

                                <!-- Statut -->
                                <td class="px-6 py-4 text-center">
                                    <span :class="getStatutBadgeClass(reception.statut)" 
                                          class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold transition-all duration-200">
                                        <component :is="getStatutIcon(reception.statut)" class="h-4 w-4 mr-1.5" />
                                        {{ getStatutLabel(reception.statut) }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex justify-center items-center gap-1">
                                        <!-- Voir détails -->
                                        <Link
                                            :href="route('bon-receptions.show-details', reception.id)"
                                            class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-xl transition-all duration-200 group/tooltip relative"
                                            title="Voir détails complets"
                                        >
                                            <EyeIcon class="h-5 w-5" />
                                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded py-1 px-2 opacity-0 group-hover/tooltip:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                Voir détails
                                            </div>
                                        </Link>

                                        <!-- PDF -->
                                        <button
                                            @click="downloadPdf(reception)"
                                            class="p-2 text-green-600 hover:text-green-800 hover:bg-green-100 rounded-xl transition-all duration-200 group/tooltip relative"
                                            title="Télécharger PDF"
                                        >
                                            <DocumentArrowDownIcon class="h-5 w-5" />
                                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded py-1 px-2 opacity-0 group-hover/tooltip:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                Télécharger PDF
                                            </div>
                                        </button>

                                        <!-- Modifier -->
                                        <Link
                                            v-if="reception.statut === 'brouillon'"
                                            :href="route('bon-receptions.edit', reception.id)"
                                            class="p-2 text-orange-600 hover:text-orange-800 hover:bg-orange-100 rounded-xl transition-all duration-200 group/tooltip relative"
                                            title="Modifier"
                                        >
                                            <PencilIcon class="h-5 w-5" />
                                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded py-1 px-2 opacity-0 group-hover/tooltip:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                Modifier
                                            </div>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- État vide -->
                <div v-if="!bonReceptions?.data || bonReceptions.data.length === 0" class="text-center py-16">
                    <div class="text-gray-400">
                        <TruckIcon class="mx-auto h-24 w-24 text-gray-300 mb-4" />
                        <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun bon de réception</h3>
                        <p class="text-gray-500 max-w-md mx-auto mb-6">
                            {{ filters.search || filters.type_reception || filters.statut || filters.responsable_reception_id ? 
                               'Aucun résultat ne correspond à vos critères de recherche.' : 
                               'Commencez par créer votre première réception pour gérer vos livraisons.' }}
                        </p>
                        <button 
                            @click="showCreateModal = true"
                            class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-colors"
                        >
                            <PlusIcon class="h-5 w-5" />
                            Créer une réception
                        </button>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="bonReceptions?.links && bonReceptions.links.length > 3" class="bg-white px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-gray-700">
                            Affichage de {{ bonReceptions.from }} à {{ bonReceptions.to }} sur {{ bonReceptions.total }} réceptions
                        </div>
                        <div class="flex items-center gap-1">
                            <template v-for="link in bonReceptions.links" :key="link.label">
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

       <!-- Modal de création -->
<!-- Modal de création -->
<div v-if="showCreateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-6xl shadow-lg rounded-md bg-white">
        <!-- En-tête du modal -->
        <div class="flex items-center justify-between pb-4 border-b">
            <h3 class="text-xl font-semibold text-gray-900">
                Nouveau Bon de Réception
            </h3>
            <button 
                @click="closeCreateModal"
                class="text-gray-400 hover:text-gray-600 transition-colors"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Contenu du formulaire -->
        <div class="mt-4 max-h-[calc(100vh-300px)] overflow-y-auto">
            <form @submit.prevent="submitReceptionForm" class="space-y-6">
                <!-- Sélection de la commande -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Commande à réceptionner *
                        </label>
                        <select 
                            v-model="form.bon_commande_id"
                            @change="onCommandeSelected"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                            <option value="">Sélectionner une commande</option>
                            <option 
                                v-for="commande in commandesEnAttente" 
                                :key="commande.id" 
                                :value="commande.id"
                                :disabled="!hasArticlesToReceive(commande)"
                            >
                                {{ commande.reference }} - {{ commande.fournisseur?.raison_sociale }} 
                                ({{ commande.pourcentage_recu }}% reçu)
                                <span v-if="!hasArticlesToReceive(commande)"> - Complètement reçue</span>
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Date de réception *
                        </label>
                        <input 
                            type="date" 
                            v-model="form.date_reception"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                    </div>
                </div>

                <!-- Responsable -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Responsable de la réception *
                    </label>
                    <select 
                        v-model="form.responsable_reception_id"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                        <option value="">Sélectionner un responsable</option>
                        <option 
                            v-for="magasinier in magasiniers" 
                            :key="magasinier.id" 
                            :value="magasinier.id"
                        >
                            {{ magasinier.name }} - {{ magasinier.email }}
                        </option>
                    </select>
                </div>

                <!-- Informations de la commande sélectionnée -->
                <div v-if="selectedCommande" class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-blue-800 mb-2">Informations de la commande</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="font-medium">Référence:</span>
                            <p>{{ selectedCommande.reference }}</p>
                        </div>
                        <div>
                            <span class="font-medium">Fournisseur:</span>
                            <p>{{ selectedCommande.fournisseur?.raison_sociale }}</p>
                        </div>
                        <div>
                            <span class="font-medium">Date commande:</span>
                            <p>{{ formatDate(selectedCommande.date_commande) }}</p>
                        </div>
                        <div>
                            <span class="font-medium">Progression:</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div 
                                        class="bg-green-600 h-2 rounded-full" 
                                        :style="{ width: selectedCommande.pourcentage_recu + '%' }"
                                    ></div>
                                </div>
                                <span class="text-xs font-medium">{{ selectedCommande.pourcentage_recu }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau des articles de la commande -->
                <div v-if="selectedCommande" class="border rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b">
                        <div class="flex justify-between items-center">
                            <h4 class="font-semibold text-gray-800">Articles de la commande</h4>
                            <span class="text-sm text-gray-600">
                                {{ getArticlesWithRemainingQuantity() }} article(s) à recevoir
                            </span>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Article
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantité Commandée
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Déjà Reçue
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Reste à Recevoir
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantité Reçue *
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Prix Unitaire
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statut
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr 
                                    v-for="(article, index) in selectedCommande.articles" 
                                    :key="article.article_id"
                                    :class="{
                                        'bg-green-50': article.reste_a_recevoir <= 0,
                                        'bg-yellow-50': article.reste_a_recevoir > 0 && getQuantiteRecue(article.article_id) > 0,
                                        'bg-red-50': hasReceptionError(article.article_id)
                                    }"
                                >
                                    <!-- Désignation Article -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div>
                                            <p class="font-medium text-sm text-gray-900">{{ article.article.designation }}</p>
                                            <p class="text-gray-500 text-xs">{{ article.article.reference }}</p>
                                            <p v-if="article.article.unite_mesure" class="text-gray-400 text-xs">
                                                Unité: {{ article.article.unite_mesure }}
                                            </p>
                                        </div>
                                    </td>

                                    <!-- Quantité Commandée -->
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-center">
                                        <span class="font-semibold">{{ article.quantite_commandee }}</span>
                                    </td>

                                    <!-- Déjà Reçue -->
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-center">
                                        <span class="text-blue-600">{{ article.quantite_deja_recue || 0 }}</span>
                                    </td>

                                    <!-- Reste à Recevoir -->
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <span 
                                            class="text-sm font-semibold px-2 py-1 rounded-full"
                                            :class="{
                                                'bg-green-100 text-green-800': article.reste_a_recevoir <= 0,
                                                'bg-orange-100 text-orange-800': article.reste_a_recevoir > 0
                                            }"
                                        >
                                            {{ article.reste_a_recevoir }}
                                        </span>
                                    </td>

                                    <!-- Quantité Reçue (Saisie) -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <input 
                                                type="number" 
                                                :min="0"
                                                :max="article.reste_a_recevoir"
                                                step="0.01"
                                                v-model="form.lignes_reception[index].quantite_receptionnee"
                                                @change="validateQuantite(article, index)"
                                                :disabled="article.reste_a_recevoir <= 0"
                                                class="w-24 border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-center"
                                                :class="{
                                                    'border-red-500 bg-red-50': hasReceptionError(article.article_id),
                                                    'border-gray-300': !hasReceptionError(article.article_id),
                                                    'bg-gray-100 text-gray-400': article.reste_a_recevoir <= 0
                                                }"
                                                placeholder="0.00"
                                            >
                                            <span class="text-xs text-gray-500">
                                                max: {{ article.reste_a_recevoir }}
                                            </span>
                                        </div>
                                        <div v-if="hasReceptionError(article.article_id)" class="text-red-500 text-xs mt-1">
                                            Quantité maximale: {{ article.reste_a_recevoir }}
                                        </div>
                                    </td>

                                    <!-- Prix Unitaire -->
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-center">
                                        {{ formatPrice(article.prix_unitaire_ht) }}
                                    </td>

                                    <!-- Statut -->
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <span 
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                            :class="{
                                                'bg-green-100 text-green-800': article.reste_a_recevoir <= 0,
                                                'bg-yellow-100 text-yellow-800': article.reste_a_recevoir > 0 && article.quantite_deja_recue > 0,
                                                'bg-gray-100 text-gray-800': article.reste_a_recevoir > 0 && article.quantite_deja_recue === 0
                                            }"
                                        >
                                            <template v-if="article.reste_a_recevoir <= 0">
                                                Complet
                                            </template>
                                            <template v-else-if="article.quantite_deja_recue > 0">
                                                Partiel
                                            </template>
                                            <template v-else>
                                                En attente
                                            </template>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Section documents avec règles conditionnelles -->
                <div class="border rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b">
                        <h4 class="font-semibold text-gray-800">Documents</h4>
                        <p class="text-sm text-gray-600 mt-1">
                            Le bon de livraison est optionnel. La facture est autorisée uniquement pour les réceptions complètes.
                        </p>
                    </div>
                    <div class="p-4 space-y-4">
                        <!-- Bon de livraison (toujours disponible) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Bon de livraison (optionnel)
                            </label>
                            <div class="flex items-center space-x-4">
                                <input 
                                    type="file" 
                                    @change="onFileSelected('fichier_bonlivraison', $event)"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="flex-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                >
                                <span class="text-xs text-gray-500 whitespace-nowrap">PDF, JPG, PNG (max 5MB)</span>
                            </div>
                        </div>

                        <!-- Facture (seulement si réception complète) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Facture
                                <span 
                                    v-if="!isReceptionComplete"
                                    class="ml-2 inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800"
                                >
                                    Disponible seulement pour réception complète
                                </span>
                            </label>
                            <div class="flex items-center space-x-4">
                                <input 
                                    type="file" 
                                    @change="onFileSelected('fichier_facture', $event)"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    :disabled="!isReceptionComplete"
                                    class="flex-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 disabled:file:bg-gray-100 disabled:file:text-gray-400 disabled:cursor-not-allowed"
                                >
                                <span 
                                    class="text-xs whitespace-nowrap"
                                    :class="isReceptionComplete ? 'text-gray-500' : 'text-gray-400'"
                                >
                                    PDF, JPG, PNG (max 5MB)
                                </span>
                            </div>
                            <p v-if="!isReceptionComplete" class="text-xs text-orange-600 mt-1">
                                ⓘ La facture ne peut être ajoutée que lorsque tous les articles sont complètement reçus.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Notes et observations
                    </label>
                    <textarea 
                        v-model="form.notes"
                        rows="3"
                        placeholder="Observations sur la réception, état des marchandises, remarques sur les quantités reçues, etc."
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    ></textarea>
                </div>

                <!-- Résumé et indicateurs -->
                <div v-if="selectedCommande" class="bg-gray-50 p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-800 mb-3">Résumé de la réception</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <!-- Articles avec reste -->
                        <div class="text-center p-3 bg-white rounded border">
                            <div class="text-2xl font-bold text-blue-600">{{ getArticlesWithRemainingQuantity() }}</div>
                            <div class="text-xs text-gray-600">Articles à recevoir</div>
                        </div>

                        <!-- Quantité totale reçue -->
                        <div class="text-center p-3 bg-white rounded border">
                            <div class="text-2xl font-bold text-green-600">{{ getTotalQuantiteRecue() }}</div>
                            <div class="text-xs text-gray-600">Quantité reçue</div>
                        </div>

                        <!-- Type de réception -->
                        <div class="text-center p-3 bg-white rounded border">
                            <div 
                                class="text-lg font-bold"
                                :class="getReceptionTypeClass()"
                            >
                                {{ getReceptionType() }}
                            </div>
                            <div class="text-xs text-gray-600">Type de réception</div>
                        </div>

                        <!-- Nouvelle progression -->
                        <div class="text-center p-3 bg-white rounded border">
                            <div class="text-2xl font-bold text-purple-600">{{ calculateNewProgression() }}%</div>
                            <div class="text-xs text-gray-600">Progression totale</div>
                        </div>
                    </div>

                    <!-- Barre de progression détaillée -->
                    <div class="mt-4">
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            <span>Avant: {{ selectedCommande.pourcentage_recu }}%</span>
                            <span>Après: {{ calculateNewProgression() }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div 
                                class="bg-gradient-to-r from-blue-500 to-green-500 h-3 rounded-full transition-all duration-500" 
                                :style="{ width: calculateNewProgression() + '%' }"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Messages d'erreur -->
                <div v-if="formErrors.length > 0" class="bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <h4 class="text-red-800 font-semibold">Erreurs à corriger</h4>
                    </div>
                    <ul class="mt-2 text-red-700 text-sm list-disc list-inside">
                        <li v-for="error in formErrors" :key="error">{{ error }}</li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button 
                        type="button"
                        @click="closeCreateModal"
                        class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                    >
                        Annuler
                    </button>
                    <button 
                        type="submit"
                        :disabled="isSubmitting || !isFormValid"
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                        <span v-if="isSubmitting">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Création en cours...
                        </span>
                        <span v-else>
                            Créer le bon de réception
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { 
    PlusIcon,
    DocumentTextIcon,
    CheckBadgeIcon,
    ClipboardDocumentListIcon,
    DocumentChartBarIcon,
    DocumentArrowDownIcon,
    MagnifyingGlassIcon,
    EyeIcon,
    PencilIcon,
    BuildingStorefrontIcon,
    DocumentCheckIcon,
    TruckIcon,
    XMarkIcon,
    FunnelIcon,
    ArrowPathIcon,
    QueueListIcon,
    ClockIcon,
    CubeIcon
} from '@heroicons/vue/24/outline';

// /////////////////////////////////////////
// Dans votre composant Vue.js - CORRECTIONS
const showCreateModal = ref(false);
const isSubmitting = ref(false);
const formErrors = ref([]);
const selectedCommande = ref(null);

const form = ref({
    bon_commande_id: '',
    date_reception: new Date().toISOString().split('T')[0],
    responsable_reception_id: '',
    lignes_reception: [],
    notes: '',
    fichier_bonlivraison: null,
    fichier_facture: null
});

// Ouvrir le modal avec une commande spécifique
const openCreateForm = (commandeId) => {
    form.value.bon_commande_id = commandeId;
    showCreateModal.value = true;
    onCommandeSelected(); // Charger automatiquement les détails
};

// Quand une commande est sélectionnée - CORRIGÉ
const onCommandeSelected = async () => {
    if (!form.value.bon_commande_id) {
        selectedCommande.value = null;
        form.value.lignes_reception = [];
        return;
    }

    try {
        // Afficher un indicateur de chargement
        formErrors.value = ['Chargement des détails de la commande...'];
        
        const response = await axios.get(`/api/commandes/${form.value.bon_commande_id}/details`);
        
        if (response.data.success) {
            selectedCommande.value = response.data.commande;
            
            // Initialiser les lignes de réception avec 0 par défaut
            form.value.lignes_reception = selectedCommande.value.articles.map(article => ({
                article_id: article.article_id,
                quantite_receptionnee: 0
            }));
            
            formErrors.value = [];
        } else {
            throw new Error(response.data.message);
        }
    } catch (error) {
        console.error('Erreur chargement commande:', error);
        selectedCommande.value = null;
        form.value.lignes_reception = [];
        formErrors.value = ['Erreur lors du chargement de la commande: ' + (error.response?.data?.message || error.message)];
    }
};

// Validation de la quantité - CORRIGÉ
// Dans la section methods du composant Vue
const validateQuantite = (article, index) => {
    const quantite = parseFloat(form.value.lignes_reception[index].quantite_receptionnee) || 0;
    
    if (quantite < 0) {
        form.value.lignes_reception[index].quantite_receptionnee = 0;
        return;
    }
    
    // CORRECTION : Conversion explicite en nombre
    const resteARecevoir = parseFloat(article.reste_a_recevoir) || 0;
    
    if (quantite > resteARecevoir) {
        form.value.lignes_reception[index].quantite_receptionnee = resteARecevoir;
    }
    
    // Forcer la mise à jour pour le computed isReceptionComplete
    form.value.lignes_reception = [...form.value.lignes_reception];
};

// Soumission du formulaire - CORRIGÉ
const submitReceptionForm = async () => {
    if (!isFormValid.value) {
        formErrors.value = ['Veuillez corriger les erreurs dans le formulaire'];
        return;
    }

    // Validation finale de la facture
    if (form.value.fichier_facture && !isReceptionComplete.value) {
        formErrors.value = ['La facture ne peut être ajoutée que pour une réception complète'];
        return;
    }

    isSubmitting.value = true;
    formErrors.value = [];

    try {
        const formData = new FormData();
        
        // Ajouter les champs simples
        formData.append('bon_commande_id', form.value.bon_commande_id);
        formData.append('date_reception', form.value.date_reception);
        formData.append('responsable_reception_id', form.value.responsable_reception_id);
        formData.append('notes', form.value.notes || '');
        
        // Ajouter les lignes de réception (seulement celles avec quantité > 0)
        let ligneIndex = 0;
        form.value.lignes_reception.forEach((ligne) => {
            const quantite = parseFloat(ligne.quantite_receptionnee) || 0;
            if (quantite > 0) {
                formData.append(`lignes_reception[${ligneIndex}][article_id]`, ligne.article_id);
                formData.append(`lignes_reception[${ligneIndex}][quantite_receptionnee]`, quantite);
                ligneIndex++;
            }
        });
        
        // Vérifier qu'il y a au moins une ligne avec quantité > 0
        if (ligneIndex === 0) {
            throw new Error('Au moins une quantité doit être supérieure à 0');
        }
        
        // Ajouter les fichiers
        if (form.value.fichier_bonlivraison) {
            formData.append('fichier_bonlivraison', form.value.fichier_bonlivraison);
        }
        if (form.value.fichier_facture && isReceptionComplete.value) {
            formData.append('fichier_facture', form.value.fichier_facture);
        }

        // Utiliser Inertia pour la soumission
        await router.post(route('bon-receptions.store'), formData, {
            forceFormData: true,
            onSuccess: () => {
                closeCreateModal();
                showNotification('Bon de réception créé avec succès', 'success');
            },
            onError: (errors) => {
                formErrors.value = Object.values(errors).flat();
            }
        });
        
    } catch (error) {
        console.error('Erreur création bon réception:', error);
        formErrors.value = ['Une erreur est survenue lors de la création: ' + error.message];
    } finally {
        isSubmitting.value = false;
    }
};

// Recharger les données
const reloadData = async () => {
    await router.reload({ only: ['bonReceptions', 'commandesEnAttente', 'stats'] });
};

// Afficher une notification
const showNotification = (message, type = 'success') => {
    // Implémentez votre système de notifications
    console.log(`${type}: ${message}`);
};

// ////////////////////////////////////////////////////////////////////////////////////////
// Dans votre composant Vue.js


// Computed pour vérifier si la réception est complète
const isReceptionComplete = computed(() => {
    // if (!selectedCommande.value) return false;
    
    // console.log(selectedCommande.value);
    
    // // Vérifier si tous les articles sont complètement reçus
    // return selectedCommande.value.articles.every(article => {
    //     const ligneReception = form.value.lignes_reception.find(
    //         l => l.article_id === article.article_id
    //     );
    //     const quantiteRecue = parseFloat(ligneReception?.quantite_receptionnee) || 0;
        
    //     // La réception est complète si la quantité reçue + déjà reçue = quantité commandée
    //     return (quantiteRecue + (article.quantite_deja_recue || 0)) >= article.quantite_commandee;
    // });
    return true;
});

// Vérifier si une commande a des articles à recevoir
const hasArticlesToReceive = (commande) => {
    return commande.articles.some(article => article.reste_a_recevoir > 0);
};

// Ouvrir le modal
const openCreateModal = () => {
    showCreateModal.value = true;
    resetForm();
};

// Fermer le modal
const closeCreateModal = () => {
    showCreateModal.value = false;
    resetForm();
};

// Réinitialiser le formulaire
const resetForm = () => {
    form.value = {
        bon_commande_id: '',
        date_reception: new Date().toISOString().split('T')[0],
        responsable_reception_id: '',
        lignes_reception: [],
        notes: '',
        fichier_bonlivraison: null,
        fichier_facture: null
    };
    selectedCommande.value = null;
    formErrors.value = [];
};


// Vérifier s'il y a une erreur de réception
const hasReceptionError = (articleId) => {
    const article = selectedCommande.value.articles.find(a => a.article_id === articleId);
    const ligne = form.value.lignes_reception.find(l => l.article_id === articleId);
    
    if (!article || !ligne) return false;
    
    const quantite = parseFloat(ligne.quantite_receptionnee) || 0;
    return quantite > article.reste_a_recevoir;
};

// Obtenir la quantité reçue pour un article
const getQuantiteRecue = (articleId) => {
    const ligne = form.value.lignes_reception.find(l => l.article_id === articleId);
    return parseFloat(ligne?.quantite_receptionnee) || 0;
};

// Gestion des fichiers
const onFileSelected = (field, event) => {
    const file = event.target.files[0];
    if (file) {
        // Vérification spécifique pour la facture
        if (field === 'fichier_facture' && !isReceptionComplete.value) {
            formErrors.value = ['La facture ne peut être ajoutée que pour une réception complète'];
            event.target.value = ''; // Réinitialiser l'input file
            return;
        }
        
        form.value[field] = file;
        formErrors.value = formErrors.value.filter(error => 
            !error.includes('facture')
        );
    }
};

// Calculs et helpers
const getArticlesWithRemainingQuantity = () => {
    if (!selectedCommande.value) return 0;
    return selectedCommande.value.articles.filter(article => 
        article.reste_a_recevoir > 0
    ).length;
};

const getTotalQuantiteRecue = () => {
    return form.value.lignes_reception.reduce((total, ligne) => {
        return total + (parseFloat(ligne.quantite_receptionnee) || 0);
    }, 0);
};

const getReceptionType = () => {
    if (!selectedCommande.value) return 'Aucune réception';
    
    if (isReceptionComplete.value) {
        return 'Réception complète';
    }
    
    const totalRecu = getTotalQuantiteRecue();
    if (totalRecu > 0) {
        return 'Réception partielle';
    }
    
    return 'Aucune réception';
};

const getReceptionTypeClass = () => {
    const type = getReceptionType();
    switch (type) {
        case 'Réception complète': return 'text-green-600';
        case 'Réception partielle': return 'text-orange-600';
        default: return 'text-gray-600';
    }
};

const calculateNewProgression = () => {
    if (!selectedCommande.value) return 0;
    
    const quantiteTotaleRecueAvant = selectedCommande.value.quantite_totale_recue;
    const quantiteRecueCetteReception = getTotalQuantiteRecue();
    const quantiteTotaleCommandee = selectedCommande.value.quantite_totale_commandee;
    
    if (quantiteTotaleCommandee === 0) return 0;
    
    const nouvelleProgression = ((quantiteTotaleRecueAvant + quantiteRecueCetteReception) / quantiteTotaleCommandee) * 100;
    return Math.min(100, Math.round(nouvelleProgression * 100) / 100);
};

// Validation du formulaire
const isFormValid = computed(() => {
    // Validation de base
    if (!form.value.bon_commande_id || !form.value.date_reception || !form.value.responsable_reception_id) {
        return false;
    }
    
    // Au moins une quantité > 0
    if (!form.value.lignes_reception.some(ligne => parseFloat(ligne.quantite_receptionnee) > 0)) {
        return false;
    }
    
    // Vérifier que les quantités ne dépassent pas le reste à recevoir
    for (const ligne of form.value.lignes_reception) {
        const article = selectedCommande.value?.articles.find(a => a.article_id === ligne.article_id);
        if (article && parseFloat(ligne.quantite_receptionnee) > article.reste_a_recevoir) {
            return false;
        }
    }
    
    // Vérification spécifique pour la facture
    if (form.value.fichier_facture && !isReceptionComplete.value) {
        return false;
    }
    
    return true;
});


// Helper pour formater les prix
const formatPrice = (price) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR'
    }).format(price);
};

// Helper pour formater les dates
const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('fr-FR');
};

// Props
const props = defineProps({
    bonReceptions: {
        type: Object,
        default: () => ({ data: [], links: [] })
    },
    commandesEnAttente: {
        type: Array,
        default: () => []
    },
    magasiniers: {
        type: Array,
        default: () => []
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


// Filtres
const filters = ref({
    type_reception: props.filters?.type_reception || '',
    statut: props.filters?.statut || '',
    responsable_reception_id: props.filters?.responsable_reception_id || '',
    search: props.filters?.search || '',
});

// Formulaire
const receptionForm = useForm({
    bon_commande_id: '',
    date_reception: new Date().toISOString().split('T')[0],
    responsable_reception_id: '',
    fichier_bonlivraison: null,
    fichier_facture: null,
    lignes_reception: [],
    notes: '',
});


const getTypeBadgeClass = (type) => {
    const classes = {
        'complet': 'bg-green-100 text-green-800 border border-green-200',
        'partiel': 'bg-orange-100 text-orange-800 border border-orange-200'
    };
    return classes[type] || 'bg-gray-100 text-gray-800 border border-gray-200';
};

const getTypeLabel = (type) => {
    const labels = {
        'complet': 'Complet',
        'partiel': 'Partiel'
    };
    return labels[type] || type;
};

const getTypeIcon = (type) => {
    const icons = {
        'complet': CheckBadgeIcon,
        'partiel': ClipboardDocumentListIcon
    };
    return icons[type] || ClipboardDocumentListIcon;
};

const getStatutBadgeClass = (statut) => {
    const classes = {
        'valide': 'bg-green-100 text-green-800 border border-green-200',
        'brouillon': 'bg-yellow-100 text-yellow-800 border border-yellow-200',
        'annule': 'bg-red-100 text-red-800 border border-red-200'
    };
    return classes[statut] || 'bg-gray-100 text-gray-800 border border-gray-200';
};

const getStatutLabel = (statut) => {
    const labels = {
        'valide': 'Validé',
        'brouillon': 'Brouillon',
        'annule': 'Annulé'
    };
    return labels[statut] || statut;
};

const getStatutIcon = (statut) => {
    const icons = {
        'valide': CheckBadgeIcon,
        'brouillon': ClockIcon,
        'annule': XMarkIcon
    };
    return icons[statut] || ClockIcon;
};

const getStatusIconBg = (statut) => {
    const classes = {
        'valide': 'bg-green-500',
        'brouillon': 'bg-yellow-500',
        'annule': 'bg-red-500'
    };
    return classes[statut] || 'bg-gray-500';
};

const getProgressBarClass = (percentage) => {
    if (percentage >= 100) return 'bg-green-500';
    if (percentage >= 75) return 'bg-blue-500';
    if (percentage >= 50) return 'bg-yellow-500';
    if (percentage >= 25) return 'bg-orange-500';
    return 'bg-red-500';
};

// Méthodes d'action
const applyFilters = () => {
    router.get(route('bon-receptions.index'), filters.value, {
        preserveState: true,
        replace: true,
    });
};

const resetFilters = () => {
    filters.value = {
        type_reception: '',
        statut: '',
        responsable_reception_id: '',
        search: '',
    };
};

const exportData = () => {
    console.log('Export des données');
};

const downloadPdf = (reception) => {
    window.open(route('bon-receptions.download-pdf', reception.id), '_blank');
};

const downloadBonLivraison = (reception) => {
    window.open(route('bon-receptions.download-bon-livraison', reception.id), '_blank');
};

const downloadFacture = (reception) => {
    window.open(route('bon-receptions.download-facture', reception.id), '_blank');
};

// Watch pour les filtres
watch(filters, (value) => {
    // Debounce pour éviter trop de requêtes
    clearTimeout(window.filterTimeout);
    window.filterTimeout = setTimeout(() => {
        applyFilters();
    }, 500);
}, { deep: true });




</script>

<style scoped>
.hover-lift:hover {
    transform: translateY(-2px);
}

.progress-bar {
    transition: width 0.3s ease-in-out;
}
</style>
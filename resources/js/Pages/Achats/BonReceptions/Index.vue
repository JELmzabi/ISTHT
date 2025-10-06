<template>
    <AuthenticatedLayout>
        <Head title="Gestion des Bons de Réception" />

        <div class="space-y-6">
            <!-- En-tête -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Bons de Réception</h1>
                    <p class="text-gray-600">Gestion des réceptions de marchandises</p>
                </div>
                <div class="flex space-x-3">
                    <!-- Bouton Nouvelle Réception -->
                    <button
                        @click="showCreateModal = true"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-2 transition-colors"
                    >
                        <PlusIcon class="h-5 w-5" />
                        Nouvelle réception
                    </button>
                </div>
            </div>

            <!-- Stats -->
            <section class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-gray-500">Total Réceptions</div>
                    <div class="mt-2 flex items-center justify-between">
                        <div class="text-3xl font-bold text-gray-800">{{ stats?.total || 0 }}</div>
                        <DocumentTextIcon class="w-6 h-6 text-gray-600" />
                    </div>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-gray-500">Réceptions Complètes</div>
                    <div class="mt-2 flex items-center justify-between">
                        <div class="text-3xl font-bold text-green-700">{{ stats?.complets || 0 }}</div>
                        <CheckBadgeIcon class="w-6 h-6 text-green-600" />
                    </div>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-gray-500">Réceptions Partielles</div>
                    <div class="mt-2 flex items-center justify-between">
                        <div class="text-3xl font-bold text-orange-700">{{ stats?.partiels || 0 }}</div>
                        <ClipboardDocumentListIcon class="w-6 h-6 text-orange-600" />
                    </div>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-gray-500">Avec Facture</div>
                    <div class="mt-2 flex items-center justify-between">
                        <div class="text-3xl font-bold text-blue-700">{{ stats?.avec_facture || 0 }}</div>
                        <DocumentChartBarIcon class="w-6 h-6 text-blue-600" />
                    </div>
                </div>
            </section>

            <!-- Filtres -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type Réception</label>
                        <select v-model="filters.type_reception" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les types</option>
                            <option value="complet">Complets</option>
                            <option value="partiel">Partiels</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select v-model="filters.statut" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les statuts</option>
                            <option value="valide">Validé</option>
                            <option value="brouillon">Brouillon</option>
                            <option value="annule">Annulé</option>
                        </select>
                    </div>
                    <div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Responsable Réception</label>
    <select v-model="filters.responsable_reception_id" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="">Tous les responsables</option>
        <option v-for="magasinier in magasiniers" :key="magasinier.id" :value="magasinier.id">
            {{ magasinier.name }} 
        </option>
    </select>
</div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                        <div class="relative">
                            <input v-model="filters.search" type="text" placeholder="N° réception, N° commande, fournisseur..." 
                                class="w-full border border-gray-300 rounded-lg p-2 pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <MagnifyingGlassIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
                        </div>
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

            <!-- Commandes en attente de livraison -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                    <h3 class="text-lg font-semibold text-gray-900">Commandes en Attente de Livraison</h3>
                    <p class="text-gray-600 text-sm">Commandes validées en attente de réception complète</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Commande</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fournisseur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Commande</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Articles</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantité Commandée</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantité Reçue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reste à Recevoir</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progression</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="commande in commandesEnAttente" :key="commande.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ commande.reference }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <img v-if="commande.fournisseur?.logo_url" :src="commande.fournisseur.logo_url" 
                                                class="h-8 w-8 rounded-full object-cover border border-gray-200">
                                            <div v-else class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <UserCircleIcon class="h-5 w-5 text-gray-400" />
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ commande.fournisseur?.nom_affichage || commande.fournisseur?.raison_sociale || 'Non spécifié' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ commande.fournisseur?.contact || '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(commande.date_mise_ligne) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ commande.articles?.length || 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                    {{ commande.quantite_totale_commandee || 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                    {{ commande.quantite_totale_recue || 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                    <span class="font-semibold text-orange-600">{{ commande.reste_a_recevoir || 0 }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" 
                                                 :style="{ width: Math.min(commande.pourcentage_recu || 0, 100) + '%' }"></div>
                                        </div>
                                        <span class="ml-2 text-xs font-medium text-gray-700">{{ Math.min(commande.pourcentage_recu || 0, 100) }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button
                                        @click="openCreateForm(commande.id)"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                    >
                                        <PlusIcon class="h-3 w-3 mr-1" />
                                        Créer réception
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="!commandesEnAttente || commandesEnAttente.length === 0" class="text-center py-12">
                    <DocumentCheckIcon class="mx-auto h-12 w-12 text-green-500" />
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Toutes les commandes sont livrées</h3>
                    <p class="mt-2 text-gray-500">Aucune commande en attente de livraison.</p>
                </div>
            </div>

            <!-- Liste des bons de réception -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Historique des Réceptions</h3>
                        <div class="text-sm text-gray-500">
                            {{ bonReceptions?.total || 0 }} résultat(s)
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Réception</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Commande</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fournisseur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Réception</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                               
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Documents</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="reception in bonReceptions?.data || []" :key="reception.id" class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ reception.numero_affichage || reception.numero }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Par {{ reception.created_by?.name || 'Système' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-medium">
                                    <div v-if="reception.bon_commande">
                                        {{ reception.bon_commande.reference }}
                                    </div>
                                    <div v-else class="text-gray-400 text-xs">
                                        Sans commande
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <img v-if="reception.fournisseur?.logo_url" :src="reception.fournisseur.logo_url" 
                                                class="h-8 w-8 rounded-full object-cover border border-gray-200">
                                            <div v-else class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <BuildingStorefrontIcon class="h-4 w-4 text-gray-400" />
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ reception.fournisseur?.nom_affichage || reception.fournisseur?.raison_sociale || 'Non spécifié' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ reception.fournisseur?.ville || '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(reception.date_reception) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getTypeBadgeClass(reception.type_reception)" 
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                        <CheckBadgeIcon v-if="reception.type_reception === 'complet'" class="h-3 w-3 mr-1" />
                                        <ClipboardDocumentListIcon v-else class="h-3 w-3 mr-1" />
                                        {{ getTypeLabel(reception.type_reception) }}
                                    </span>
                                </td>
                               
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <!-- Dans la liste des bons de réception -->
<td class="px-6 py-4 whitespace-nowrap">
    <div class="flex space-x-2">
        <button 
            v-if="reception.fichier_bonlivraison" 
            @click="downloadBonLivraison(reception)"
            class="text-blue-600 hover:text-blue-800 p-1 rounded-lg hover:bg-blue-50 transition-colors"
            title="Télécharger BL"
        >
            <DocumentTextIcon class="h-4 w-4" />
        </button>
        <button v-else class="text-gray-300 p-1" title="Aucun BL">
            <DocumentTextIcon class="h-4 w-4" />
        </button>
        
        <button 
            v-if="reception.fichier_facture" 
            @click="downloadFacture(reception)"
            class="text-green-600 hover:text-green-800 p-1 rounded-lg hover:bg-green-50 transition-colors"
            title="Télécharger Facture"
        >
            <DocumentChartBarIcon class="h-4 w-4" />
        </button>
        <button v-else class="text-gray-300 p-1" title="Aucune facture">
            <DocumentChartBarIcon class="h-4 w-4" />
        </button>
    </div>
</td>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getStatutBadgeClass(reception.statut)" 
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                        {{ getStatutLabel(reception.statut) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
<Link
    :href="route('bon-receptions.show-details', reception.id)"
    class="text-blue-600 hover:text-blue-900 p-1 rounded-lg hover:bg-blue-50 transition-colors"
    title="Voir détails complets"
>
    <EyeIcon class="h-4 w-4" />
</Link>
                                         <button
                                            @click="downloadPdf(reception)"
                                            class="text-green-600 hover:text-green-900 p-1 rounded-lg hover:bg-green-50 transition-colors"
                                            title="Télécharger PDF"
                                        >
                                            <DocumentArrowDownIcon class="h-4 w-4" />
                                        </button>
                                        <Link
                                            v-if="reception.statut === 'brouillon'"
                                            :href="route('bon-receptions.edit', reception.id)"
                                            class="text-orange-600 hover:text-orange-900 p-1 rounded-lg hover:bg-orange-50 transition-colors"
                                            title="Modifier"
                                        >
                                            <PencilIcon class="h-4 w-4" />
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Message vide -->
                <div v-if="!bonReceptions?.data || bonReceptions.data.length === 0" class="text-center py-16">
                    <div class="text-gray-500">
                        <TruckIcon class="mx-auto h-20 w-20 text-gray-300" />
                        <h3 class="mt-4 text-xl font-medium text-gray-900">Aucun bon de réception</h3>
                        <p class="mt-2 text-gray-500">
                            {{ filters.search || filters.type_reception || filters.statut || filters.responsable_reception_id ? 
                               'Aucun résultat pour vos critères de recherche.' : 
                               'Commencez par créer votre première réception.' }}
                        </p>
                        <div class="mt-8" v-if="!filters.search && !filters.type_reception && !filters.statut && !filters.responsable_reception_id">
                            <button 
                                @click="showCreateModal = true"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                            >
                                <PlusIcon class="h-5 w-5 mr-2" />
                                Nouvelle réception
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="bonReceptions?.links && bonReceptions.links.length > 1" class="bg-white px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-gray-700">
                            Affichage de {{ bonReceptions.from }} à {{ bonReceptions.to }} sur {{ bonReceptions.total }} résultats
                        </div>
                        <div class="flex space-x-1">
                            <template v-for="link in bonReceptions.links" :key="link.label">
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

        <!-- Modal de création de bon de réception -->
        <div v-if="showCreateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-6xl shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
                <div class="mt-3">
                    <!-- En-tête du modal -->
                    <div class="flex justify-between items-center pb-4 border-b">
                        <h3 class="text-xl font-bold text-gray-900">Créer un Bon de Réception</h3>
                        <button @click="closeCreateModal" class="text-gray-400 hover:text-gray-600">
                            <XMarkIcon class="h-6 w-6" />
                        </button>
                    </div>

                    <!-- Formulaire de création -->
                    <form @submit.prevent="submitReceptionForm" class="mt-4 space-y-6">
                        <!-- Sélection de la commande -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Sélectionner une Commande *
                            </label>
                            <select 
                                v-model="receptionForm.bon_commande_id" 
                                @change="loadCommandeDetails"
                                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required
                            >
                                <option value="">Choisir une commande...</option>
                                <option 
                                    v-for="commande in commandesEnAttente" 
                                    :key="commande.id" 
                                    :value="commande.id"
                                >
                                    {{ commande.reference }} - {{ commande.fournisseur?.raison_sociale || 'Non spécifié' }} 
                                    ({{ Math.min(commande.pourcentage_recu || 0, 100) }}% reçu)
                                </option>
                            </select>
                        </div>

                        <!-- Informations de la commande sélectionnée -->
                        <div v-if="selectedCommande" class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="font-semibold text-lg mb-3">Informations de la Commande</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <strong>Référence:</strong> {{ selectedCommande.reference }}
                                </div>
                                <div>
                                    <strong>Fournisseur:</strong> {{ selectedCommande.fournisseur?.raison_sociale || 'Non spécifié' }}
                                </div>
                                <div>
                                    <strong>Statut:</strong> 
                                    <span :class="getStatutBadgeClass(selectedCommande.statut)" class="ml-1 px-2 py-1 rounded-full text-xs">
                                        {{ getStatutLabel(selectedCommande.statut) }}
                                    </span>
                                </div>
                                <div class="md:col-span-3">
                                    <strong>Progression:</strong>
                                    <div class="flex items-center mt-1">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" 
                                                 :style="{ width: Math.min(selectedCommande.pourcentage_recu || 0, 100) + '%' }"></div>
                                        </div>
                                        <span class="ml-2 text-xs font-medium text-gray-700">{{ Math.min(selectedCommande.pourcentage_recu || 0, 100) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Articles de la commande -->
                        <div v-if="selectedCommande && selectedCommande.articles" class="border rounded-lg">
                            <div class="bg-gray-50 px-4 py-3 border-b">
                                <h4 class="font-semibold">Articles à Réceptionner</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    Les prix et TVA sont repris de la commande et ne peuvent pas être modifiés
                                </p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Article</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantité Commandée</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Déjà Reçue</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reste à Recevoir</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantité Reçue *</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix Unitaire HT</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">TVA %</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(ligne, index) in selectedCommande.articles" :key="ligne.id">
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-gray-900">{{ ligne.article?.designation || 'Article non trouvé' }}</div>
                                                <div class="text-sm text-gray-500">Ref: {{ ligne.article?.reference || 'N/A' }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 text-center">
                                                {{ ligne.quantite_commandee }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 text-center">
                                                {{ ligne.quantite_deja_recue || 0 }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 text-center">
                                                <span class="font-semibold" :class="ligne.reste_a_recevoir > 0 ? 'text-orange-600' : 'text-green-600'">
                                                    {{ ligne.reste_a_recevoir || 0 }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <input
                                                    type="number"
                                                    v-model="receptionForm.lignes_reception[index].quantite_receptionnee"
                                                    :max="ligne.reste_a_recevoir"
                                                    min="0"
                                                    step="0.01"
                                                    class="w-24 border border-gray-300 rounded p-1 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                    @change="validateQuantite(ligne, index)"
                                                >
                                                <div v-if="receptionForm.errors[`lignes_reception.${index}.quantite_receptionnee`]" 
                                                     class="text-red-500 text-xs mt-1">
                                                    {{ receptionForm.errors[`lignes_reception.${index}.quantite_receptionnee`] }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ formatCurrency(ligne.prix_unitaire_ht || 0) }}
                                                </div>
                                                <div class="text-xs text-gray-500">Prix commande</div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ ligne.taux_tva }}%
                                                </div>
                                                <div class="text-xs text-gray-500">TVA commande</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Informations de réception -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Date de Réception *
                                </label>
                                <input
                                    type="date"
                                    v-model="receptionForm.date_reception"
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required
                                >
                                <div v-if="receptionForm.errors.date_reception" class="text-red-500 text-xs mt-1">
                                    {{ receptionForm.errors.date_reception }}
                                </div>
                            </div>
                            <!-- Dans le modal de création -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Responsable Réception *
    </label>
    <select
        v-model="receptionForm.responsable_reception_id"
        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        required
    >
        <option value="">Choisir un responsable...</option>
        <option 
            v-for="magasinier in magasiniers" 
            :key="magasinier.id" 
            :value="magasinier.id"
        >
            {{ magasinier.name }} 
        </option>
    </select>
    <div v-if="receptionForm.errors.responsable_reception_id" class="text-red-500 text-xs mt-1">
        {{ receptionForm.errors.responsable_reception_id }}
    </div>
</div>
                        </div>

                        <!-- Documents -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Bon de Livraison
                                </label>
                                <input
                                    type="file"
                                    @change="receptionForm.fichier_bonlivraison = $event.target.files[0]"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                <div v-if="receptionForm.errors.fichier_bonlivraison" class="text-red-500 text-xs mt-1">
                                    {{ receptionForm.errors.fichier_bonlivraison }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Facture
                                </label>
                                <input
                                    type="file"
                                    @change="receptionForm.fichier_facture = $event.target.files[0]"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                <div v-if="receptionForm.errors.fichier_facture" class="text-red-500 text-xs mt-1">
                                    {{ receptionForm.errors.fichier_facture }}
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Notes
                            </label>
                            <textarea
                                v-model="receptionForm.notes"
                                rows="3"
                                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Notes supplémentaires..."
                            ></textarea>
                            <div v-if="receptionForm.errors.notes" class="text-red-500 text-xs mt-1">
                                {{ receptionForm.errors.notes }}
                            </div>
                        </div>

                        <!-- Résumé -->
                        <div v-if="hasQuantitesSaisies" class="p-4 bg-green-50 rounded-lg">
                            <h4 class="font-semibold text-green-800 mb-2">Résumé de la Réception</h4>
                            <div class="text-sm text-green-700">
                                <p>Type de réception: <strong>{{ getTypeReceptionCalcule }}</strong></p>
                                <p>Nombre d'articles: <strong>{{ nombreArticlesAReceptionner }}</strong></p>
                                <p>Quantité totale: <strong>{{ quantiteTotaleReceptionnee }}</strong></p>
                            </div>
                        </div>

                        <!-- Boutons -->
<div class="flex justify-end space-x-4 pt-4 border-t">
    <button
        type="button"
        @click="closeCreateModal"
        class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors"
        :disabled="receptionForm.processing"
    >
        Annuler
    </button>
    <button
        type="submit"
        :disabled="!canSubmitReception || receptionForm.processing"
        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 disabled:bg-blue-300 transition-colors flex items-center gap-2"
    >
        <svg v-if="receptionForm.processing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>{{ receptionForm.processing ? 'Création en cours...' : 'Créer le Bon de Réception' }}</span>
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
import { ref, computed, watch, onMounted } from 'vue';
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
    UserCircleIcon,
    BuildingStorefrontIcon,
    DocumentCheckIcon,
    TruckIcon,
    XMarkIcon
} from '@heroicons/vue/24/outline';

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

// États réactifs
const showCreateModal = ref(false);
const selectedCommande = ref(null);

// Filtres
const filters = ref({
    type_reception: props.filters?.type_reception || '',
    statut: props.filters?.statut || '',
    responsable_reception_id: props.filters?.responsable_reception_id || '',
    search: props.filters?.search || '',
});

// Formulaire de création
const receptionForm = useForm({
    bon_commande_id: '',
    date_reception: new Date().toISOString().split('T')[0],
    responsable_reception_id: '',
    fichier_bonlivraison: null,
    fichier_facture: null,
    lignes_reception: [],
    notes: '',
});

// Computed properties
const hasQuantitesSaisies = computed(() => {
    return receptionForm.lignes_reception.some(ligne => 
        parseFloat(ligne.quantite_receptionnee) > 0
    );
});

const canSubmitReception = computed(() => {
    return receptionForm.bon_commande_id && 
           receptionForm.date_reception && 
           receptionForm.responsable_reception_id &&
           hasQuantitesSaisies.value;
});

const nombreArticlesAReceptionner = computed(() => {
    return receptionForm.lignes_reception.filter(ligne => 
        parseFloat(ligne.quantite_receptionnee) > 0
    ).length;
});

const quantiteTotaleReceptionnee = computed(() => {
    return receptionForm.lignes_reception.reduce((total, ligne) => 
        total + parseFloat(ligne.quantite_receptionnee || 0), 0
    );
});

const getTypeReceptionCalcule = computed(() => {
    if (!selectedCommande.value) return 'Partiel';
    
    const tousComplets = selectedCommande.value.articles.every(ligne => {
        const quantiteRecue = parseFloat(
            receptionForm.lignes_reception.find(l => l.article_id === ligne.article_id)?.quantite_receptionnee || 0
        );
        const quantiteTotaleRecue = (ligne.quantite_deja_recue || 0) + quantiteRecue;
        return quantiteTotaleRecue >= ligne.quantite_commandee;
    });
    
    return tousComplets ? 'Complet' : 'Partiel';
});

// Méthodes
const openCreateForm = (commandeId = null) => {
    if (commandeId) {
        receptionForm.bon_commande_id = commandeId;
        loadCommandeDetails();
    }
    showCreateModal.value = true;
};

// const closeCreateModal = () => {
//     showCreateModal.value = false;
//     selectedCommande.value = null;
//     receptionForm.reset();
//     receptionForm.clearErrors();
// };

const loadCommandeDetails = async () => {
    if (!receptionForm.bon_commande_id) {
        selectedCommande.value = null;
        return;
    }

    try {
        const commande = props.commandesEnAttente.find(c => c.id == receptionForm.bon_commande_id);
        if (commande) {
            selectedCommande.value = { ...commande };
            
            // Initialiser seulement la quantité réceptionnée
            receptionForm.lignes_reception = selectedCommande.value.articles.map(ligne => ({
                article_id: ligne.article_id,
                quantite_receptionnee: 0,
            }));
        }
    } catch (error) {
        console.error('Error loading commande details:', error);
    }
};

const validateQuantite = (ligne, index) => {
    const quantiteReceptionnee = parseFloat(receptionForm.lignes_reception[index].quantite_receptionnee) || 0;
    const resteARecevoir = ligne.reste_a_recevoir || 0;
    
    if (quantiteReceptionnee > resteARecevoir) {
        receptionForm.lignes_reception[index].quantite_receptionnee = resteARecevoir;
    }
    
    if (quantiteReceptionnee < 0) {
        receptionForm.lignes_reception[index].quantite_receptionnee = 0;
    }
};

const submitReceptionForm = () => {
    // Filtrer seulement les lignes avec des quantités > 0
    const lignesAEnvoyer = receptionForm.lignes_reception
        .filter(ligne => parseFloat(ligne.quantite_receptionnee) > 0)
        .map(ligne => ({
            article_id: ligne.article_id,
            quantite_receptionnee: ligne.quantite_receptionnee
        }));

    // Préparer les données
    const formData = {
        bon_commande_id: receptionForm.bon_commande_id,
        date_reception: receptionForm.date_reception,
        responsable_reception_id: receptionForm.responsable_reception_id,
        notes: receptionForm.notes,
        lignes_reception: lignesAEnvoyer,
        fichier_bonlivraison: receptionForm.fichier_bonlivraison,
        fichier_facture: receptionForm.fichier_facture
    };

    // Désactiver le bouton
    const submitButton = document.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.innerHTML = '<span class="flex items-center"><svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Création...</span>';

    // Utiliser useForm avec transformation pour les fichiers
    receptionForm.transform((data) => ({
        ...data,
        lignes_reception: lignesAEnvoyer
    })).post(route('bon-receptions.store'), {
        preserveScroll: true,
        preserveState: false, // Important pour fermer le modal
        onSuccess: () => {
            // Fermer le modal
            closeCreateModal();
            
            // Réactiver le bouton
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        },
        onError: (errors) => {
            console.error('Form errors:', errors);
            
            // Réactiver le bouton
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        },
        onFinish: () => {
            // S'assurer que le bouton est réactivé
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    });
};

const closeCreateModal = () => {
    showCreateModal.value = false;
    
    // Réinitialiser après un court délai pour éviter les conflits
    setTimeout(() => {
        selectedCommande.value = null;
        receptionForm.reset();
        receptionForm.clearErrors();
        
        // Réinitialiser les champs fichiers
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.value = '';
        });
    }, 300);
};

// Watch pour les filtres
watch(filters, (value) => {
    router.get(route('bon-receptions.index'), value, {
        preserveState: true,
        replace: true,
    });
}, { deep: true });

// Méthodes utilitaires
const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('fr-FR');
};

const formatCurrency = (amount) => {
    if (!amount) return '0,00 DH';
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'MAD' }).format(amount);
};

const calculateTotal = (reception) => {
    if (!reception.lignesReception) return 0;
    return reception.lignesReception.reduce((total, ligne) => {
        const montantHT = ligne.quantite_receptionnee * ligne.prix_unitaire;
        const montantTTC = montantHT * (1 + (ligne.taux_tva / 100));
        return total + montantTTC;
    }, 0);
};

const getTypeBadgeClass = (type) => {
    const classes = {
        'complet': 'bg-green-100 text-green-800',
        'partiel': 'bg-orange-100 text-orange-800'
    };
    return classes[type] || 'bg-gray-100 text-gray-800';
};

const getTypeLabel = (type) => {
    const labels = {
        'complet': 'Complet',
        'partiel': 'Partiel'
    };
    return labels[type] || type;
};

const getStatutBadgeClass = (statut) => {
    const classes = {
        'valide': 'bg-green-100 text-green-800',
        'brouillon': 'bg-yellow-100 text-yellow-800',
        'annule': 'bg-red-100 text-red-800',
        'cree': 'bg-gray-100 text-gray-800',
        'attente_livraison': 'bg-blue-100 text-blue-800',
        'livre_partiellement': 'bg-orange-100 text-orange-800',
        'livre_completement': 'bg-green-100 text-green-800'
    };
    return classes[statut] || 'bg-gray-100 text-gray-800';
};

const getStatutLabel = (statut) => {
    const labels = {
        'valide': 'Validé',
        'brouillon': 'Brouillon',
        'annule': 'Annulé',
        'cree': 'Créé',
        'attente_livraison': 'En attente livraison',
        'livre_partiellement': 'Livré partiellement',
        'livre_completement': 'Livré complètement'
    };
    return labels[statut] || statut;
};

const resetFilters = () => {
    filters.value = {
        type_reception: '',
        statut: '',
        responsable_reception_id: '',
        search: '',
    };
};

const downloadPdf = (reception) => {
    console.log('Download PDF for reception:', reception.id);
    
    // Méthode 1: Ouvrir dans un nouvel onglet
    window.open(route('bon-receptions.download-pdf', reception.id), '_blank');
    
    // Méthode 2: Téléchargement direct (alternative)
    // const link = document.createElement('a');
    // link.href = route('bon-receptions.download-pdf', reception.id);
    // link.target = '_blank';
    // link.download = `bon-reception-${reception.numero}.pdf`;
    // document.body.appendChild(link);
    // link.click();
    // document.body.removeChild(link);
};
// Méthodes de téléchargement
const downloadBonLivraison = (reception) => {
    window.open(route('bon-receptions.download-bon-livraison', reception.id), '_blank');
};

const downloadFacture = (reception) => {
    window.open(route('bon-receptions.download-facture', reception.id), '_blank');
};
const downloadDocument = (url, type) => {
    if (url) {
        const link = document.createElement('a');
        link.href = url;
        link.target = '_blank';
        link.download = `${type}_${new Date().getTime()}`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
};

// Log pour le débogage
onMounted(() => {
    console.log('Données reçues dans le composant:', {
        commandesEnAttente: props.commandesEnAttente,
        bonReceptions: props.bonReceptions,
        stats: props.stats,
        responsables: props.responsables
    });
});
</script>

<style scoped>
.progress-bar {
    transition: width 0.3s ease-in-out;
}
</style>
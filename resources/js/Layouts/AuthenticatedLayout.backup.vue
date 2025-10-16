<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navbar -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <button 
                            @click="isSidebarOpen = !isSidebarOpen"
                            class="md:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100"
                        >
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        
                        <div class="flex-shrink-0 flex items-center ml-4 md:ml-0">
                            <h1 class="text-xl font-semibold">Gestion Stock</h1>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Indicateur de page active -->
                        <div class="hidden sm:block">
                            <span class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                                {{ getCurrentPageTitle() }}
                            </span>
                        </div>
                        
                        <div class="ml-3 relative">
                            <div class="flex items-center">
                                <span class="mr-2 text-sm text-gray-700">{{ $page.props.auth.user.name }}</span>
                                <div class="relative">
                                    <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar et contenu -->
        <div class="flex">
            <!-- Sidebar -->
            <aside 
                :class="[
                    'bg-white shadow-sm w-64 fixed md:static inset-y-0 left-0 z-40 transform transition-transform duration-300 ease-in-out',
                    isSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'
                ]"
            >
                <div class="h-full overflow-y-auto py-4">
                    <!-- En-tête sidebar -->
                    <div class="px-4 py-2 border-b border-gray-200 mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Menu Principal</h2>
                    </div>
                    
                    <nav class="mt-4">
                        <ul class="space-y-1 px-3">
                            <!-- Tableau de bord -->
                            <li>
                                <Link 
                                    href="/dashboard" 
                                    class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors group"
                                    :class="{ 
                                        'bg-blue-50 text-blue-600 border-r-2 border-blue-600 font-semibold': $page.url === '/dashboard',
                                        'text-gray-600 hover:text-gray-900': $page.url !== '/dashboard'
                                    }"
                                >
                                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 mr-3 group-hover:bg-blue-200 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    </div>
                                    Tableau de bord
                                </Link>
                            </li>
                            
                            <!-- Module Articles -->
                            <li>
                                <Link 
                                    href="/articles" 
                                    class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors group"
                                    :class="{ 
                                        'bg-blue-50 text-blue-600 border-r-2 border-blue-600 font-semibold': $page.url.startsWith('/articles'),
                                        'text-gray-600 hover:text-gray-900': !$page.url.startsWith('/articles')
                                    }"
                                >
                                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 text-green-600 mr-3 group-hover:bg-green-200 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    Référentiel Articles
                                </Link>
                            </li>

                            <!-- Séparateur Module Achats -->
                            <li>
                                <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-6 mb-2">
                                    Gestion des Achats
                                </div>
                            </li>
                            
                            <!-- Bons de commande -->
                            <li>
                                <Link 
                                    :href="route('bon-commandes.index')" 
                                    class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors group"
                                    :class="{ 
                                        'bg-blue-50 text-blue-600 border-r-2 border-blue-600 font-semibold': $page.url.startsWith('/achats/bon-commandes'),
                                        'text-gray-600 hover:text-gray-900': !$page.url.startsWith('/achats/bon-commandes')
                                    }"
                                >
                                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-purple-100 text-purple-600 mr-3 group-hover:bg-purple-200 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    Bons de Commande
                                </Link>
                            </li>

                            <!-- Fournisseurs -->
                            <li>
                                <Link 
                                    :href="route('fournisseurs.index')" 
                                    class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors group"
                                    :class="{ 
                                        'bg-blue-50 text-blue-600 border-r-2 border-blue-600 font-semibold': $page.url.startsWith('/achats/fournisseurs'),
                                        'text-gray-600 hover:text-gray-900': !$page.url.startsWith('/achats/fournisseurs')
                                    }"
                                >
                                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-orange-100 text-orange-600 mr-3 group-hover:bg-orange-200 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    Fournisseurs
                                    <span v-if="fournisseursCount" class="ml-auto bg-blue-100 text-blue-600 text-xs font-medium px-2 py-1 rounded-full">
                                        {{ fournisseursCount }}
                                    </span>
                                </Link>
                            </li>

                            <!-- Bons de réception - MÊME STRUCTURE QUE LES AUTRES -->
                            <!-- Bons de réception - MÊME MÉTHODE QUE LES AUTRES -->
<li>
    <Link 
        href="/achats/bon-receptions" 
        class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors group"
        :class="{ 
            'bg-blue-50 text-blue-600 border-r-2 border-blue-600 font-semibold': $page.url.startsWith('/achats/bon-receptions'),
            'text-gray-600 hover:text-gray-900': !$page.url.startsWith('/achats/bon-receptions')
        }"
    >
        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-teal-100 text-teal-600 mr-3 group-hover:bg-teal-200 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        Bons de Réception
    </Link>
</li>

                            <!-- Module Stock -->
                            <li>
                                <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-6 mb-2">
                                    Gestion du Stock
                                </div>
                            </li>

                            <!-- Dans la section Gestion du Stock -->
<li>
    <Link 
        :href="route('entree-stocks.index')" 
        class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors group"
        :class="{ 
            'bg-blue-50 text-blue-600 border-r-2 border-blue-600 font-semibold': $page.url.startsWith('/stock/entrees'),
            'text-gray-600 hover:text-gray-900': !$page.url.startsWith('/stock/entrees')
        }"
    >
        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 text-green-600 mr-3 group-hover:bg-green-200 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        Entrées Stock
    </Link>
</li>

                            <!-- Inventaire -->
                            <li>
                                <Link 
                                    href="/inventaire" 
                                    class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors group"
                                    :class="{ 
                                        'bg-blue-50 text-blue-600 border-r-2 border-blue-600 font-semibold': $page.url.startsWith('/inventaire'),
                                        'text-gray-600 hover:text-gray-900': !$page.url.startsWith('/inventaire')
                                    }"
                                >
                                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 mr-3 group-hover:bg-indigo-200 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    Inventaire
                                </Link>
                            </li>

                            <!-- Mouvements de stock -->
                            <li>
                                <Link 
                                    href="/mouvements" 
                                    class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors group"
                                    :class="{ 
                                        'bg-blue-50 text-blue-600 border-r-2 border-blue-600 font-semibold': $page.url.startsWith('/mouvements'),
                                        'text-gray-600 hover:text-gray-900': !$page.url.startsWith('/mouvements')
                                    }"
                                >
                                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 text-amber-600 mr-3 group-hover:bg-amber-200 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                        </svg>
                                    </div>
                                    Mouvements
                                </Link>
                            </li>

                            <!-- Séparateur Users -->
                            <li>
                                <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-6 mb-2">
                                    Gestion des Utilisateurs
                                </div>
                            </li>

                            <!-- Profil -->
                            <li>
                                <Link 
                                    :href="route('users.index')" 
                                    class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors group"
                                    :class="{ 
                                        'bg-blue-50 text-blue-600 border-r-2 border-blue-600 font-semibold': $page.url.startsWith('/users'),
                                        'text-gray-600 hover:text-gray-900': !$page.url.startsWith('/users')
                                    }"
                                >
                                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 mr-3 group-hover:bg-gray-200 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    Utilisateurs
                                </Link>
                            </li>
                            

                            <!-- Séparateur Administration -->
                            <li>
                                <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-6 mb-2">
                                    Administration
                                </div>
                            </li>

                            <!-- Profil -->
                            <li>
                                <Link 
                                    href="/profile" 
                                    class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors group"
                                    :class="{ 
                                        'bg-blue-50 text-blue-600 border-r-2 border-blue-600 font-semibold': $page.url.startsWith('/profile'),
                                        'text-gray-600 hover:text-gray-900': !$page.url.startsWith('/profile')
                                    }"
                                >
                                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-600 mr-3 group-hover:bg-gray-200 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    Mon Profil
                                </Link>
                            </li>

                            <!-- Paramètres -->
                            <li>
                                <Link 
                                    href="/parametres" 
                                    class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors group"
                                    :class="{ 
                                        'bg-blue-50 text-blue-600 border-r-2 border-blue-600 font-semibold': $page.url.startsWith('/parametres'),
                                        'text-gray-600 hover:text-gray-900': !$page.url.startsWith('/parametres')
                                    }"
                                >
                                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 mr-3 group-hover:bg-red-200 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    Paramètres
                                </Link>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>

            <!-- Overlay mobile -->
            <div 
                v-if="isSidebarOpen"
                @click="isSidebarOpen = false"
                class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
            ></div>

            <!-- Contenu principal -->
            <main class="flex-1 p-4 md:p-6 lg:p-8 transition-all duration-300">
                <!-- En-tête de page dynamique -->
                <div class="mb-6">
                   
                </div>

                <!-- Contenu de la page -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 min-h-[calc(100vh-12rem)]">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const isSidebarOpen = ref(false);

// Récupérer les statistiques des fournisseurs si disponibles
const fournisseursCount = computed(() => {
    return usePage().props.fournisseursStats?.total || null;
});

// Titres des pages
const pageTitles = {
    '/dashboard': 'Tableau de Bord',
    '/articles': 'Référentiel des Articles',
    '/achats/fournisseurs': 'Gestion des Fournisseurs',
    '/achats/bon-commandes': 'Bons de Commande',
    '/achats/bon-receptions': 'Bons de Réception',
    '/inventaire': 'Inventaire du Stock',
    '/mouvements': 'Mouvements de Stock',
    '/users': 'Gestion des Utilisateurs',
    '/profile': 'Mon Profil',
    '/parametres': 'Paramètres du Système',
};

// Descriptions des pages
const pageDescriptions = {
    '/dashboard': 'Vue d\'ensemble de votre activité et statistiques',
    '/articles': 'Gestion du catalogue et des références articles',
    '/achats/fournisseurs': 'Gérez vos partenaires fournisseurs et leurs informations',
    '/achats/bon-commandes': 'Créez et suivez vos bons de commande',
    '/achats/bon-receptions': 'Enregistrez et validez les réceptions de marchandises',
    '/inventaire': 'Consultez et gérez les niveaux de stock',
    '/mouvements': 'Historique des entrées et sorties de stock',
    '/profile': 'Gérez vos informations personnelles',
    '/parametres': 'Configurez les paramètres de l\'application',
    '/users': 'Gérez les utilisateurs de l\'application'
};

const getCurrentPageTitle = () => {
    const currentUrl = usePage().url;
    for (const [path, title] of Object.entries(pageTitles)) {
        if (currentUrl.startsWith(path)) {
            return title;
        }
    }
    return 'Gestion Stock';
};

const getCurrentPageDescription = () => {
    const currentUrl = usePage().url;
    for (const [path, description] of Object.entries(pageDescriptions)) {
        if (currentUrl.startsWith(path)) {
            return description;
        }
    }
    return 'Interface de gestion de stock et d\'achats';
};
</script>

<style scoped>
/* Animation douce pour les transitions */
.sidebar-enter-active,
.sidebar-leave-active {
    transition: transform 0.3s ease;
}

.sidebar-enter-from,
.sidebar-leave-to {
    transform: translateX(-100%);
}

/* Style pour la scrollbar de la sidebar */
.sidebar-scrollbar::-webkit-scrollbar {
    width: 4px;
}

.sidebar-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.sidebar-scrollbar::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
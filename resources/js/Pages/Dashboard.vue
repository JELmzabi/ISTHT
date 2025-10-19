<template>
  <div class="p-6 space-y-6">

    <!-- Top KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
      <div class="bg-white shadow rounded-lg p-4">
        <h3 class="text-gray-500 text-sm">Total Users</h3>
        <p class="text-2xl font-bold">{{ totalUsers }}</p>
      </div>
      <div class="bg-white shadow rounded-lg p-4">
        <h3 class="text-gray-500 text-sm">Active Fournisseurs</h3>
        <p class="text-2xl font-bold">{{ activeFournisseurs }}</p>
      </div>
      <div class="bg-white shadow rounded-lg p-4">
        <h3 class="text-gray-500 text-sm">Articles in Stock</h3>
        <p class="text-2xl font-bold">{{ totalArticles }}</p>
      </div>
      <div class="bg-white shadow rounded-lg p-4">
        <h3 class="text-gray-500 text-sm">Total Bon Commandes</h3>
        <p class="text-2xl font-bold">{{ totalBCs }}</p>
      </div>
      <div class="bg-white shadow rounded-lg p-4">
        <h3 class="text-gray-500 text-sm">Pending Demandes</h3>
        <p class="text-2xl font-bold">{{ pendingDemandes }}</p>
      </div>
      <div class="bg-white shadow rounded-lg p-4">
        <h3 class="text-gray-500 text-sm">Stock Value</h3>
        <p class="text-2xl font-bold">{{ totalStockValue }} €</p>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Bon Commande Status Pie -->
      <div class="bg-white p-4 shadow rounded-lg">
        <h3 class="font-semibold mb-4">Bon Commande Status</h3>
        <apexchart type="donut" :options="bcChartOptions" :series="bcChartSeries"></apexchart>
      </div>

      <!-- Articles in Stock Bar -->
      <div class="bg-white p-4 shadow rounded-lg">
        <h3 class="font-semibold mb-4">Top Articles in Stock</h3>
        <apexchart type="bar" :options="articlesChartOptions" :series="articlesChartSeries"></apexchart>
      </div>
    </div>

    <!-- Low Stock Table -->
    <div class="bg-white shadow rounded-lg p-4">
      <h3 class="font-semibold mb-4 text-red-600">Low Stock Alerts</h3>
      <table class="w-full table-auto border-collapse border border-gray-200 text-sm">
        <thead>
          <tr class="bg-red-100">
            <th class="border p-2">Article</th>
            <th class="border p-2">Stock</th>
            <th class="border p-2">Seuil Minimal</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="article in lowStockArticles" :key="article.reference">
            <td class="border p-2">{{ article.designation }}</td>
            <td class="border p-2">{{ article.quantite_stock }}</td>
            <td class="border p-2">{{ article.seuil_minimal }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Recent Demandes Table -->
    <div class="bg-white shadow rounded-lg p-4">
      <h3 class="font-semibold mb-4">Recent Demandes</h3>
      <table class="w-full table-auto border-collapse border border-gray-200 text-sm">
        <thead>
          <tr class="bg-gray-100">
            <th class="border p-2">Numero</th>
            <th class="border p-2">Demandeur</th>
            <th class="border p-2">Motif</th>
            <th class="border p-2">Statut</th>
            <th class="border p-2">Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="demande in recentDemandes" :key="demande.numero">
            <td class="border p-2">{{ demande.numero }}</td>
            <td class="border p-2">{{ demande.demandeur }}</td>
            <td class="border p-2">{{ demande.motif }}</td>
            <td class="border p-2">
              <span
                :class="{
                  'bg-yellow-100 text-yellow-800 px-2 py-1 rounded': demande.statut === 'pending',
                  'bg-green-100 text-green-800 px-2 py-1 rounded': demande.statut === 'approved',
                  'bg-red-100 text-red-800 px-2 py-1 rounded': demande.statut === 'rejected'
                }"
              >
                {{ demande.statut }}
              </span>
            </td>
            <td class="border p-2">{{ demande.date }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Fournisseur Spending Chart -->
    <div class="bg-white p-4 shadow rounded-lg">
      <h3 class="font-semibold mb-4">Fournisseur Spending</h3>
      <apexchart type="bar" :options="fournisseurChartOptions" :series="fournisseurChartSeries"></apexchart>
    </div>
    
  </div>
</template>

<script setup>
import VueApexCharts from "vue3-apexcharts";

// Dummy data
const totalUsers = 120;
const activeFournisseurs = 15;
const totalArticles = 250;
const totalBCs = 80;
const pendingDemandes = 12;
const totalStockValue = 45000;

// Bon Commande Status
const bcChartSeries = [20, 15, 30, 15];
const bcChartOptions = {
  labels: ['Cree', 'Attente Livraison', 'Livre Partiel', 'Livre Complet'],
  legend: { position: 'bottom' },
  colors: ['#7F3DFF','#FBBF24','#F87171','#34D399']
};

// Articles in stock
const articlesChartSeries = [{
  name: 'Quantité',
  data: [120, 95, 60, 45, 30]
}];
const articlesChartOptions = {
  chart: { id: 'articles-stock' },
  xaxis: { categories: ['Article A','Article B','Article C','Article D','Article E'] },
  colors: ['#3B82F6']
};

// Recent demandes
const recentDemandes = [
  { numero: 'DEM-2025-001', demandeur: 'John Doe', motif: 'Besoin urgent', statut: 'pending', date: '2025-10-15' },
  { numero: 'DEM-2025-002', demandeur: 'Jane Doe', motif: 'Stock faible', statut: 'approved', date: '2025-10-14' },
  { numero: 'DEM-2025-003', demandeur: 'Ali Mohamed', motif: 'Réapprovisionnement', statut: 'rejected', date: '2025-10-13' }
];

// Low Stock Articles
const lowStockArticles = [
  { reference: 'ART-001', designation: 'Article A', quantite_stock: 5, seuil_minimal: 10 },
  { reference: 'ART-002', designation: 'Article B', quantite_stock: 2, seuil_minimal: 15 },
  { reference: 'ART-003', designation: 'Article C', quantite_stock: 8, seuil_minimal: 12 }
];

// Fournisseur Spending
const fournisseurChartSeries = [{
  name: 'Montant TTC (€)',
  data: [15000, 12000, 8000, 5000]
}];
const fournisseurChartOptions = {
  chart: { id: 'fournisseur-spending' },
  xaxis: { categories: ['Fournisseur A','Fournisseur B','Fournisseur C','Fournisseur D'] },
  colors: ['#FBBF24']
};
</script>

<script>
export default {
  components: {
    apexchart: VueApexCharts
  }
};
</script>

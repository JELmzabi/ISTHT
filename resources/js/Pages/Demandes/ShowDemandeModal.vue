<script setup>
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Modal } from '@inertiaui/modal-vue';
import { Link } from '@inertiajs/vue3';
import {
    UserIcon,
    PencilIcon,
    XCircleIcon
} from '@heroicons/vue/24/outline';
import Dump from '@/Components/Dump.vue';

const props = defineProps({
    demande: Object // passed from controller with relations: articles
});

// Format date in French
function formatDate(date) {
    return new Date(date).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// Compute a user-friendly status label
const statusLabel = computed(() => {
    const status = props.demande.statut;
    return {
        en_attente: 'En attente',
        approuvee: 'Approuvée',
        rejetee: 'Rejetée',
        annulee: 'Annulée'
    }[status] || status;
});

// Compute badge colors
const statusClasses = computed(() => {
    const status = props.demande.statut;
    return {
        en_attente: 'bg-yellow-100 text-yellow-700',
        approuvee: 'bg-green-100 text-green-700',
        rejetee: 'bg-red-100 text-red-700',
        annulee: 'bg-gray-100 text-gray-600'
    }[status] || 'bg-gray-100 text-gray-600';
});
</script>

<template>
<AuthenticatedLayout>
    <Head title="Détails de la demande" />

<Modal>
    <div class="space-y-6">
        <!-- Header -->
        <div class="">
            <div>
                <h1 class="text-3xl font-bold mb-1">Demande #{{ demande.numero }}</h1>
                <p class="">Créée le {{ formatDate(demande.created_at) }}</p>
            </div>
        </div>

        <!-- Motif & Status -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold mb-2">Motif</h2>
                    <p class="text-gray-700">{{ demande.motif || '—————' }}</p>
                </div>
                <div>
                    <span
                        :class="['px-3 py-1 rounded-full text-sm font-medium', statusClasses]"
                    >
                        {{ statusLabel }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Validation Information -->
        <div
            v-if="demande.statut === 'approuvee' || demande.statut === 'rejetee'"
            class="bg-white p-6 rounded-lg shadow-sm border"
        >
            <h2 class="text-lg font-semibold mb-4">Informations de validation</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-500 text-sm">Validé par :</p>
                    <p class="text-gray-800 font-medium">{{ demande.validateur || '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Date de validation :</p>
                    <p class="text-gray-800 font-medium">{{ demande.date_validation ? formatDate(demande.date_validation) : '—' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-500 text-sm">Commentaires :</p>
                    <p class="text-gray-800 font-medium">{{ demande.commentaire_validation || '—' }}</p>
                </div>
            </div>
        </div>

        <!-- Articles Table -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4">Articles demandés</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Article</th>
                            <th class="px-6 py-3 text-center font-medium text-gray-500 uppercase">Quantité demandée</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="item in demande.articles" :key="item.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ item.designation }}</td>
                            <td class="px-6 py-4 text-center">{{ item.quantite_demandee }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</Modal>
</AuthenticatedLayout>
</template>

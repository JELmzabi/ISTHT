<!-- resources/js/Components/Pagination.vue -->
<template>
    <div v-if="links.length > 3" class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
        <div class="flex flex-1 justify-between sm:hidden">
            <Link
                :href="links[0].url"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                :class="{ 'opacity-50 cursor-not-allowed': !links[0].url }"
                :preserve-state="true"
                :preserve-scroll="true"
            >
                Précédent
            </Link>
            <Link
                :href="links[links.length - 1].url"
                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                :class="{ 'opacity-50 cursor-not-allowed': !links[links.length - 1].url }"
                :preserve-state="true"
                :preserve-scroll="true"
            >
                Suivant
            </Link>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Affichage de
                    <span class="font-medium">{{ from }}</span>
                    à
                    <span class="font-medium">{{ to }}</span>
                    sur
                    <span class="font-medium">{{ total }}</span>
                    résultats
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <Link
                        v-for="(link, index) in links"
                        :key="index"
                        :href="link.url"
                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 focus:z-20 focus:outline-offset-0"
                        :class="{
                            'z-10 bg-blue-600 text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600': link.active,
                            'text-gray-900 hover:bg-gray-50 focus:outline-offset-0': !link.active && link.url,
                            'text-gray-400 cursor-not-allowed': !link.url,
                            'rounded-l-md': index === 0,
                            'rounded-r-md': index === links.length - 1
                        }"
                        v-html="link.label"
                        :preserve-state="true"
                        :preserve-scroll="true"
                    />
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    links: {
        type: Array,
        required: true
    },
    from: {
        type: Number,
        default: 0
    },
    to: {
        type: Number,
        default: 0
    },
    total: {
        type: Number,
        default: 0
    }
});
</script>
<script setup>
import { ref, computed } from 'vue'
import { Modal } from '@inertiaui/modal-vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    articles: Array,
})

const search = ref('')
const createCardexModal = ref(null)
const dropdownOpen = ref(false)

const form = useForm({
    article_id: null,
})

// Liste filtrée des articles selon la recherche
const filteredArticles = computed(() => {
    return props.articles.filter(a =>
        !search.value || a.designation.toLowerCase().includes(search.value.toLowerCase())
    )
})

function selectArticle(article) {
    form.article_id = article.id
    search.value = article.designation
    dropdownOpen.value = false
}

function closeIdle() {
    setTimeout(() => (dropdownOpen.value = false), 300)
}
</script>

<template>
    <Modal ref="createCardexModal">
        <!-- Header -->
        <div class="mb-2">
            <h2 class="text-lg font-semibold text-gray-900">Exporter le Cardex</h2>
            <p class="text-sm text-gray-500 mt-1">
                Sélectionnez l’article dont vous souhaitez exporter le (Cardex).
            </p>
        </div>

        <!-- Form -->
        <div class="grid gap-4 mt-4">
            <!-- Champ de recherche d'article -->
            <div class="relative mb-2">
                <input
                    type="text"
                    v-model="search"
                    placeholder="Rechercher un article..."
                    @focus="dropdownOpen = true"
                    @blur="closeIdle"
                    class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500"
                />

                <!-- Liste déroulante -->
                <ul
                    v-if="dropdownOpen && filteredArticles.length"
                    class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg"
                    style="max-height: 200px; overflow-y: auto;"
                >
                    <li
                        v-for="article in filteredArticles"
                        :key="article.id"
                        @click="selectArticle(article)"
                        class="px-3 py-2 hover:bg-indigo-100 cursor-pointer"
                    >
                        {{ article.designation }}
                    </li>
                </ul>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end gap-2">
                <button
                    type="button"
                    @click="createCardexModal.value.close()"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
                >
                    Annuler
                </button>

                <a
                    v-if="form.article_id"
                    :href="route('cardex.export', form.article_id)"
                    target="_blank"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                >
                    Exporter le PDF
                </a>

                <button
                    v-else
                    disabled
                    class="px-4 py-2 bg-blue-400 text-white rounded-lg opacity-70 cursor-not-allowed"
                >
                    Sélectionnez un article
                </button>
            </div>
        </div>
    </Modal>
</template>

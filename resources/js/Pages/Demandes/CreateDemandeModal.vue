<script setup>
import { ref, reactive, computed } from 'vue'
import { Modal } from '@inertiaui/modal-vue';
import { useForm } from '@inertiajs/vue3';
import Dump from '@/Components/Dump.vue';

const props = defineProps({
  articles: Array
})
const search = ref('');
const createDemandeModal = ref(null)
const dropdownOpen = ref(false)


const form = useForm({
  articles: [],
  fiche_technique: null,
  motif: '',
})

function handleFileUpload(event) {
  form.fiche_technique = event.target.files[0];
}

// Filter articles not yet added
const filteredArticles = computed(() => {
  return props.articles
    .filter(a => !form.articles.find(fa => fa.article_id === a.id))
    .filter(a => !search.value || a.designation.toLowerCase().includes(search.value.toLowerCase()))
})

function selectArticle(article) {
  article = {
    article_id: article.id,
    designation: article.designation
  }

  form.articles.push({ ...article, quantite: 1 })
  search.value = ''
  dropdownOpen.value = false
}

function removeArticle(index) {
  form.articles.splice(index, 1)
}

function submit() {
  form.post(route('demandes.store'), {
    onSuccess: () => {
      form.reset();
      dropdownOpen.value = false;
      createDemandeModal.value.close();
    },
  })
  
}

const articleErrors = computed(() => {
  return Object.entries(form.errors)
    .filter(([key]) => key.startsWith('articles.')) // only article errors
    .map(([_, message]) => message) // get just the messages
})


function closeIdle() {
  setTimeout(() => dropdownOpen.value = false, 300);
}
</script>

<template>
  <Modal ref="createDemandeModal">
    <!-- Header -->
    <div class="mb-4">
      <h2 class="text-lg font-semibold">Nouvelle Demande d’Articles</h2>
    </div>

    <!-- Body -->
    <div>
      <form @submit.prevent="submit" class="space-y-4">
        
        <!-- Fiche Technique Upload -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Fiche Technique
          </label>
          <input
            type="file"
            required
            @change="handleFileUpload"
            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          />

          <div v-if="form.errors.fiche_technique" class="text-red-600 text-sm mt-1">
            {{ form.errors.fiche_technique }}
          </div>

          <p v-if="form.fiche_technique" class="text-sm text-gray-500 mt-1">
            Fichier sélectionné : {{ form.fiche_technique.name }}
          </p>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Articles demandés
          </label>

          <div class="relative mb-2">
            <input
              type="text"
              v-model="search"
              placeholder="Rechercher un article..."
              @focus="dropdownOpen = true"
              @blur="closeIdle" 
              class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500"
            />

            <div v-if="form.errors.articles" class="text-red-600 text-sm mt-1">
              {{ form.errors.articles }}
            </div>

            <ul class="bg-red-300 text-red-900 border-red-500 border-1 rounded p-2 text-sm mt-2" v-if="articleErrors.length">
              <li v-for="error in articleErrors" :key="error">
                {{ error }}
              </li>
            </ul>

            <!-- Dropdown -->
            <ul
              v-if="dropdownOpen && filteredArticles.length"
              class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg"
              style="max-height: 200px; overflow-y: auto;"
            >
              <li
                v-for="article in filteredArticles"
                :key="article.id"
                @click="selectArticle(article)"
                class="px-3 py-2 hover:bg-indigo-200 cursor-pointer"
              >
                {{ article.designation }}
              </li>
            </ul>
          </div>

          <!-- Selected Articles Table -->
          <table class="w-full border border-gray-200 text-sm rounded-lg overflow-hidden">
            <thead class="bg-gray-50 text-gray-700">
              <tr>
                <th class="p-2 text-left">Article</th>
                <th class="p-2 text-center w-32">Quantité</th>
                <th class="p-2 w-10"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in form.articles" :key="item.id" class="border-t">
                <td class="p-2">{{ item.designation }}</td>
                <td class="p-2 text-center">
                  <input
                    type="number"
                    min="1"
                    v-model.number="item.quantite"
                    class="w-20 text-center border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                  />
                </td>
                <td class="p-2 text-center">
                  <button
                    type="button"
                    @click="removeArticle(index)"
                    class="text-red-500 hover:text-red-700"
                  >
                    ✕
                  </button>
                </td>
              </tr>
              <tr v-if="form.articles.length === 0">
                <td colspan="3" class="text-center text-gray-400 p-3">
                  Aucun article ajouté
                </td>
              </tr>
            </tbody>
          </table>
        </div>


        <!-- Motif -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Motif de la demande
          </label>
          <textarea
            v-model="form.motif"
            class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
            rows="2"
            placeholder="Ex: Besoin de fournitures de bureau"
          ></textarea>
        </div>

      </form>
    </div>

    <!-- Footer -->
    <div>
      <div class="flex justify-end space-x-3 pt-2">
        <button
          type="button"
          @click="$emit('close')"
          class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
        >
          Annuler
        </button>
        <button
          type="button"
          @click="submit"
          :disabled="form.processing"
          class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50"
        >
          Enregistrer
        </button>
      </div>
    </div>
  </Modal>
</template>


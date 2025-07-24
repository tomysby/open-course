<!-- Resources/js/Pages/Materials/Show.vue -->
<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div v-if="material.status !== 'approved'" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
          <div class="flex">
            <div class="flex-shrink-0">
              <ExclamationIcon class="h-5 w-5 text-yellow-400" />
            </div>
            <div class="ml-3">
              <p class="text-sm text-yellow-700">
                This material is {{ material.status }} and not publicly visible.
              </p>
            </div>
          </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-2">
          <span v-if="material.category" class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
            {{ material.category.name }}
          </span>
          <span 
            v-for="tag in material.tags" 
            :key="tag.id"
            class="px-3 py-1 bg-gray-100 text-gray-800 text-sm rounded-full"
          >
            {{ tag.name }}
          </span>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
          <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ material.title }}</h1>
            <div class="flex items-center text-sm text-gray-500 mb-4">
              <span>By {{ material.user.name }}</span>
              <span class="mx-2">•</span>
              <span>{{ material.created_at }}</span>
            </div>

            <div v-if="material.isArticle()" class="prose max-w-none">
              {{ material.content }}
            </div>

            <div v-else class="mt-4">
              <div v-if="material.isImage()" class="flex justify-center">
                <img :src="material.file_url" :alt="material.title" class="max-h-96">
              </div>

              <div v-else-if="material.isPdf()" class="h-screen">
                <iframe 
                  :src="material.file_url" 
                  class="w-full h-full"
                  frameborder="0"
                ></iframe>
              </div>

              <div v-else-if="material.isAudio()" class="mt-4">
                <audio controls class="w-full">
                  <source :src="material.file_url" :type="'audio/' + material.file_extension">
                  Your browser does not support the audio element.
                </audio>
              </div>

              <div v-else-if="material.isVideo()" class="mt-4">
                <video controls class="w-full" :poster="material.thumbnail_url">
                  <source :src="material.file_url" :type="'video/' + material.file_extension">
                  Your browser does not support the video tag.
                </video>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Comments :material="material" :comments="material.comments" />
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ExclamationIcon } from '@heroicons/vue/outline';

defineProps({
  material: Object
});
</script>
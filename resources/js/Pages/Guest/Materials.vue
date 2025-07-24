<template>
    <div class="min-h-screen bg-gray-50">
      <!-- Navigation -->
      <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <div class="flex-shrink-0 flex items-center">
                <Link :href="route('materials.index')">
                  <ApplicationLogo class="block h-9 w-auto" />
                </Link>
              </div>
            </div>
            <div class="flex items-center space-x-4">
              <Link :href="route('login')" class="text-gray-600 hover:text-gray-900">
                Masuk
              </Link>
              <Link :href="route('register')" class="text-gray-600 hover:text-gray-900">
                Daftar
              </Link>
            </div>
          </div>
        </div>
      </nav>
  
      <!-- Header -->
      <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold text-gray-900">Materi Edukasi</h1>
        </div>
      </header>
  
      <!-- Main Content -->
      <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="material in materials.data" :key="material.id" 
                 class="bg-white overflow-hidden shadow rounded-lg">
              <!-- Thumbnail -->
              <img :src="material.thumbnail_path || '/images/default-thumbnail.png'" 
                   :alt="material.title"
                   class="w-fit h-fit object-cover">
              
              <div class="p-4">
                <div class="mt-4 flex justify-between items-center">
                  <span class="text-sm text-gray-500">
                    {{ material.category?.name || 'Uncategorized' }}
                  </span>
                  <div class="text-sm">
                    <Link :href="route('login')" 
                          class="text-blue-600 hover:text-blue-800">
                      Masuk untuk melihat
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Empty State -->
          <div v-if="materials.data.length === 0" class="text-center py-12">
            <p class="text-gray-500">Belum ada materi untuk ditampilkan.</p>
          </div>
  
          <!-- Pagination -->
          <div class="mt-6">
            <Pagination :links="materials.links" />
          </div>
  
          <!-- Call to Action -->
          <div class="mt-12 bg-white shadow rounded-lg p-6 text-center">
            <h3 class="text-lg font-medium text-gray-900">Siap berkontribusi?</h3>
            <p class="mt-2 text-sm text-gray-600">
                Bergabunglah dengan komunitas kami dan bagikan pengetahuan Anda dengan orang lain.
            </p>
            <div class="mt-6">
              <Link :href="route('register')" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Mulai
              </Link>
            </div>
          </div>
        </div>
      </main>
    </div>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3';
  import ApplicationLogo from '@/Components/ApplicationLogo.vue';
  import Pagination from '@/Components/Pagination.vue';
  
  defineProps({
    materials: Object,
  });
  </script>
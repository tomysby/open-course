<template>
    <AuthenticatedLayout>
      <template #header>
        <div class="flex justify-between items-center">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isSearch ? `Hasil pencarian untuk "${filters.search}"` : 'Semua Kursus' }}
          </h2>
          <div class="w-1/3">
            <SearchCourses />
          </div>
        </div>
      </template>
  
      <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div v-if="courses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="course in courses" 
              :key="course.id" 
              class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200"
            >
              <Link :href="route('courses.show', course.slug)">
                <div class="h-48 bg-gray-200 overflow-hidden">
                  <img 
                    v-if="course.thumbnail" 
                    :src="course.thumbnail" 
                    :alt="course.title"
                    class="w-full h-full object-cover"
                  >
                  <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                </div>
                <div class="p-4">
                  <div class="flex justify-between items-start">
                    <h3 class="text-lg font-semibold text-gray-900">{{ course.title }}</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                      {{ course.category.name }}
                    </span>
                  </div>
                  <p class="mt-2 text-gray-600 line-clamp-2">{{ course.description }}</p>
                  <div class="mt-4 flex items-center justify-between">
                    <span class="text-sm text-gray-500">Lihat detail</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                  </div>
                </div>
              </Link>
            </div>
          </div>
          <div v-else class="text-center py-12 bg-white rounded-lg shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">
              {{ isSearch ? 'Tidak ditemukan hasil pencarian' : 'Belum ada kursus tersedia' }}
            </h3>
            <p class="mt-1 text-gray-500">
              {{ isSearch ? 'Coba dengan kata kunci lain' : 'Silakan kembali lagi nanti' }}
            </p>
            <div class="mt-6">
              <Link 
                v-if="isSearch"
                :href="route('courses.index')" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Lihat Semua Kursus
              </Link>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  
  defineProps({
    courses: Array,
    filters: Object,
    isSearch: Boolean
  });
  </script>
  
  <style scoped>
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  </style>
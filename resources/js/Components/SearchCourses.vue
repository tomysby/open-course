<template>
    <div class="relative">
      <div class="relative">
        <input
          type="text"
          v-model="searchQuery"
          @input="performSearch"
          placeholder="Cari kursus..."
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <div v-if="isLoading" class="absolute right-3 top-2.5">
          <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>
      </div>
      
      <div 
        v-if="searchResults.length > 0 && searchQuery.length > 0" 
        class="absolute z-50 mt-1 w-full bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200 max-h-96 overflow-y-auto"
      >
        <div 
          v-for="result in searchResults" 
          :key="result.id"
          class="p-3 hover:bg-gray-50 cursor-pointer border-b last:border-b-0 transition-colors duration-200"
          @click="goToCourse(result)"
        >
          <div class="flex items-start">
            <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-md flex items-center justify-center mr-3">
              <span v-if="!result.thumbnail" class="text-gray-500 text-xs">No Image</span>
              <img v-else :src="result.thumbnail" :alt="result.title" class="h-full w-full object-cover rounded-md">
            </div>
            <div>
              <div class="font-medium text-gray-900">{{ result.title }}</div>
              <div class="text-sm text-gray-500">{{ result.category.name }}</div>
              <div class="text-xs text-gray-400 mt-1 line-clamp-2">{{ result.description }}</div>
            </div>
          </div>
        </div>
        <div v-if="searchResults.length === 0 && searchQuery.length >= 2" class="p-3 text-center text-gray-500">
          Tidak ditemukan hasil untuk "{{ searchQuery }}"
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { router } from '@inertiajs/vue3';
  import _ from 'lodash';
  
  const searchQuery = ref('');
  const searchResults = ref([]);
  const isLoading = ref(false);
  
  const performSearch = _.debounce(() => {
    if (searchQuery.value.length < 2) {
      searchResults.value = [];
      return;
    }
    
    isLoading.value = true;
    
    router.get(route('courses.search'), { search: searchQuery.value }, {
      preserveState: true,
      replace: true,
      onSuccess: (page) => {
        searchResults.value = page.props.courses || [];
      },
      onError: () => {
        searchResults.value = [];
      },
      onFinish: () => {
        isLoading.value = false;
      }
    });
  }, 300);
  
  const goToCourse = (course) => {
    router.visit(route('courses.show', { course: course.slug }));
    searchResults.value = [];
    searchQuery.value = '';
  };
  </script>
  
  <style scoped>
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  </style>
<template>
    <div class="space-y-4">
      <div v-for="course in courses" :key="course.id" class="flex items-start p-4 border rounded-lg hover:bg-gray-50 transition-colors">
        <div class="flex-shrink-0 h-16 w-16 bg-gray-200 rounded-md overflow-hidden">
          <img v-if="course.thumbnail" :src="course.thumbnail" :alt="course.title" class="h-full w-full object-cover">
          <div v-else class="h-full w-full flex items-center justify-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
        </div>
        <div class="ml-4 flex-1">
          <div class="flex items-center justify-between">
            <h4 class="text-md font-medium text-gray-900">{{ course.title }}</h4>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
              {{ course.category.name }}
            </span>
          </div>
          <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ course.description }}</p>
          <div class="mt-2 flex items-center justify-between">
            <div class="flex items-center text-sm text-gray-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              {{ course.created_at }}
            </div>
            <Link :href="route('courses.show', course.slug)" class="text-sm text-blue-600 hover:text-blue-800">
              View Course
            </Link>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3';
  
  defineProps({
    courses: Array
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
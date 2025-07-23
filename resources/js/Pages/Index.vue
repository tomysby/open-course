<template>
    <AuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">All Courses</h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="course in courses.data" :key="course.id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <Link :href="route('courses.show', course.slug)">
                <img :src="course.thumbnail || '/images/default-thumbnail.jpg'" :alt="course.title" class="w-full h-48 object-cover">
                <div class="p-6">
                  <h3 class="text-lg font-semibold">{{ course.title }}</h3>
                  <p class="text-gray-600 mt-2">{{ course.description.substring(0, 100) }}...</p>
                  <div class="mt-4 flex items-center justify-between">
                    <span class="text-sm text-gray-500">{{ course.category.name }}</span>
                    <span class="text-sm text-gray-500">{{ course.user.name }}</span>
                  </div>
                </div>
              </Link>
            </div>
          </div>
  
          <div class="mt-6">
            <Pagination :links="courses.links" />
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Link } from '@inertiajs/vue3';
  import Pagination from '@/Components/Pagination.vue';
  
  defineProps({
    courses: Object,
  });
  </script>
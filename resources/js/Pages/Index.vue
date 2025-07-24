<template>
  <AuthenticatedLayout>
      <template #header>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">All Courses</h2>
      </template>

      <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                  <div v-for="course in courses.data" :key="course.id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                      <!-- Thumbnail -->
                      <img :src="course.thumbnail || '/images/default-thumbnail.jpg'" 
                           :alt="course.title" 
                           class="w-full h-48 object-cover">
                      
                      <div class="p-6">
                          <h3 class="text-lg font-semibold">{{ course.title }}</h3>
                          
                          <!-- Untuk guest -->
                          <template v-if="!$page.props.auth.user">
                              <p class="text-gray-600 mt-2">Login to view course details</p>
                              <div class="mt-4">
                                  <Link :href="route('login')" 
                                        class="text-blue-600 hover:text-blue-800">
                                      Login
                                  </Link>
                                  <span class="mx-2 text-gray-400">or</span>
                                  <Link :href="route('register')" 
                                        class="text-blue-600 hover:text-blue-800">
                                      Register
                                  </Link>
                              </div>
                          </template>
                          
                          <!-- Untuk user yang login -->
                          <template v-else>
                              <p class="text-gray-600 mt-2">{{ course.excerpt }}</p>
                              <Link :href="route('courses.show', course.slug)"
                                    class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                                  View Course
                              </Link>
                          </template>
                      </div>
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
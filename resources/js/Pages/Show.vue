<template>
    <AuthenticatedLayout>
      <template #header>
        <div class="flex justify-between items-center">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ course.title }}</h2>
          <div v-if="$page.props.auth.user?.isAdmin && !course.is_published" class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded text-sm">
            Pending Approval
          </div>
        </div>
      </template>
  
      <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <div class="flex flex-col md:flex-row gap-6">
                <div class="md:w-1/4">
                  <div class="sticky top-6">
                    <h3 class="font-semibold text-lg mb-4">Course Materials</h3>
                    <ul class="space-y-2">
                      <li v-for="material in materials" :key="material.id">
                        <Link :href="route('materials.show', { course: course.slug, material: material.id })" 
                          class="block px-3 py-2 rounded hover:bg-gray-100"
                          :class="{ 'bg-blue-50 text-blue-600': $page.url.includes(`/materials/${material.id}`) }">
                          {{ material.title }}
                        </Link>
                      </li>
                    </ul>
                  </div>
                </div>
                
                <div class="md:w-3/4">
                  <slot />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Link } from '@inertiajs/vue3';
  
  defineProps({
    course: Object,
    materials: Array,
  });
  </script>
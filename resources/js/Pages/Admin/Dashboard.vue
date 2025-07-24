<template>
    <AuthenticatedLayout>
      <template #header>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          Admin Dashboard
        </h2>
      </template>
  
      <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <!-- Stats Cards -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <StatCard 
              title="Jumlah Pengguna" 
              :value="stats.total_users" 
              icon="users"
              color="blue"
            />
            <StatCard 
              title="Jumlah Materi" 
              :value="stats.total_courses" 
              icon="academic-cap"
              color="green"
            />
            <StatCard 
              title="Materi Pending" 
              :value="stats.pending_courses" 
              icon="clock"
              color="yellow"
            />
            <StatCard 
              title="Kategori" 
              :value="stats.total_categories" 
              icon="tag"
              color="purple"
            />
          </div>
  
          <!-- Two Column Layout -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Users -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                  <h3 class="text-lg font-medium text-gray-900">Pengguna Terbaru</h3>
                  <Link :href="route('admin.users')" class="text-sm text-blue-600 hover:text-blue-800">
                    View All
                  </Link>
                </div>
                <UserList :users="recentUsers" />
              </div>
            </div>
  
            <!-- Recent Courses -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                  <h3 class="text-lg font-medium text-gray-900">Materi Terbaru</h3>
                  <Link :href="route('admin.courses')" class="text-sm text-blue-600 hover:text-blue-800">
                    View All
                  </Link>
                </div>
                <EducationalMaterialList :courses="recentCourses" admin-view />
              </div>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import StatCard from '@/Components/StatCard.vue';
  import UserList from '@/Components/Admin/UserList.vue';
  import { Link } from '@inertiajs/vue3';
  import EducationalMaterialList from '@/Components/EducationalMaterialList.vue';
  
  defineProps({
    stats: Object,
    recentUsers: Array,
    recentCourses: Array,
    progressData: Object,
  });
  </script>
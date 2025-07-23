<template>
    <Head title="Dashboard" />
  
    <AuthenticatedLayout>
      <template #header>
        <div class="flex justify-between items-center">
          <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Dashboard Overview
          </h2>
          <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Last updated: {{ new Date().toLocaleDateString() }}</span>
          </div>
        </div>
      </template>
  
      <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <!-- Stats Cards -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <StatCard 
              title="Total Courses" 
              :value="stats.courses" 
              icon="academic-cap"
              color="blue"
              :change="12"
            />
            <StatCard 
              title="Active Students" 
              :value="stats.students" 
              icon="users"
              color="green"
              :change="8"
            />
            <StatCard 
              title="New Materials" 
              :value="stats.materials" 
              icon="document-text"
              color="indigo"
              :change="5"
            />
            <StatCard 
              title="Completion Rate" 
              :value="stats.completion_rate + '%'" 
              icon="check-circle"
              color="purple"
              :change="3"
            />
          </div>
  
          <!-- Main Content Grid -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Courses -->
            <div class="lg:col-span-2">
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                  <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Recent Courses</h3>
                    <Link :href="route('courses.index')" class="text-sm text-blue-600 hover:text-blue-800">
                      View All
                    </Link>
                  </div>
                  <CourseList :courses="recentCourses" />
                </div>
              </div>
            </div>
  
            <!-- Quick Actions & Progress -->
            <div class="space-y-6">
              <!-- Quick Actions -->
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                  <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                  <div class="space-y-3">
                    <Link 
                      :href="route('courses.create')" 
                      class="flex items-center p-3 border rounded-lg hover:bg-gray-50 transition-colors"
                    >
                      <svg class="h-5 w-5 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                      </svg>
                      <span>Create New Course</span>
                    </Link>
                    <Link 
                      :href="route('profile.edit')" 
                      class="flex items-center p-3 border rounded-lg hover:bg-gray-50 transition-colors"
                    >
                      <svg class="h-5 w-5 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                      <span>Update Profile</span>
                    </Link>
                  </div>
                </div>
              </div>
  
              <!-- Learning Progress -->
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                  <h3 class="text-lg font-medium text-gray-900 mb-4">Your Progress</h3>
                  <ProgressChart v-if="progressData && progressData.courses" :progress="progressData" />
                    <div v-else class="text-center py-6 text-gray-500">
                        No progress data available
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import CourseList from '@/Components/CourseList.vue';
import ProgressChart from '@/Components/ProgressChart.vue';

defineProps({
  stats: {
    type: Object,
    required: true,
    default: () => ({
      courses: 0,
      students: 0,
      materials: 0,
      completion_rate: 0
    })
  },
  recentCourses: {
    type: Array,
    required: true,
    default: () => []
  },
  progressData: {
    type: Object,
    required: true,
    default: () => ({
      percentage: 0,
      courses: []
    })
  }
});
</script>
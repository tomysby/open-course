<!-- Resources/js/Pages/Admin/Courses/Index.vue -->
<template>
  <AuthenticatedLayout>
      <template #header>
          <h2 class="text-xl font-semibold leading-tight text-gray-800">
              Courses
          </h2>
      </template>
      
      <div class="py-6">
          <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
              <div class="mb-6 flex justify-end">
                  <Link :href="route('admin.courses.create')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                      Create New Course
                  </Link>
              </div>
              <SearchCourses :filters="filters" />
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <div class="p-6">
                      <div class="overflow-x-auto">
                          <table class="min-w-full divide-y divide-gray-200">
                              <thead class="bg-gray-50">
                                  <tr>
                                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                          Title
                                      </th>
                                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                          Category
                                      </th>
                                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                          Status
                                      </th>
                                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                          Actions
                                      </th>
                                  </tr>
                              </thead>
                              <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="course in courses.data" :key="course.id">
                                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                          {{ course.title }}
                                      </td>
                                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                          {{ course.category?.name || 'Uncategorized' }}
                                      </td>
                                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                          <span :class="statusClasses(course.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                              {{ course.status }}
                                          </span>
                                      </td>
                                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                          <Link :href="route('admin.courses.edit', course.id)" class="text-blue-600 hover:text-blue-900 mr-3">Edit</Link>
                                          <button @click="deleteCourse(course.id)" class="text-red-600 hover:text-red-900">Delete</button>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                          <div class="mt-4 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Showing {{ courses.from }} to {{ courses.to }} of {{ courses.total }} results
                            </div>
                            <div class="flex space-x-2">
                                <Link 
                                    v-if="courses.prev_page_url"
                                    :href="courses.prev_page_url"
                                    class="px-3 py-1 rounded border border-gray-300 text-gray-700 hover:bg-gray-50"
                                >
                                    Previous
                                </Link>
                                <Link 
                                    v-if="courses.next_page_url"
                                    :href="courses.next_page_url"
                                    class="px-3 py-1 rounded border border-gray-300 text-gray-700 hover:bg-gray-50"
                                >
                                    Next
                                </Link>
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import SearchCourses from '@/Components/Admin/SearchCourses.vue';

defineProps({
    courses: Object,
    filters: Object
});

const deleteCourse = (id) => {
  if (confirm('Are you sure you want to delete this course?')) {
      router.delete(route('admin.courses.destroy', id));
  }
};

const statusClasses = (status) => {
  return {
      'bg-green-100 text-green-800': status === 'published',
      'bg-yellow-100 text-yellow-800': status === 'pending',
      'bg-red-100 text-red-800': status === 'rejected',
  };
};
  
  const approveCourse = (course) => {
    router.post(route('admin.courses.approve', course.id), {}, {
      preserveScroll: true,
    });
  };
  
</script>
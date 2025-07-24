<!-- Resources/js/Pages/Admin/Tags/Index.vue -->
<template>
  <AuthenticatedLayout>
      <template #header>
          <h2 class="text-xl font-semibold leading-tight text-gray-800">
              Tags
          </h2>
      </template>

      <div class="py-6">
          <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
              <div class="mb-6 flex justify-end">
                  <Link :href="route('admin.tags.create')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                      Create New Tag
                  </Link>
              </div>

              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <div class="p-6">
                      <div class="overflow-x-auto">
                          <table class="min-w-full divide-y divide-gray-200">
                              <thead class="bg-gray-50">
                                  <tr>
                                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                          Name
                                      </th>
                                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                          Slug
                                      </th>
                                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                          Actions
                                      </th>
                                  </tr>
                              </thead>
                              <tbody class="bg-white divide-y divide-gray-200">
                                  <tr v-for="category in tags" :key="category.id">
                                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                          {{ category.name }}
                                      </td>
                                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                          {{ category.slug }}
                                      </td>
                                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                          <Link :href="route('admin.tags.edit', category.id)" class="text-blue-600 hover:text-blue-900 mr-3">Edit</Link>
                                          <button @click="deleteTag(category.id)" class="text-red-600 hover:text-red-900">Delete</button>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
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

defineProps({
  tags: Array
});

const deleteTag = (id) => {
  if (confirm('Are you sure you want to delete this category?')) {
      router.delete(route('admin.tags.destroy', id));
  }
};
</script>
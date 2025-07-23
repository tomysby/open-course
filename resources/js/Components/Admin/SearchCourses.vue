<!-- Resources/js/Components/Admin/SearchCourses.vue -->
<template>
    <div class="mb-6">
      <div class="flex items-center">
        <input
          type="text"
          v-model="form.search"
          @input="handleSearch"
          placeholder="Search courses..."
          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
          :disabled="form.processing"
        />
        <button 
          @click="resetSearch"
          class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300"
          :disabled="form.processing"
        >
          <span v-if="form.processing">...</span>
          <span v-else>Reset</span>
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { useForm, router } from '@inertiajs/vue3';
  import { watch, ref } from 'vue';
  import { debounce } from 'lodash';
  
  const props = defineProps({
    filters: Object
  });
  
  const form = useForm({
    search: props.filters?.search || ''
  });
  
  // Debounce the search input
  const handleSearch = debounce(() => {
    router.get(route('admin.courses'), 
    { search: form.search }, 
    {
      preserveState: true,
      replace: true,
      preserveScroll: true
    });
  }, 300);
  
  const resetSearch = () => {
    form.search = '';
    router.get(route('admin.courses'), {}, {
      preserveState: true,
      replace: true,
      preserveScroll: true
    });
  };
  </script>
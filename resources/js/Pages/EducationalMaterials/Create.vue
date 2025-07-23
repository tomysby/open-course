<!-- Resources/js/Pages/EducationalMaterials/Create.vue -->
<template>
    <AuthenticatedLayout>
      <template #header>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          Upload Educational Material
        </h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <form @submit.prevent="submit" class="bg-white shadow-sm rounded-lg p-6">
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                Title *
              </label>
              <input v-model="form.title" id="title" type="text" class="input-field">
              <p v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</p>
            </div>
  
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">
                Type *
              </label>
              <select v-model="form.type" @change="handleTypeChange" class="input-field">
                <option v-for="type in types" :key="type" :value="type">
                  {{ type.charAt(0).toUpperCase() + type.slice(1) }}
                </option>
              </select>
            </div>
  
            <div v-if="form.type === 'article'" class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">
                Content *
              </label>
              <textarea v-model="form.content" rows="10" class="input-field"></textarea>
              <p v-if="form.errors.content" class="text-red-500 text-xs mt-1">{{ form.errors.content }}</p>
            </div>
  
            <div v-else class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">
                File *
              </label>
              <input type="file" @input="form.file = $event.target.files[0]" class="input-field">
              <p class="text-gray-500 text-xs mt-1">
                Max size: 
                <span v-if="form.type === 'image'">2MB (JPEG, PNG, JPG, GIF)</span>
                <span v-if="form.type === 'pdf'">5MB (PDF)</span>
                <span v-if="form.type === 'audio'">10MB (MP3, WAV)</span>
                <span v-if="form.type === 'video'">20MB (MP4, MOV, AVI)</span>
              </p>
              <p v-if="form.errors.file" class="text-red-500 text-xs mt-1">{{ form.errors.file }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Category
                </label>
                <select v-model="form.category_id" class="input-field">
                    <option value="">Select a category</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Tags
                </label>
                <Multiselect
                    v-model="form.tags"
                    mode="tags"
                    :options="tagsOptions"
                    placeholder="Select tags"
                    :close-on-select="false"
                    :searchable="true"
                />
            </div>
  
            <div class="flex items-center justify-end">
              <button type="submit" class="btn-primary" :disabled="form.processing">
                Submit for Review
              </button>
            </div>
          </form>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { useForm } from '@inertiajs/vue3';
  import Multiselect from '@vueform/multiselect';
  
  const props = defineProps({
    types: Array,
    categories: Array,
    tags: Array
  });

  const tagsOptions = props.tags.map(tag => ({
    value: tag.id,
    label: tag.name
  }));
  
  const form = useForm({
    title: '',
    type: 'article',
    content: '',
    file: null,
    category_id: null,
    tags: []
  });
  
  const handleTypeChange = () => {
    form.content = '';
    form.file = null;
  };
  
  const submit = () => {
    const route = route('materials.store');
    
    if (form.type === 'article') {
      form.post(route);
    } else {
      form.post(route, {
        preserveScroll: true,
        onSuccess: () => form.reset(),
      });
    }
  };
  </script>
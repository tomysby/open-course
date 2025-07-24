<template>
    <AuthenticatedLayout>
      <template #header>
        <div class="flex items-center justify-between">
          <h2 class="text-2xl font-bold text-gray-800">
            Upload Educational Material
          </h2>
          <Link 
            :href="route('materials.index')" 
            class="text-sm font-medium text-blue-600 hover:text-blue-900 flex items-center gap-1"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Materials
          </Link>
        </div>
      </template>
  
      <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
              <h3 class="text-lg font-medium text-gray-900">Material Details</h3>
              <p class="mt-1 text-sm text-gray-500">Fill in the details below to submit a new educational material for review.</p>
            </div>
  
            <form @submit.prevent="submit" class="p-6 space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Title <span class="text-red-500">*</span>
                </label>
                <input 
                  v-model="form.title" 
                  id="title" 
                  type="text" 
                  class="form-input"
                  placeholder="Enter material title"
                >
                <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
              </div>
  
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Type <span class="text-red-500">*</span>
                  </label>
                  <select 
                    v-model="form.type" 
                    @change="handleTypeChange" 
                    class="form-select"
                  >
                    <option v-for="type in types" :key="type" :value="type">
                      {{ type.charAt(0).toUpperCase() + type.slice(1) }}
                    </option>
                  </select>
                </div>
  
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Category
                  </label>
                  <select 
                    v-model="form.category_id" 
                    class="form-select"
                  >
                    <option value="">Select a category</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                      {{ category.name }}
                    </option>
                  </select>
                </div>
              </div>
  
              <div v-if="form.type === 'article'">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Content <span class="text-red-500">*</span>
                </label>
                <textarea 
                  v-model="form.content" 
                  rows="10" 
                  class="form-textarea"
                  placeholder="Enter article content..."
                ></textarea>
                <p v-if="form.errors.content" class="mt-1 text-sm text-red-600">{{ form.errors.content }}</p>
              </div>
  
              <div v-else>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  File <span class="text-red-500">*</span>
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                  <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                      <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                      <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                        <span>Upload a file</span>
                        <input type="file" @change="form.file = $event.target.files[0]" class="sr-only">
                      </label>
                      <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">
                      <template v-if="form.type === 'image'">PNG, JPG, GIF up to 2MB</template>
                      <template v-else-if="form.type === 'pdf'">PDF up to 5MB</template>
                      <template v-else-if="form.type === 'audio'">MP3, WAV up to 10MB</template>
                      <template v-else-if="form.type === 'video'">MP4, MOV, AVI up to 20MB</template>
                    </p>
                  </div>
                </div>
                <p v-if="form.errors.file" class="mt-1 text-sm text-red-600">{{ form.errors.file }}</p>
              </div>
  
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Tags
                </label>
                <Multiselect
                  v-model="form.tags"
                  mode="tags"
                  :options="tagsOptions"
                  placeholder="Select tags"
                  :close-on-select="false"
                  :searchable="true"
                  class="form-multiselect"
                />
              </div>
  
              <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                <button 
                  type="submit" 
                  class="btn-primary flex items-center gap-2"
                  :disabled="form.processing"
                >
                  <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                  Submit for Review
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { useForm, Link, router  } from '@inertiajs/vue3';
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
    if (form.type !== 'article' && form.file) {
        const maxSize = {
            image: 2 * 1024 * 1024,
            pdf: 5 * 1024 * 1024,
            audio: 10 * 1024 * 1024,
            video: 20 * 1024 * 1024,
        }[form.type];
        
        if (form.file.size > maxSize) {
            alert(`File size exceeds maximum allowed for ${form.type} (${maxSize/1024/1024}MB)`);
            return;
        }
    }
    // Use router instead of route to avoid naming conflict
    if (form.type === 'article') {
        form.post(route('materials.store'), {
            preserveScroll: true,
            onSuccess: () => form.reset(),
            forceFormData: form.type !== 'article', // Only force form data for file uploads
            onError: (errors) => {
                form.errors = errors;
            }            
        });
    } else {
    // For file uploads, we need to convert the form to FormData
    const formData = new FormData();
    formData.append('title', form.title);
    formData.append('type', form.type);
    formData.append('category_id', form.category_id || '');
    formData.append('file', form.file);
    
    // Handle tags if present
    if (form.tags && form.tags.length > 0) {
      form.tags.forEach(tag => {
        formData.append('tags[]', tag);
      });
    }

    // Use axios or Inertia's router for the file upload
    router.post(router.route('materials.store'), formData, {
      preserveScroll: true,
      onSuccess: () => form.reset(),
      onError: (errors) => {
        form.errors = errors;
      },
      onProgress: (progress) => {
            console.log(`Upload progress: ${progress.percentage}%`);
        }
    });
  }
};
  </script>
  
  <style scoped>
  .btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring-2 focus:ring-blue-200 transition ease-in-out duration-150;
  }
  
  .form-input {
    @apply block w-full rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300;
  }
  
  .form-select {
    @apply block w-full rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300;
  }
  
  .form-textarea {
    @apply block w-full rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300;
  }
  
  .form-multiselect {
    @apply block w-full rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300;
  }
  </style>
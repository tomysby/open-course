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
                <div 
                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg drop-zone"
                    @drop.prevent="handleDrop"
                    @dragover.prevent="handleDragOver"
                    :class="{ 'drop-zone-active': form.type !== 'article' && isDragging }"
                    >
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                            <span>Upload a file</span>
                            <input 
                            type="file" 
                            @change="form.file = $event.target.files[0]" 
                            class="sr-only"
                            :accept="fileAccept"
                            >
                        </label>
                        <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">
                        <template v-if="form.type === 'image'">PNG, JPG, GIF up to 2MB</template>
                        <template v-else-if="form.type === 'pdf'">PDF up to 5MB</template>
                        <template v-else-if="form.type === 'audio'">MP3, WAV up to 10MB</template>
                        <template v-else-if="form.type === 'video'">MP4, MOV, AVI up to 20MB</template>
                        </p>
                        <p v-if="form.file" class="text-sm text-green-600 mt-2">
                        File selected: {{ form.file.name }} ({{ (form.file.size / 1024 / 1024).toFixed(2) }}MB)
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
                    >
                    <template #tag="{ option, handleTagRemove }">
                        <div class="multiselect-tag is-primary bg-blue-100 text-blue-800 rounded-full py-1 px-3 flex items-center text-sm">
                        {{ option.label }}
                        <span 
                            @click="handleTagRemove(option, $event)"
                            class="multiselect-tag-remove ml-1 hover:text-blue-600"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        </div>
                    </template>
                    </Multiselect>
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
  // Handle file drop
    const handleDrop = (e) => {
    e.preventDefault();
    if (form.type === 'article') return;
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        form.file = files[0];
    }
    };

    const handleDragOver = (e) => {
    e.preventDefault();
    };

  const submit = () => {
    // File validation
    if (form.type !== 'article') {
        if (!form.file) {
        alert('Please select a file');
        return;
        }

        const maxSizes = {
        image: 2 * 1024 * 1024,
        pdf: 5 * 1024 * 1024,
        audio: 10 * 1024 * 1024,
        video: 20 * 1024 * 1024,
        };

        if (form.file.size > maxSizes[form.type]) {
        alert(`File size exceeds maximum allowed for ${form.type} (${maxSizes[form.type]/1024/1024}MB)`);
        return;
        }

        // File type validation
        const validExtensions = {
        image: ['image/jpeg', 'image/png', 'image/gif'],
        pdf: ['application/pdf'],
        audio: ['audio/mpeg', 'audio/wav'],
        video: ['video/mp4', 'video/quicktime', 'video/x-msvideo']
        };

        if (!validExtensions[form.type].includes(form.file.type)) {
        alert(`Invalid file type for ${form.type}`);
        return;
        }
    }

    // Submit form
    form.post(route('materials.store'), {
        preserveScroll: true,
        forceFormData: true, // Always use FormData for file uploads
        onSuccess: () => form.reset(),
        onError: (errors) => {
        console.error('Submission error:', errors);
        },
        onProgress: (progress) => {
        console.log(`Upload progress: ${progress.percentage}%`);
        }
    });
    };
  </script>
  
  <style scoped>
  .drop-zone {
  @apply border-2 border-dashed border-gray-300 rounded-lg;
}

.drop-zone-active {
  @apply border-blue-500 bg-blue-50;
}

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

  .multiselect-tag {
  display: inline-flex;
  align-items: center;
  margin-right: 0.5rem;
  margin-bottom: 0.25rem;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  background-color: #e0f2fe;
  color: #0369a1;
  font-size: 0.875rem;
  line-height: 1.25rem;
}

.multiselect-tag-remove {
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  padding-left: 0.25rem;
  opacity: 0.7;
}

.multiselect-tag-remove:hover {
  opacity: 1;
}

/* Style untuk dropdown options */
:deep(.multiselect-option) {
  padding: 0.5rem 1rem;
}

:deep(.multiselect-option.is-selected) {
  background-color: #3b82f6;
  color: white;
}

:deep(.multiselect-option.is-pointed) {
  background-color: #f3f4f6;
  color: #1f2937;
}
  </style>
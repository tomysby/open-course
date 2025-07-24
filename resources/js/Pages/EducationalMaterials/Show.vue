<template>
    <AuthenticatedLayout>
      <template #header>
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-800">
            Detail Materi: {{ material.title }}
          </h2>
          <Link 
            :href="route('materials.index')" 
            class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Daftar
          </Link>
        </div>
      </template>
  
      <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Header Info -->
            <div class="px-6 py-4 bg-gray-50 border-b">
              <div class="flex flex-wrap justify-between items-center">
                <div>
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" 
                        :class="statusColor(material.status)">
                    {{ materialStatus(material.status) }}
                  </span>
                </div>
                <div class="text-sm text-gray-500">
                  Diunggah oleh: {{ material.user.name }} • 
                  {{ formatDate(material.created_at) }}
                </div>
              </div>
            </div>
  
            <!-- Main Content -->
            <div class="p-6">
              <!-- Meta Information -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                  <h3 class="text-sm font-medium text-gray-500">Jenis Materi</h3>
                  <p class="mt-1 text-sm text-gray-900 capitalize">{{ material.type }}</p>
                </div>
                <div>
                  <h3 class="text-sm font-medium text-gray-500">Kategori</h3>
                  <p class="mt-1 text-sm text-gray-900">{{ material.category?.name || '-' }}</p>
                </div>
                <div>
                  <h3 class="text-sm font-medium text-gray-500">Tag</h3>
                  <div class="mt-1 flex flex-wrap gap-2">
                    <span v-for="tag in material.tags" :key="tag.id" 
                          class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                      {{ tag.name }}
                    </span>
                    <span v-if="material.tags.length === 0">-</span>
                  </div>
                </div>
                <div v-if="material.status === 'rejected'">
                  <h3 class="text-sm font-medium text-gray-500">Alasan Penolakan</h3>
                  <p class="mt-1 text-sm text-red-600">{{ material.rejection_reason }}</p>
                </div>
              </div>
  
              <!-- Content Preview -->
              <div class="mt-6 border-t pt-6">
                <h3 class="text-lg font-medium mb-4">Isi Materi</h3>
                
                <div v-if="material.type === 'article'" class="prose max-w-none">
                  <div v-html="material.content"></div>
                </div>
                
                <div v-else-if="material.type === 'image'" class="flex justify-center">
                  <img :src="'/storage/' + material.file_path" 
                       class="max-w-full h-auto rounded-lg shadow-md"
                       :alt="material.title">
                </div>
                
                <div v-else-if="material.type === 'pdf'" class="w-full h-screen">
                  <iframe :src="route('materials.stream', material.file_path)" 
                          class="w-full h-full border rounded-lg"></iframe>
                </div>
                
                <div v-else-if="material.type === 'video'" class="w-full">
                    <video controls class="w-full rounded-lg" :poster="material.thumbnail_path ? route('materials.stream', material.thumbnail_path.split('/').pop()) : ''">
                        <source :src="route('materials.stream', material.file_path)" type="video/mp4">
                        Browser Anda tidak mendukung pemutaran video.
                    </video>
                    <div class="mt-2 text-sm text-gray-500">
                        <a :href="route('materials.stream', material.file_path.split('/').pop())" 
                        download 
                        class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Video
                        </a>
                    </div>
                </div>
                
                <div v-else-if="material.type === 'audio'" class="w-full">
                  <audio controls class="w-full">
                    <source :src="'/storage/' + material.file_path" type="audio/mpeg">
                    Browser Anda tidak mendukung pemutaran audio.
                  </audio>
                </div>
              </div>
  
              <!-- Action Buttons -->
              <div class="mt-8 pt-6 border-t flex justify-end space-x-3">
                <Link v-if="canEdit" 
                      :href="route('materials.edit', material.id)" 
                      class="btn-secondary">
                  Edit
                </Link>
                
                <button v-if="canReview && material.status === 'pending'"
                        @click="approveMaterial(material.id)"
                        class="btn-success">
                  Setujui
                </button>
                
                <button v-if="canReview && material.status === 'pending'"
                        @click="showRejectModal = true"
                        class="btn-danger">
                  Tolak
                </button>
                
                <button v-if="canDelete"
                        @click="confirmDelete(material)"
                        class="btn-danger">
                  Hapus
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Reject Modal -->
      <Modal :show="showRejectModal" @close="showRejectModal = false">
        <div class="p-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">Tolak Materi</h3>
            <button @click="showRejectModal = false" class="text-gray-400 hover:text-gray-500">
              &times;
            </button>
          </div>
          <textarea v-model="rejectionReason"
                    class="w-full border rounded p-2"
                    placeholder="Masukkan alasan penolakan..."
                    rows="4"></textarea>
          <div class="mt-4 flex justify-end space-x-2">
            <button @click="showRejectModal = false" class="btn-secondary">
              Batal
            </button>
            <button @click="rejectMaterial" class="btn-danger" :disabled="!rejectionReason">
              Konfirmasi Penolakan
            </button>
          </div>
        </div>
      </Modal>
  
      <!-- Delete Confirmation Modal -->
      <Modal :show="showDeleteModal" @close="showDeleteModal = false">
        <div class="p-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">Konfirmasi Hapus</h3>
            <button @click="showDeleteModal = false" class="text-gray-400 hover:text-gray-500">
              &times;
            </button>
          </div>
          <p>Apakah Anda yakin ingin menghapus materi "{{ materialToDelete?.title }}"?</p>
          <div class="mt-4 flex justify-end space-x-2">
            <button @click="showDeleteModal = false" class="btn-secondary">
              Batal
            </button>
            <button @click="deleteMaterial" class="btn-danger">
              Ya, Hapus
            </button>
          </div>
        </div>
      </Modal>
    </AuthenticatedLayout>
    <CommentSection 
    :material-id="material.id" 
    :initial-comments="material.comments" 
    />
  </template>
  
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Link, router } from '@inertiajs/vue3';
  import { ref } from 'vue';
  import Modal from '@/Components/Modal.vue';
  import CommentSection from '@/Components/Comments/CommentSection.vue';
  
  const props = defineProps({
    material: Object,
    canEdit: Boolean,
    canReview: Boolean,
    canDelete: Boolean
  });
  
  const showRejectModal = ref(false);
  const showDeleteModal = ref(false);
  const rejectionReason = ref('');
  const materialToDelete = ref(null);
  
  const statusColor = (status) => {
    return {
      'pending': 'bg-yellow-100 text-yellow-800',
      'approved': 'bg-green-100 text-green-800',
      'rejected': 'bg-red-100 text-red-800'
    }[status];
  };
  
  const materialStatus = (status) => {
    return {
      'pending': 'Menunggu Review',
      'approved': 'Disetujui',
      'rejected': 'Ditolak'
    }[status];
  };
  
  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };
  
  const approveMaterial = (id) => {
    if (confirm('Setujui materi ini?')) {
      router.post(route('materials.approve', id), {
        preserveScroll: true
      });
    }
  };
  
  const rejectMaterial = () => {
    router.post(route('materials.reject', props.material.id), {
      reason: rejectionReason.value,
      preserveScroll: true,
      onSuccess: () => {
        showRejectModal.value = false;
        rejectionReason.value = '';
      }
    });
  };
  
  const confirmDelete = (material) => {
    materialToDelete.value = material;
    showDeleteModal.value = true;
  };
  
  const deleteMaterial = () => {
    router.delete(route('materials.destroy', materialToDelete.value.id), {
      preserveScroll: true,
      onSuccess: () => {
        showDeleteModal.value = false;
        router.visit(route('materials.index'));
      }
    });
  };
  </script>
  
  <style scoped>
  .btn-secondary {
    @apply inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
  }
  
  .btn-success {
    @apply inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500;
  }
  
  .btn-danger {
    @apply inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500;
  }
  
  .prose :deep(img) {
    @apply rounded-lg shadow-md;
  }
  
  .prose :deep(a) {
    @apply text-blue-600 hover:text-blue-800;
  }
  </style>
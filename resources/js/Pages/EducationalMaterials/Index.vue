<template>
    <AuthenticatedLayout>
      <template #header>
        <div class="flex items-center justify-between">
          <h2 class="text-2xl font-bold text-gray-800">
            Materi Edukasi
          </h2>
          <Link 
            :href="route('materials.create')" 
            class="btn-primary flex items-center gap-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Unggah Materi Baru
          </Link>
        </div>
      </template>
  
      <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
              <h3 class="text-lg font-medium text-gray-900">Daftar Materi</h3>
              <div class="relative">
                <input 
                  type="text" 
                  placeholder="Cari materi..." 
                  class="pl-10 pr-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
            </div>
  
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="materials.data.length === 0">
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                      Belum ada materi yang tersedia
                    </td>
                  </tr>
                  <tr v-for="material in materials.data" v-else :key="material.id" class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                          <MaterialIcon :type="material.type" class="h-6 w-6 text-blue-600" />
                        </div>
                        <div>
                          <div class="font-medium text-gray-900">{{ material.title }}</div>
                          <div class="text-sm text-gray-500">{{ formatDate(material.created_at) }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                        {{ getTypeName(material.type) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="statusClasses(material.status)" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ getStatusName(material.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <div class="flex items-center space-x-3">
                        <Link 
                          :href="route('materials.show', material.id)" 
                          class="text-blue-600 hover:text-blue-900 flex items-center"
                          title="Lihat"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                          </svg>
                        </Link>
                        <template v-if="canReview && material.status === 'pending'">
                          <button 
                            @click="approve(material.id)" 
                            class="text-green-600 hover:text-green-900 flex items-center"
                            title="Setujui"
                          >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                          </button>
                          <button 
                            @click="reject(material)" 
                            class="text-red-600 hover:text-red-900 flex items-center"
                            title="Tolak"
                          >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                          </button>
                        </template>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            <Pagination :links="materials.links" class="px-6 py-4 border-t border-gray-100" />
          </div>
        </div>
      </div>
  
      <!-- Modal Penolakan -->
      <Modal :show="showRejectionModal" @close="showRejectionModal = false">
        <div class="p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Tolak Materi</h2>
            <button @click="showRejectionModal = false" class="text-gray-400 hover:text-gray-500">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <div class="space-y-4">
            <p class="text-sm text-gray-600">Silakan berikan alasan penolakan materi ini:</p>
            <textarea 
              v-model="rejectionReason" 
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
              rows="4" 
              placeholder="Masukkan alasan penolakan..."
            ></textarea>
            
            <div class="flex justify-end space-x-3 pt-2">
              <button 
                @click="showRejectionModal = false" 
                class="btn-secondary px-4 py-2 text-sm font-medium"
              >
                Batal
              </button>
              <button 
                @click="confirmReject" 
                class="btn-danger px-4 py-2 text-sm font-medium flex items-center gap-2"
                :disabled="!rejectionReason"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                Konfirmasi Penolakan
              </button>
            </div>
          </div>
        </div>
      </Modal>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Link, router } from '@inertiajs/vue3';
  import { ref } from 'vue';
  import Modal from '@/Components/Modal.vue';
  import Pagination from '@/Components/Pagination.vue';
  import MaterialIcon from '@/Components/MaterialIcon.vue';
  
  const props = defineProps({
    materials: Object,
    canReview: Boolean
  });
  
  const showRejectionModal = ref(false);
  const rejectionReason = ref('');
  const currentMaterialId = ref(null);
  
  const statusClasses = (status) => {
    return {
      'bg-yellow-100 text-yellow-800': status === 'pending',
      'bg-green-100 text-green-800': status === 'approved',
      'bg-red-100 text-red-800': status === 'rejected',
    };
  };
  
  const getStatusName = (status) => {
    const statuses = {
      'pending': 'Menunggu Review',
      'approved': 'Disetujui',
      'rejected': 'Ditolak'
    };
    return statuses[status] || status;
  };
  
  const getTypeName = (type) => {
    const types = {
      'article': 'Artikel',
      'image': 'Gambar',
      'pdf': 'PDF',
      'audio': 'Audio',
      'video': 'Video'
    };
    return types[type] || type;
  };
  
  const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
  };
  
  const approve = (id) => {
    router.post(route('materials.approve', id), {
      preserveScroll: true,
      onSuccess: () => {
        // Notifikasi sukses bisa ditambahkan di sini
      }
    });
  };
  
  const reject = (material) => {
    currentMaterialId.value = material.id;
    showRejectionModal.value = true;
  };
  
  const confirmReject = () => {
    router.post(route('materials.reject', currentMaterialId.value), {
      reason: rejectionReason.value,
      preserveScroll: true,
      onSuccess: () => {
        showRejectionModal.value = false;
        rejectionReason.value = '';
      }
    });
  };
  </script>
  
  <style scoped>
  /* Gaya tetap sama, hanya ubah nama kelas jika perlu */
  .btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring-2 focus:ring-blue-200 transition ease-in-out duration-150;
  }
  
  .btn-secondary {
    @apply inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:border-blue-300 focus:ring-2 focus:ring-blue-200 transition ease-in-out duration-150;
  }
  
  .btn-danger {
    @apply inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring-2 focus:ring-red-200 transition ease-in-out duration-150;
  }
  </style>
<!-- Resources/js/Pages/EducationalMaterials/Index.vue -->
<template>
    <AuthenticatedLayout>
      <template #header>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          Educational Materials
        </h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="flex justify-end mb-4">
            <Link :href="route('materials.create')" class="btn-primary">
              Upload New Material
            </Link>
          </div>
  
          <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <!-- Table headers -->
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <!-- Table body -->
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="material in materials.data" :key="material.id">
                  <td class="px-6 py-4 whitespace-nowrap">{{ material.title }}</td>
                  <td class="px-6 py-4 whitespace-nowrap capitalize">{{ material.type }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="statusClasses(material.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ material.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <Link :href="route('materials.show', material.id)" class="text-blue-600 hover:text-blue-900 mr-3">
                      View
                    </Link>
                    <template v-if="canReview && material.status === 'pending'">
                      <button @click="approve(material.id)" class="text-green-600 hover:text-green-900 mr-3">
                        Approve
                      </button>
                      <button @click="reject(material)" class="text-red-600 hover:text-red-900">
                        Reject
                      </button>
                    </template>
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- Pagination -->
            <Pagination :links="materials.links" class="p-4" />
          </div>
        </div>
      </div>
  
      <!-- Rejection Modal -->
      <Modal :show="showRejectionModal" @close="showRejectionModal = false">
        <div class="p-6">
          <h2 class="text-lg font-medium text-gray-900 mb-4">Reject Material</h2>
          <textarea v-model="rejectionReason" class="w-full border-gray-300 rounded-md" rows="4" placeholder="Enter rejection reason..."></textarea>
          <div class="mt-4 flex justify-end">
            <button @click="showRejectionModal = false" class="btn-secondary mr-2">
              Cancel
            </button>
            <button @click="confirmReject" class="btn-danger">
              Confirm Reject
            </button>
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
  
  const approve = (id) => {
    router.post(route('materials.approve', id), {
      preserveScroll: true
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
<template>
    <AuthenticatedLayout>
      <template #header>
        <div class="flex justify-between items-center">
          <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Manajemen Pengguna
          </h2>
        </div>
      </template>
  
      <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <!-- Export Options Dropdown -->
                <div v-if="showExportOptions" class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-10">
                    <div class="p-2">
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                        <select v-model="exportFormat" class="w-full border rounded p-2">
                        <option value="xlsx">Excel (.xlsx)</option>
                        <option value="csv">CSV (.csv)</option>
                        <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kolom</label>
                        <div class="space-y-1">
                        <label v-for="column in exportColumns" :key="column.value" class="flex items-center">
                            <input 
                            type="checkbox" 
                            v-model="selectedExportColumns" 
                            :value="column.value" 
                            class="h-4 w-4 text-blue-600 rounded"
                            >
                            <span class="ml-2 text-sm text-gray-700">{{ column.label }}</span>
                        </label>
                        </div>
                    </div>
                    <button 
                        @click="exportData"
                        class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                    >
                        Export
                    </button>
                    </div>
                </div>
            <div class="p-6">
              <!-- Search and Filter -->
              <div class="mb-6 flex justify-between items-center">
                <div class="w-1/3">
                  <input
                    v-model="search"
                    type="text"
                    placeholder="Cari pengguna..."
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    @input="performSearch"
                  >
                </div>
                <button @click="showAddModal = true" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Tambah Pengguna</button>
              </div>
              <div class="mb-4 flex items-center space-x-4">
                <select 
                    v-model="batchAction" 
                    class="border rounded px-3 py-2"
                    @change="applyBatchAction"
                >
                    <option value="">Aksi Massal</option>
                    <option 
                    v-for="role in roles" 
                    :key="'batch-'+role.id" 
                    :value="'role:'+role.id"
                    >
                    Ubah role ke {{ role.name }}
                    </option>
                    <option value="delete">Hapus yang dipilih</option>
                </select>
                
                <button 
                    @click="selectAll = !selectAll"
                    class="px-3 py-2 bg-gray-200 rounded"
                >
                    {{ selectAll ? 'Batal Pilih Semua' : 'Pilih Semua' }}
                </button>
                </div>
              
              <!-- Users Table -->
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pilih</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="user in users.data" :key="user.id">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input 
                            type="checkbox" 
                            v-model="selectedUsers"
                            :value="user.id"
                            class="h-4 w-4 text-blue-600 rounded"
                            >
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-600">{{ user.name.charAt(0) }}</span>
                          </div>
                          <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                            <div class="text-sm text-gray-500">Bergabung: {{ formatDate(user.created_at) }}</div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ user.email }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <select
                            v-model="user.role_id"
                            @change="updateRole(user)"
                            :class="{
                            'border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50': true,
                            'cursor-not-allowed opacity-50': user.id === $page.props.auth.user.id
                            }"
                            :disabled="user.id === $page.props.auth.user.id"
                        >
                            <option v-for="role in roles" :key="role.id" :value="role.id">
                            {{ role.name }}
                            </option>
                        </select>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button
                            @click="confirmDelete(user)"
                            :class="{
                            'text-red-600 hover:text-red-900': true,
                            'cursor-not-allowed opacity-50': user.id === $page.props.auth.user.id
                            }"
                            :disabled="user.id === $page.props.auth.user.id"
                        >
                            Hapus
                        </button>
                        <span 
                            v-if="user.id === $page.props.auth.user.id" 
                            class="text-xs text-gray-500 ml-2"
                        >
                            (Tidak tersedia)
                        </span>&nbsp;
                        <button
                        @click="confirmResetPassword(user)"
                        class="text-blue-600 hover:text-blue-900 mr-3"
                        >
                        Reset Password
                        </button>
                        </td>
                    </tr>
                  </tbody>
                </table>
              </div>
  
              <!-- Pagination -->
              <Pagination class="mt-6" :links="users.links" />
            </div>
          </div>
        </div>
      </div>
  
      <!-- Delete Confirmation Modal -->
      <ConfirmationModal :show="showDeleteModal" @close="showDeleteModal = false">
        <template #title>
          Konfirmasi Penghapusan
        </template>
        <template #content>
          Apakah Anda yakin ingin menghapus pengguna {{ userToDelete?.name }}?
        </template>
        <template #footer>
          <button @click="showDeleteModal = false" class="mr-4 px-4 py-2 bg-gray-200 rounded-md">
            Batal
          </button>
          <button @click="deleteUser" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
            Hapus
          </button>
        </template>
      </ConfirmationModal>

      <!-- Add User Modal -->
      <ConfirmationModal :show="showAddModal" @close="showAddModal = false">
        <template #title>
          Tambah Pengguna
        </template>
        <template #content>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Nama</label>
              <input v-model="newUser.name" type="text" class="w-full px-3 py-2 border rounded" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input v-model="newUser.email" type="email" class="w-full px-3 py-2 border rounded" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input 
                v-model="newUser.password" 
                type="password" 
                class="w-full px-3 py-2 border rounded"
                :class="{ 'border-red-500': errors.password }"
                >
                <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input 
                v-model="newUser.password_confirmation" 
                type="password" 
                class="w-full px-3 py-2 border rounded"
                :class="{ 'border-red-500': errors.password }"
                >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Role</label>
              <select v-model="newUser.role_id" class="w-full px-3 py-2 border rounded">
                <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
              </select>
            </div>
            <div v-if="addUserError" class="text-red-600 text-sm">{{ addUserError }}</div>
          </div>
        </template>
        <template #footer>
          <button @click="showAddModal = false" class="mr-4 px-4 py-2 bg-gray-200 rounded-md">Batal</button>
          <button @click="addUser" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Tambah</button>
        </template>
      </ConfirmationModal>
      <ConfirmationModal :show="showResetPasswordModal" @close="showResetPasswordModal = false">
        <template #title>
          Reset Password
        </template>
        <template #content>
          Apakah Anda yakin ingin mereset password pengguna {{ userToReset?.name }}?
        </template>
        <template #footer>
          <button @click="showResetPasswordModal = false" class="mr-4 px-4 py-2 bg-gray-200 rounded-md">Batal</button>
          <button @click="resetPassword" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Reset</button>
        </template>
      </ConfirmationModal>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { ref, watch } from 'vue';
  import { Link, router } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import Pagination from '@/Components/Pagination.vue';
  import ConfirmationModal from '@/Components/ConfirmationModal.vue';
  import _ from 'lodash';
  
  const props = defineProps({
    users: Object,
    roles: Array,
    filters: { // Tambahkan default value untuk filters
        type: Object,
        default: () => ({})
    },
  });
  
  const search = ref(props.filters?.search || '');
  const showDeleteModal = ref(false);
  const userToDelete = ref(null);
  const showAddModal = ref(false);
  const addUserError = ref('');
  const newUser = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role_id: props.roles?.[0]?.id || '',
  });
  const errors = ref({});
  
  const performSearch = _.debounce(() => {
    router.get(route('admin.users'), { search: search.value }, {
      preserveState: true,
      replace: true,
    });
  }, 300);
  
  const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
  };
  
  const updateRole = (user) => {
  // Tambahkan konfirmasi sebelum mengubah role
  if (confirm(`Yakin ingin mengubah role ${user.name}?`)) {
    router.put(route('admin.users.update-role', user.id), {
      role_id: user.role_id
    }, {
      preserveScroll: true,
      onSuccess: () => {
        // Gunakan notifikasi yang lebih baik
        toast.success('Role pengguna berhasil diperbarui');
      },
      onError: () => {
        toast.error('Gagal memperbarui role pengguna');
      }
    });
  }
};

const confirmDelete = (user) => {
  // Tambahkan pengecekan khusus untuk admin
  if (user.role?.name === 'admin' && 
      props.users.data.filter(u => u.role?.name === 'admin').length <= 1) {
    toast.error('Tidak dapat menghapus admin terakhir');
    return;
  }
  
  // Gunakan modal konfirmasi yang lebih informatif
  userToDelete.value = user;
  showDeleteModal.value = true;
};
  
  const deleteUser = () => {
    if (!userToDelete.value) return;
    router.delete(route('admin.users.destroy', userToDelete.value.id), {
      preserveScroll: true,
      onSuccess: () => {
        showDeleteModal.value = false;
        alert('Pengguna berhasil dihapus!');
      },
      onError: () => {
        alert('Gagal menghapus pengguna!');
      }
    });
  };
  
  const addUser = () => {
  errors.value = {};
  
  // Validasi client-side
  if (!newUser.value.name) {
    errors.value.name = 'Nama wajib diisi';
    return;
  }
  
  if (!newUser.value.email) {
    errors.value.email = 'Email wajib diisi';
    return;
  } else if (!/^\S+@\S+\.\S+$/.test(newUser.value.email)) {
    errors.value.email = 'Format email tidak valid';
    return;
  }
  
  if (!newUser.value.password) {
    errors.value.password = 'Password wajib diisi';
    return;
  } else if (newUser.value.password.length < 8) {
    errors.value.password = 'Password minimal 8 karakter';
    return;
  } else if (newUser.value.password !== newUser.value.password_confirmation) {
    errors.value.password = 'Password dan konfirmasi password tidak sama';
    return;
  }
  
  if (!newUser.value.role_id) {
    errors.value.role_id = 'Role wajib dipilih';
    return;
  }

  router.post(route('admin.users.store'), newUser.value, {
    preserveScroll: true,
    onSuccess: () => {
      showAddModal.value = false;
      newUser.value = { 
        name: '', 
        email: '', 
        password: '', 
        password_confirmation: '', 
        role_id: props.roles?.[0]?.id || '' 
      };
      toast.success('Pengguna berhasil ditambahkan');
    },
    onError: (err) => {
      if (err.response?.data?.errors) {
        errors.value = err.response.data.errors;
      } else {
        toast.error('Gagal menambahkan pengguna');
      }
    }
  });
};

  const batchAction = ref('');
const selectedUsers = ref([]);
const selectAll = ref(false);

watch(selectAll, (val) => {
    selectedUsers.value = val ? props.users.data.map(u => u.id) : [];
});

const applyBatchAction = () => {
    if (!batchAction.value || selectedUsers.value.length === 0) return;

    if (batchAction.value.startsWith('role:')) {
        const roleId = batchAction.value.split(':')[1];
        if (confirm(`Yakin ingin mengubah role ${selectedUsers.value.length} pengguna?`)) {
        router.put(route('admin.users.batch-update'), {
            user_ids: selectedUsers.value,
            role_id: roleId
        }, {
            preserveScroll: true,
            onSuccess: () => {
            selectedUsers.value = [];
            selectAll.value = false;
            toast.success('Role pengguna berhasil diperbarui');
            }
        });
        }
    } else if (batchAction.value === 'delete') {
        if (confirm(`Yakin ingin menghapus ${selectedUsers.value.length} pengguna?`)) {
        router.delete(route('admin.users.batch-delete'), {
            data: { user_ids: selectedUsers.value },
            preserveScroll: true,
            onSuccess: () => {
            selectedUsers.value = [];
            selectAll.value = false;
            toast.success('Pengguna berhasil dihapus');
            }
        });
        }
    }

    batchAction.value = '';
};

const showExportOptions = ref(false);
const exportFormat = ref('xlsx');
const exportColumns = ref([
  { value: 'name', label: 'Nama' },
  { value: 'email', label: 'Email' },
  { value: 'role', label: 'Role' },
  { value: 'created_at', label: 'Tanggal Bergabung' }
]);
const selectedExportColumns = ref(['name', 'email', 'role', 'created_at']);

const exportData = () => {
  router.visit(route('admin.users.export'), {
    method: 'get',
    data: {
      format: exportFormat.value,
      columns: selectedExportColumns.value
    },
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      showExportOptions.value = false;
    }
  });
};

const showResetPasswordModal = ref(false);
const userToReset = ref(null);

const confirmResetPassword = (user) => {
  userToReset.value = user;
  showResetPasswordModal.value = true;
};

const resetPassword = () => {
  router.put(route('admin.users.reset-password', userToReset.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      showResetPasswordModal.value = false;
      toast.success('Password berhasil direset');
    }
  });
};
  </script>
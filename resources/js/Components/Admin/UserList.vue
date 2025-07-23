<template>
    <div class="space-y-4">
      <div v-for="user in users" :key="user.id" class="flex items-center justify-between p-4 border rounded-lg">
        <div class="flex items-center">
          <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
            <span class="text-gray-600 text-sm">{{ user.name.charAt(0) }}</span>
          </div>
          <div class="ml-4">
            <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
            <div class="text-sm text-gray-500">{{ user.email }}</div>
          </div>
        </div>
        <div class="text-sm text-gray-500 capitalize">{{ user.role.name }}</div>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    users: Array,
  });
  </script>
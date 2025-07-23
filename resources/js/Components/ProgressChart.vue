<template>
    <div>
      <div class="flex items-center justify-between mb-2">
        <span class="text-sm font-medium text-gray-700">Completion</span>
        <span class="text-sm font-medium text-gray-700">{{ progress.percentage }}%</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-2.5">
        <div 
          class="bg-blue-600 h-2.5 rounded-full" 
          :style="`width: ${progress.percentage}%`"
        ></div>
      </div>
      <div class="grid grid-cols-3 gap-4 mt-4">
        <div v-for="item in progress.courses" :key="item.id" class="text-center">
          <p class="text-sm text-gray-500">{{ item.name }}</p>
          <p class="text-lg font-semibold">{{ item.progress }}%</p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    progress: {
      type: Object,
      required: true,
      default: () => ({
        percentage: 0,
        courses: []
      })
    }
  });
  </script>
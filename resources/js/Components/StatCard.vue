<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
      <div class="flex items-center">
        <div :class="`bg-${color}-100 p-3 rounded-full`">
          <svg :class="`h-6 w-6 text-${color}-600`" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path v-if="icon === 'academic-cap'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            <path v-if="icon === 'users'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            <path v-if="icon === 'document-text'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            <path v-if="icon === 'check-circle'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div class="ml-4">
          <p class="text-sm font-medium text-gray-500 truncate">{{ title }}</p>
          <p class="text-2xl font-semibold text-gray-900">{{ value }}</p>
          <div class="flex items-center mt-1">
            <span :class="`text-xs font-medium ${change >= 0 ? 'text-green-600' : 'text-red-600'}`">
              {{ change >= 0 ? '+' : '' }}{{ change }}%
            </span>
            <svg :class="`h-4 w-4 ml-1 ${change >= 0 ? 'text-green-600' : 'text-red-600'}`" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="change >= 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
            </svg>
            <span class="text-xs text-gray-500 ml-1">vs last month</span>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    title: String,
    value: [String, Number],
    icon: String,
    color: {
      type: String,
      default: 'blue'
    },
    change: {
      type: Number,
      default: 0
    }
  });
  </script>
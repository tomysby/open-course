<template>
    <div v-if="links.length > 3" class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
      <div class="-mt-px flex w-0 flex-1">
        <Link
          v-if="links[0].url"
          :href="links[0].url"
          class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
          preserve-scroll
        >
          &laquo; Previous
        </Link>
      </div>
      <div class="hidden md:-mt-px md:flex">
        <Link
          v-for="(link, index) in links.slice(1, -1)"
          :key="index"
          :href="link.url"
          class="inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium"
          :class="{
            'border-indigo-500 text-indigo-600': link.active,
            'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': !link.active
          }"
          preserve-scroll
        >
          {{ link.label }}
        </Link>
      </div>
      <div class="-mt-px flex w-0 flex-1 justify-end">
        <Link
          v-if="links[links.length - 1].url"
          :href="links[links.length - 1].url"
          class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
          preserve-scroll
        >
          Next &raquo;
        </Link>
      </div>
    </div>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3';
  
  defineProps({
    links: {
      type: Array,
      required: true,
      default: () => [],
    },
  });
  </script>
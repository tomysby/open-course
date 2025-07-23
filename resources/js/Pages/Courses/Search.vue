<template>
    <div>
      <pre>{{ courses }}</pre>
    </div>
  </template>
  
  <script setup>
  defineProps({
    courses: Array,
  });
  </script>
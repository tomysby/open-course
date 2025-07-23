<template>
    <Modal :show="show" @close="close">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">
          <slot name="title"></slot>
        </h2>
        <div class="mb-6">
          <slot name="content"></slot>
        </div>
        <div class="flex justify-end">
          <slot name="footer"></slot>
        </div>
      </div>
    </Modal>
  </template>
  
  <script setup>
  import Modal from '@/Components/Modal.vue';
  
  defineProps({
    show: Boolean,
  });
  
  const emit = defineEmits(['close']);
  
  const close = () => {
    emit('close');
  };
  </script>
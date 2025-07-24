<template>
    <div class="comment" :class="{ 'ml-8 pl-4 border-l-2 border-gray-200': isReply }">
      <div class="flex items-start space-x-3">
        <img 
          :src="comment.user.profile_photo_url || '/images/default-avatar.png'" 
          :alt="comment.user.name"
          class="w-10 h-10 rounded-full object-cover"
        >
        <div class="flex-1">
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="flex justify-between items-start">
              <div>
                <span class="font-semibold text-gray-800">{{ comment.user.name }}</span>
                <span class="text-xs text-gray-500 ml-2">{{ comment.time_ago }}</span>
              </div>
              <div v-if="authUser" class="flex space-x-2">
                <button 
                  @click="$emit('reply', comment)"
                  class="text-xs text-blue-600 hover:text-blue-800"
                >
                  Balas
                </button>
                <button 
                  v-if="authUser.id === comment.user_id"
                  @click="$emit('delete', comment.id)"
                  class="text-xs text-red-600 hover:text-red-800"
                >
                  Hapus
                </button>
              </div>
            </div>
            <p class="mt-1 text-gray-700">{{ comment.content }}</p>
          </div>
  
          <!-- Form Reply (inline) -->
          <div v-if="showReplyForm" class="mt-3 ml-4">
            <textarea
              v-model="replyContent"
              placeholder="Tulis balasan Anda..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
              rows="2"
            ></textarea>
            <div class="flex justify-end space-x-2 mt-2">
              <button
                @click="showReplyForm = false"
                class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800"
              >
                Batal
              </button>
              <button
                @click="submitReply"
                :disabled="!replyContent.trim()"
                class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:bg-blue-300"
              >
                Kirim Balasan
              </button>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Replies -->
      <div v-if="comment.replies && comment.replies.length > 0" class="mt-4 space-y-4">
        <CommentItem
          v-for="reply in comment.replies"
          :key="reply.id"
          :comment="reply"
          :auth-user="authUser"
          is-reply
          @reply="$emit('reply', $event)"
          @delete="$emit('delete', $event)"
        />
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { router } from '@inertiajs/vue3';
  
  const props = defineProps({
    comment: {
      type: Object,
      required: true
    },
    authUser: {
      type: Object,
      default: null
    },
    isReply: {
      type: Boolean,
      default: false
    }
  });
  
  const emit = defineEmits(['reply', 'delete']);
  
  const showReplyForm = ref(false);
  const replyContent = ref('');
  
  const submitReply = async () => {
    try {
      const response = await axios.post(route('comments.store'), {
        content: replyContent.value,
        educational_material_id: props.comment.educational_material_id,
        parent_id: props.comment.id
      });
  
      // Tambahkan reply ke daftar replies
      if (!props.comment.replies) {
        props.comment.replies = [];
      }
      props.comment.replies.push(response.data);
      
      // Reset form
      replyContent.value = '';
      showReplyForm.value = false;
    } catch (error) {
      console.error('Error submitting reply:', error);
    }
  };
  
  // Tampilkan form reply ketika ada event reply
  defineExpose({
    showReply() {
      showReplyForm.value = true;
    }
  });
  </script>
  
  <style scoped>
  .comment {
    transition: all 0.2s ease;
  }
  </style>
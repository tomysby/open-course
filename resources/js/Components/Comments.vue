<!-- Resources/js/Components/Comments.vue -->
<template>
    <div class="mt-8">
      <h3 class="text-lg font-semibold mb-4">Discussion ({{ comments.length }})</h3>
      
      <!-- Comment Form -->
      <form @submit.prevent="submit" class="mb-6">
        <textarea
          v-model="form.content"
          rows="3"
          class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
          placeholder="Add your comment..."
        ></textarea>
        <button
          type="submit"
          class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
          :disabled="form.processing"
        >
          Post Comment
        </button>
      </form>
  
      <!-- Comments List -->
      <div class="space-y-4">
        <div v-for="comment in comments" :key="comment.id" class="border-b border-gray-200 pb-4">
          <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
              <img :src="comment.user.profile_photo_url" :alt="comment.user.name" class="h-10 w-10 rounded-full">
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-900">
                  {{ comment.user.name }}
                </p>
                <span class="text-xs text-gray-500">
                  {{ formatDate(comment.created_at) }}
                </span>
              </div>
              <p class="text-sm text-gray-700 mt-1">
                {{ comment.content }}
              </p>
              
              <!-- Reply Button -->
              <button 
                @click="toggleReply(comment.id)"
                class="text-xs text-blue-600 hover:text-blue-800 mt-2"
              >
                Reply
              </button>
  
              <!-- Reply Form -->
              <form 
                v-if="activeReply === comment.id"
                @submit.prevent="submitReply(comment.id)"
                class="mt-3"
              >
                <textarea
                  v-model="replyForm.content"
                  rows="2"
                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                  placeholder="Write your reply..."
                ></textarea>
                <div class="flex space-x-2 mt-2">
                  <button
                    type="submit"
                    class="px-3 py-1 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700"
                    :disabled="replyForm.processing"
                  >
                    Post Reply
                  </button>
                  <button
                    type="button"
                    @click="activeReply = null"
                    class="px-3 py-1 bg-gray-200 text-gray-700 text-xs rounded-md hover:bg-gray-300"
                  >
                    Cancel
                  </button>
                </div>
              </form>
  
              <!-- Replies -->
              <div v-if="comment.replies.length" class="mt-4 pl-4 border-l-2 border-gray-200 space-y-3">
                <div v-for="reply in comment.replies" :key="reply.id" class="pt-3">
                  <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                      <img :src="reply.user.profile_photo_url" :alt="reply.user.name" class="h-8 w-8 rounded-full">
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between">
                        <p class="text-xs font-medium text-gray-900">
                          {{ reply.user.name }}
                        </p>
                        <span class="text-xs text-gray-500">
                          {{ formatDate(reply.created_at) }}
                        </span>
                      </div>
                      <p class="text-xs text-gray-700 mt-1">
                        {{ reply.content }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  
  const props = defineProps({
    material: Object,
    comments: {
      type: Array,
      default: () => []
    }
  });
  
  const activeReply = ref(null);
  
  const form = useForm({
    content: '',
    parent_id: null
  });
  
  const replyForm = useForm({
    content: '',
    parent_id: null
  });
  
  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  };
  
  const submit = () => {
    form.post(route('comments.store', props.material.id), {
      preserveScroll: true,
      onSuccess: () => form.reset()
    };
  };
  
  const toggleReply = (commentId) => {
    activeReply.value = activeReply.value === commentId ? null : commentId;
  };
  
  const submitReply = (parentId) => {
    replyForm.parent_id = parentId;
    replyForm.post(route('comments.store', props.material.id), {
      preserveScroll: true,
      onSuccess: () => {
        replyForm.reset();
        activeReply.value = null;
      }
    });
  };
  </script>
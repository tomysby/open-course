<template>
    <div class="comment-section bg-white rounded-lg shadow-sm p-6">
      <h3 class="text-xl font-semibold mb-6">Diskusi</h3>
      
      <!-- Form Komentar Utama -->
      <div v-if="$page.props.auth.user" class="mb-8">
        <textarea
          v-model="newComment"
          placeholder="Tulis komentar Anda..."
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          rows="3"
        ></textarea>
        <div class="flex justify-end mt-2">
          <button
            @click="submitComment"
            :disabled="!newComment.trim()"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-blue-300"
          >
            Kirim Komentar
          </button>
        </div>
      </div>
      <div v-else class="mb-6 p-4 bg-blue-50 text-blue-800 rounded-lg">
        <Link :href="route('login')" class="text-blue-600 font-medium">Login</Link> untuk berpartisipasi dalam diskusi.
      </div>
  
      <!-- Daftar Komentar -->
      <div v-if="comments.length > 0" class="space-y-6">
        <CommentItem
          v-for="comment in comments"
          :key="comment.id"
          :comment="comment"
          :auth-user="$page.props.auth.user"
          @reply="handleReply"
          @delete="handleDelete"
        />
      </div>
      <div v-else class="text-gray-500 text-center py-8">
        Belum ada komentar. Jadilah yang pertama berkomentar!
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { Link, usePage } from '@inertiajs/vue3';
  import CommentItem from './CommentItem.vue';
  
  const props = defineProps({
    materialId: {
      type: Number,
      required: true
    },
    initialComments: {
      type: Array,
      default: () => []
    }
  });
  
  const comments = ref(props.initialComments);
  const newComment = ref('');
  const replyingTo = ref(null);
  
  const submitComment = async () => {
    try {
      const response = await axios.post(route('comments.store'), {
        content: newComment.value,
        educational_material_id: props.materialId,
        parent_id: replyingTo.value?.id || null
      });
  
      if (replyingTo.value) {
        // Tambahkan reply ke komentar parent
        const parent = findComment(comments.value, replyingTo.value.id);
        if (parent) {
          parent.replies = parent.replies || [];
          parent.replies.push(response.data);
        }
        replyingTo.value = null;
      } else {
        // Tambahkan komentar baru
        comments.value.unshift(response.data);
      }
  
      newComment.value = '';
    } catch (error) {
      console.error('Error submitting comment:', error);
    }
  };
  
  const handleReply = (comment) => {
    replyingTo.value = comment;
    window.scrollTo({
      top: document.querySelector('.comment-section').offsetTop,
      behavior: 'smooth'
    });
  };
  
  const handleDelete = (commentId) => {
    if (confirm('Apakah Anda yakin ingin menghapus komentar ini?')) {
      axios.delete(route('comments.destroy', commentId))
        .then(() => {
          removeComment(comments.value, commentId);
        })
        .catch(error => {
          console.error('Error deleting comment:', error);
        });
    }
  };
  
  // Helper functions
  function findComment(commentList, id) {
    for (const comment of commentList) {
      if (comment.id === id) return comment;
      if (comment.replies) {
        const found = findComment(comment.replies, id);
        if (found) return found;
      }
    }
    return null;
  }
  
  function removeComment(commentList, id) {
    for (let i = 0; i < commentList.length; i++) {
      if (commentList[i].id === id) {
        commentList.splice(i, 1);
        return true;
      }
      if (commentList[i].replies) {
        if (removeComment(commentList[i].replies, id)) {
          return true;
        }
      }
    }
    return false;
  }
  
  onMounted(() => {
    // Set up real-time updates jika diperlukan
    // Echo.channel(`material.${props.materialId}.comments`)
    //   .listen('NewComment', (data) => {
    //     // Handle new comment from websocket
    //   });
  });
  </script>
  
  <style scoped>
  .comment-section {
    max-width: 800px;
    margin: 0 auto;
  }
  </style>
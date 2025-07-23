<!-- Resources/js/Pages/Admin/Courses/Create.vue -->
<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create New Course
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <input v-model="form.title" type="text" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                <p v-if="form.errors.title" class="mt-2 text-sm text-red-600">{{ form.errors.title }}</p>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea v-model="form.description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                                <p v-if="form.errors.description" class="mt-2 text-sm text-red-600">{{ form.errors.description }}</p>
                            </div>

                            <div class="mb-4">
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                                <select v-model="form.category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">Select a category</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.category_id" class="mt-2 text-sm text-red-600">{{ form.errors.category_id }}</p>
                            </div>

                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select v-model="form.status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="published">Published</option>
                                    <option value="pending">Pending</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                                <p v-if="form.errors.status" class="mt-2 text-sm text-red-600">{{ form.errors.status }}</p>
                            </div>

                            <div class="flex items-center justify-end">
                                <Link :href="route('admin.courses')" class="mr-4 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    Cancel
                                </Link>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-200 disabled:opacity-25 transition" :disabled="form.processing">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    categories: Array
});

const form = useForm({
    title: '',
    description: '',
    category_id: '',
    status: 'published',
});

const submit = () => {
    form.post(route('admin.courses.store'));
};
</script>
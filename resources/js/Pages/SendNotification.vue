<template>
  <AppLayout title="Send Notification">
    <div class="container mx-auto p-4">
      <h1 class="text-3xl font-bold text-gray-900">Send a Notification</h1>
      <form @submit.prevent="createNotification" class="mt-4">
        <div class="mb-4">
          <label for="user" class="block text-sm font-medium text-gray-700"
            >User</label
          >
          <select
            id="user"
            v-model="form.user_id"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
          >
            <option disabled value="">Please select a user</option>
            <option v-for="user in users" :key="user.id" :value="user.id">
              {{ user.name }}
            </option>
          </select>
        </div>
        <div class="mb-4">
          <label for="title" class="block text-sm font-medium text-gray-700"
            >Title</label
          >
          <input
            type="text"
            id="title"
            v-model="form.title"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
            placeholder="Notification title"
          />
        </div>
        <div class="mb-6">
          <label for="body" class="block text-sm font-medium text-gray-700"
            >Body</label
          >
          <textarea
            id="body"
            v-model="form.body"
            rows="3"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
            placeholder="Notification message"
          ></textarea>
        </div>
        <button
          type="submit"
          class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md"
        >
          Send Notification
        </button>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps(["users"]);

const form = useForm({
  user_id: "",
  title: "",
  body: "",
});

const createNotification = () =>
  form.post(route("send_notifications"), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  });
</script>

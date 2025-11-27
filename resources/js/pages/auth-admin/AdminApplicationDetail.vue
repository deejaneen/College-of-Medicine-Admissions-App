<template>
  <AdminIndex>
    <h1>Application Details</h1>

    <div class="p-4 border rounded">
      <p><strong>Name:</strong> {{ application.lastName }}, {{ application.firstName }} {{ application.middleName }}</p>
      <p><strong>Email:</strong> {{ application.emailAddress }}</p>
      <p><strong>Program:</strong> {{ application.program }}</p>
      <p><strong>Status:</strong> {{ application.applicationStatus }}</p>
      <!-- Add other fields as needed -->
    </div>
  </AdminIndex>
</template>

<script lang="ts" setup>
import AdminIndex from '../AdminIndex.vue';

const { application } = defineProps<{ application: any }>();
</script>

<template>
    <div class="content-container">
      <aside class="sidebar">
        <AdminNavLayout />
      </aside>

      <main class="main-container">
        <slot />
      </main>
    </div>
</template>

<script lang="ts" setup>
import AdminNavLayout from '@/layouts/AdminNavLayout.vue'
</script>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'AdminIndex',
  components: {
    AdminNavLayout,
  },
})
</script>

<style scoped>
.content-container {
  display: flex;
  height: 100vh; 
}

.sidebar {
  flex-shrink: 0;
  width: 300px;
  overflow-y: auto;
  padding: 1rem;
  background: var(--color-primary-light);
}

.main-container {
  flex: 1 1 auto;
  display: flex;
  flex-direction: column;
  padding: 1rem;
  overflow: auto; 
}
</style>

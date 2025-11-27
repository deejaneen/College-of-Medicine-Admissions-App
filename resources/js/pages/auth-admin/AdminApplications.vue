<template>
  <AdminIndex>
    <div class="applications-container">
        <div class="page-title">Applications</div>

            <!-- Search -->
        <input
        type="text"
        v-model="search"
        placeholder="Search name, email, program, or status"
        class="search-input"
        />

            <!-- Table -->
        <table class="table-elevated-minimal">
            <thead>
                <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr
                v-for="app in filteredApplications"
                :key="app.id"
                @click="goToApplication(app.id)"
                >
                <td>{{ app.lastName }}, {{ app.firstName }} {{ app.middleName }}</td>
                <td>{{ app.emailAddress }}</td>
                <td>
                    <span :class="`status status-${getStatusClass(app.applicationStatus)}`">
                    {{ app.applicationStatus }}
                    </span>
                </td>
                </tr>
            </tbody>
        </table>

            <!-- Pagination -->
        <div class="pagination">
            <button
                class="pagination-btn"
                :disabled="pagination.current_page === 1"
                @click="goToPage(pagination.current_page - 1)"
            >
                Previous
            </button>
            <span class="pagination-info">
                Page {{ pagination.current_page }} of {{ pagination.last_page }}
            </span>
            <button
                class="pagination-btn"
                :disabled="pagination.current_page === pagination.last_page"
                @click="goToPage(pagination.current_page + 1)"
            >
                Next
            </button>
        </div>
    </div>
   
  </AdminIndex>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import AdminIndex from '../AdminIndex.vue';

function goToApplication(id: number) {
    Inertia.get(`/admin/applications/${id}`);
}
// Add this method to your script section
function getStatusClass(status: string): string {
  const statusLower = status.toLowerCase();
  if (statusLower.includes('accepted')) return 'accepted';
  if (statusLower.includes('rejected')) return 'rejected';
  return 'for-review';
}

interface Application {
  id: number;
  firstName: string;
  middleName?: string;
  lastName: string;
  emailAddress: string;
  applicationStatus: string;
}

// Props from Laravel
const props = defineProps<{
  applications: Application[];
  pagination: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}>();

const search = ref('');

// Client-side filtering (optional)
const filteredApplications = computed(() => {
  if (!search.value) return props.applications;
  const term = search.value.toLowerCase();
  return props.applications.filter(
    (app) =>
      app.firstName.toLowerCase().includes(term) ||
      app.middleName?.toLowerCase().includes(term) ||
      app.lastName.toLowerCase().includes(term) ||
      app.emailAddress.toLowerCase().includes(term) ||
      app.applicationStatus.toLowerCase().includes(term)
  );
});

// Pagination
const pagination = ref(props.pagination);

// Go to a specific page
function goToPage(page: number) {
  Inertia.get('/admin/applications', { page }, { preserveState: true, replace: true });
}
</script>

<style scoped>
.applications-container{
    padding: var(--space-16);
    background: var(--color-light-bg);
}
.page-title{
    font-size: var(--font-size-h4);
    font-weight: 600;
    color: var(--color-primary);
    text-transform: uppercase;
}
.search-input {
  width: 100%;
  padding: var(--space-3) var(--space-4);
  border: 1px solid var(--color-neutral);
  border-radius: var(--radius-m);
  font-size: var(--font-size-p);
  margin-bottom: var(--space-6);
  transition: all 0.2s ease;
}

.search-input:focus {
  outline: none;
  border-color: var(--color-primary-light);
  box-shadow: 0 0 0 2px var(--color-bright-green-25);
}

/* Combined Table Style - Elevated Card with Minimal Header */
.table-elevated-minimal {
  width: 100%;
  border-collapse: collapse;
  font-family: var(--font-sans);
  font-size: var(--font-size-p);
  background: var(--color-background);
  border-radius: var(--radius-l);
  overflow: hidden;
  box-shadow: var(--elevation-3);
}

/* Minimal Header Style */
.table-elevated-minimal th {
  background-color: var(--color-light-bg);
  color: var(--color-primary);
  font-weight: 600;
  text-transform: uppercase;
  text-align: left;
  padding: var(--space-4);
  border-bottom: 2px solid var(--color-primary-light);
}

/* Elevated Card Body Style */
.table-elevated-minimal td {
  padding: var(--space-5);
  border-bottom: 1px solid var(--color-neutral);
}
.table-elevated-minimal td:last-child {
    text-transform: uppercase;
}
.table-elevated-minimal tr:last-child td {
  border-bottom: none;
}

.table-elevated-minimal tr:nth-child(even) {
  background-color: var(--color-light-bg-40);
}

.table-elevated-minimal tr:hover {
  background-color: var(--color-bright-green-25);
  cursor: pointer;
  transition: background-color 0.2s ease;
}

/* Status badges */
.status {
  display: inline-block;
  padding: var(--space-1) var(--space-3);
  border-radius: var(--radius-s);
  font-size: 1.2rem;
  font-weight: 500;

}
.status-accepted {
  background-color: var(--color-bright-green-25);
  color: var(--color-primary);
}

.status-rejected {
  background-color: rgba(220, 53, 69, 0.15);
  color: #dc3545;
}

.status-for-review {
  background-color: rgba(13, 110, 253, 0.15);
  color: #0d6efd;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: var(--space-8);
}

.pagination-btn {
  padding: var(--space-3) var(--space-5);
  border: 1px solid var(--color-neutral);
  border-radius: var(--radius-m);
  background-color: var(--color-background);
  color: var(--color-primary);
  font-size: var(--font-size-p);
  cursor: pointer;
  transition: all 0.2s ease;
}

.pagination-btn:hover:not(:disabled) {
  background-color: var(--color-light-bg);
  border-color: var(--color-primary-light);
}

.pagination-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination-info {
  font-size: var(--font-size-p);
  color: var(--color-primary);
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .table-elevated-minimal {
    font-size: 1.4rem;
  }
  
  .table-elevated-minimal th,
  .table-elevated-minimal td {
    padding: var(--space-3);
  }
  
  .pagination {
    flex-direction: column;
    gap: var(--space-4);
  }
}
</style>
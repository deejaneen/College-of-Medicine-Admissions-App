<template>
  <SuperAdminIndex>
<div class="admin-management-container">
  <div class="page-header">
        <h1 class="page-title">
          ADMIN TABLE
        </h1>
      </div>
   <!-- SUPERADMINS CARD -->
    <div class="card-container">
 <div class="admin-card">
    <div class="card-header">
      <div class="card-title-group">
        <h3 class="card-title">
          <span class="material-icons card-title-icon">admin_panel_settings</span>
          SuperAdmins
        </h3>
        <div class="filter-controls">
          <select 
            v-model="superadminStatusFilter" 
            @change="filterSuperAdmins" 
            class="status-filter-select mini"
          >
            <option value="all">All SuperAdmins</option>
            <option value="active">Active Only</option>
            <option value="inactive">Inactive Only</option>
          </select>
          <span v-if="superadmins.meta" class="meta-info">
            {{ superadmins.meta.from }}-{{ superadmins.meta.to }} of {{ superadmins.meta.total }}
          </span>
        </div>
      </div>
    </div>
    
    <div class="card-content">
      <table class="compact-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="admin in superadmins.data" :key="admin.id" class="admin-row">
            <td class="admin-name">
              <div class="name-with-icon">
                <span class="material-icons admin-icon">admin_panel_settings</span>
                {{ admin.name }}
              </div>
            </td>
            <td class="admin-email">{{ admin.email }}</td>
            <td>
              <span :class="`status-badge ${admin.is_active ? 'active' : 'inactive'}`">
                {{ admin.is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td>
              <div class="action-buttons">
                <Link 
                  :href="route('superadmin.admins.edit', admin.id)" 
                  class="action-btn edit-btn" 
                  title="Edit"
                >
                  <span class="material-icons">edit</span>
                </Link>
              </div>
            </td>
          </tr>
          <tr v-if="superadmins.data.length === 0">
            <td colspan="4" class="empty-state">
              <div class="empty-icon">👑</div>
              <div v-if="superadminStatusFilter === 'all'" class="empty-text">No superadmins found</div>
              <div v-else class="empty-text">No {{ superadminStatusFilter }} superadmins</div>
            </td>
          </tr>
        </tbody>
      </table>
      
      <!-- SUPERADMIN PAGINATION -->
      <div v-if="superadmins.links && superadmins.links.length > 3" class="pagination-section">
        <Pagination 
          :links="superadmins.links" 
          :from="superadmins.meta?.from"
          :to="superadmins.meta?.to"
          :total="superadmins.meta?.total"
          :current-page="superadmins.meta?.current_page"
          :last-page="superadmins.meta?.last_page"
          @page-changed="onSuperAdminPageChange"
        />
      </div>
    </div>
  </div>

  <!-- ADMINS CARD -->
  <div class="admin-card">
    <div class="card-header">
      <div class="card-title-group">
        <h3 class="card-title">
          <span class="material-icons card-title-icon">shield</span>
          Admins
        </h3>
        <div class="filter-controls">
          <select 
            v-model="adminStatusFilter" 
            @change="filterAdmins" 
            class="status-filter-select mini"
          >
            <option value="all">All Admins</option>
            <option value="active">Active Only</option>
            <option value="inactive">Inactive Only</option>
          </select>
          <span v-if="admins.meta" class="meta-info">
            {{ admins.meta.from }}-{{ admins.meta.to }} of {{ admins.meta.total }}
          </span>
        </div>
      </div>
    </div>
    
    <div class="card-content">
      <table class="compact-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="admin in admins.data" :key="admin.id" class="admin-row">
            <td class="admin-name">
              <div class="name-with-icon">
                <span class="material-icons admin-icon">shield</span>
                {{ admin.name }}
              </div>
            </td>
            <td class="admin-email">{{ admin.email }}</td>
            <td>
              <span :class="`status-badge ${admin.is_active ? 'active' : 'inactive'}`">
                {{ admin.is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td>
              <div class="action-buttons">
                <Link 
                  :href="route('superadmin.admins.edit', admin.id)" 
                  class="action-btn edit-btn" 
                  title="Edit"
                >
                  <span class="material-icons">edit</span>
                </Link>
              </div>
            </td>
          </tr>
          <tr v-if="admins.data.length === 0">
            <td colspan="4" class="empty-state">
              <div class="empty-icon">👨‍💼</div>
              <div v-if="adminStatusFilter === 'all'" class="empty-text">No admins found</div>
              <div v-else class="empty-text">No {{ adminStatusFilter }} admins</div>
            </td>
          </tr>
        </tbody>
      </table>
      
      <!-- ADMIN PAGINATION -->
      <div v-if="admins.links && admins.links.length > 3" class="pagination-section">
        <Pagination 
          :links="admins.links" 
          :from="admins.meta?.from"
          :to="admins.meta?.to"
          :total="admins.meta?.total"
          :current-page="admins.meta?.current_page"
          :last-page="admins.meta?.last_page"
          @page-changed="onAdminPageChange"
        />
      </div>
    </div>
  </div>
    </div>
 
</div>
 

</SuperAdminIndex>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import Pagination from '../auth-admin/Pagination.vue';
import SuperAdminIndex from '../SuperAdminIndex.vue';

interface Admin {
  id: number;
  name: string;
  email: string;
  role: string;
  is_active: boolean;
  created_at: string;
}

interface Props {
  superadmins: {
    data: Admin[];
    links?: any[];
    meta?: {
      current_page: number;
      from: number;
      to: number;
      total: number;
      last_page: number;
      per_page: number;
      path: string;
    };
  };
  admins: {
    data: Admin[];
    links?: any[];
    meta?: {
      current_page: number;
      from: number;
      to: number;
      total: number;
      last_page: number;
      per_page: number;
      path: string;
    };
  };
  currentUser: Admin;
  filters?: {
    superadmin_status?: string;
    admin_status?: string;
  };
}

const props = defineProps<Props>();

// Filter states - these control server-side filtering
const superadminStatusFilter = ref<string>(props.filters?.superadmin_status || 'all');
const adminStatusFilter = ref<string>(props.filters?.admin_status || 'all');

// Server-side filtering functions (exactly like your applications page)
function filterSuperAdmins(): void {
  // Use router.get() to reload with new filters, just like Inertia.reload()
  router.get(route('superadmin.editUser'), {
    superadmin_status: superadminStatusFilter.value,
    superadmin_page: 1, // Reset to first page when filtering
    admin_status: adminStatusFilter.value,
    admin_page: props.admins.meta?.current_page || 1
  }, {
    preserveScroll: true,
    preserveState: true,
    replace: true
  });
}

function filterAdmins(): void {
  router.get(route('superadmin.editUser'), {
    admin_status: adminStatusFilter.value,
    admin_page: 1, // Reset to first page when filtering
    superadmin_status: superadminStatusFilter.value,
    superadmin_page: props.superadmins.meta?.current_page || 1
  }, {
    preserveScroll: true,
    preserveState: true,
    replace: true
  });
}

// Handle pagination changes (preserving filters)
function onSuperAdminPageChange(page: number): void {
  router.get(route('superadmin.editUser'), {
    superadmin_status: superadminStatusFilter.value,
    superadmin_page: page,
    admin_status: adminStatusFilter.value,
    admin_page: props.admins.meta?.current_page || 1
  }, {
    preserveScroll: true,
    preserveState: true,
    replace: true
  });
}

function onAdminPageChange(page: number): void {
  router.get(route('superadmin.editUser'), {
    admin_status: adminStatusFilter.value,
    admin_page: page,
    superadmin_status: superadminStatusFilter.value,
    superadmin_page: props.superadmins.meta?.current_page || 1
  }, {
    preserveScroll: true,
    preserveState: true,
    replace: true
  });
}
</script>


<style scoped>
/* ADMIN MANAGEMENT STYLES */
.admin-management-container {
    padding: var(--space-6);
    background: var(--color-light-bg);
    min-height: 100vh;
}

.page-header {
    margin-bottom: var(--space-8);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.page-title {
    font-size: var(--font-size-h4);
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: var(--space-2);
}

.create-admin-btn {
    background: var(--color-primary);
    color: white;
    border: none;
    padding: var(--space-3) var(--space-5);
    border-radius: var(--radius-m);
    font-size: var(--font-size-p);
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: var(--space-2);
    transition: background 0.2s ease;
    text-decoration: none;
}

.create-admin-btn:hover {
    background: var(--color-primary-80);
}

.admin-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--space-6);
    max-width: 1600px;
    margin: 0 auto;
}

@media (min-width: 1024px) {
    .admin-grid {
        grid-template-columns: 1fr 1fr;
        gap: var(--space-8);
    }
}
.card-container {
  display: flex;
  justify-content: center;
  gap: var(--space-8);
}
.admin-card {
    background: var(--color-background);
    border-radius: var(--radius-m);
    overflow: hidden;
    box-shadow: var(--elevation-1);
    border: 1px solid var(--color-border);
    border-left: 4px solid var(--color-primary);
    height: fit-content;
    display: flex;
    flex-direction: column;
    margin-bottom: var(--space-6);
    width: 100%;
}

.admin-card:first-child {
    border-left-color: #8b5cf6;
}

.admin-card:last-child {
    border-left-color: #3b82f6;
}

.card-header {
    padding: var(--space-5);
    border-bottom: 1px solid var(--color-border);
    background: var(--color-light-bg-40);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: var(--space-4);
}

.card-title-group {
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
}

.card-title {
    font-size: var(--font-size-h6);
    font-weight: 600;
    color: var(--color-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: var(--space-2);
    text-transform: uppercase;
}

.card-title-icon {
    color: var(--color-primary);
    font-size: var(--font-size-h5);
}

.meta-info {
    font-size: 1.2rem;
    color: var(--color-text-light);
    font-weight: 500;
}

.card-actions {
    display: flex;
    gap: var(--space-2);
}

.card-action {
    background: var(--color-primary);
    color: white;
    border: none;
    padding: var(--space-2) var(--space-4);
    border-radius: var(--radius-s);
    font-size: var(--font-size-p);
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: var(--space-2);
    transition: background 0.2s ease;
}

.card-action.secondary {
    background: var(--color-secondary);
}

.card-action:hover {
    opacity: 0.9;
}

.filter-controls {
    display: flex;
    align-items: center;
    gap: var(--space-4);
}

.status-filter-select.mini {
    padding: var(--space-1) var(--space-3);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-s);
    font-size: 1.2rem;
    background: var(--color-background);
    color: var(--color-foreground);
    cursor: pointer;
    min-width: 140px;
}

.card-content {
    padding: var(--space-5);
    flex: 1;
    display: flex;
    flex-direction: column;
}

.compact-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 1.4rem;
    margin-bottom: var(--space-4);
}

.compact-table thead {
    background: var(--color-light-bg);
}

.compact-table th {
    padding: var(--space-3) var(--space-4);
    text-align: left;
    font-weight: 600;
    color: var(--color-text-light);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    font-size: 1.2rem;
    border-bottom: 1px solid var(--color-border);
}

.compact-table td {
    padding: var(--space-3) var(--space-4);
    border-bottom: 1px solid var(--color-border);
    vertical-align: middle;
}

.compact-table tr:last-child td {
    border-bottom: none;
}

.admin-row:hover {
    background: var(--color-light-bg-40);
}

.admin-name {
    font-weight: 500;
    color: var(--color-foreground);
    white-space: nowrap;
}

.name-with-icon {
    display: flex;
    align-items: center;
    gap: var(--space-2);
}

.admin-icon {
    color: var(--color-primary);
    font-size: var(--font-size-h6);
}

.deactivate-btn {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
    border: none;
    padding: var(--space-1) var(--space-2);
    border-radius: var(--radius-s);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.deactivate-btn:hover:not(:disabled) {
    background: #f59e0b;
    color: white;
}

.restore-btn {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: none;
    padding: var(--space-1) var(--space-2);
    border-radius: var(--radius-s);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.restore-btn:hover:not(:disabled) {
    background: #10b981;
    color: white;
}

.admin-email {
    color: var(--color-text-light);
    white-space: nowrap;
}

.status-badge {
    display: inline-block;
    padding: var(--space-1) var(--space-3);
    border-radius: var(--radius-s);
    font-size: 1.2rem;
    font-weight: 600;
    text-transform: uppercase;
}
.active{
  color: var(--color-primary-light);
  background-color: var(--color-light-bg-20);

}
.inactive{
  color: var(--color-rejected-text);
  background-color: var(--color-rejected);

}
.status-superadmin {
    background: rgba(139, 92, 246, 0.1);
    color: #8b5cf6;
    border: 1px solid #8b5cf6;
}

.status-admin {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    border: 1px solid #3b82f6;
}

.action-buttons {
    display: flex;
    gap: var(--space-2);
}

.action-btn {
    border: none;
    padding: var(--space-1) var(--space-2);
    border-radius: var(--radius-s);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    text-decoration: none;
}

.edit-btn {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    text-decoration: none;
}

.edit-btn:hover {
    background: #10b981;
    color: white;
}

.promote-btn {
    background: rgba(139, 92, 246, 0.1);
    color: #8b5cf6;
}

.promote-btn:hover {
    background: #8b5cf6;
    color: white;
}

.demote-btn {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.demote-btn:hover {
    background: #3b82f6;
    color: white;
}

.empty-state {
    text-align: center;
    padding: var(--space-8) var(--space-4);
    color: var(--color-text-light);
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: var(--space-3);
    opacity: 0.5;
}

.empty-text {
    font-size: 1.4rem;
    font-style: italic;
}

.pagination-section {
    margin-top: auto;
    padding-top: var(--space-5);
    border-top: 1px solid var(--color-border);
}

/* Responsive */
@media (max-width: 768px) {
    .admin-management-container {
        padding: var(--space-4);
    }
    
    .page-header {
        flex-direction: column;
        gap: var(--space-4);
        align-items: flex-start;
    }
    
    .create-admin-btn {
        align-self: flex-start;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--space-3);
    }
    
    .filter-controls {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--space-2);
    }
    
    .action-buttons {
        flex-wrap: wrap;
    }
}
</style>
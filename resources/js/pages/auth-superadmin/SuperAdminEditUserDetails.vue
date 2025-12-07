<template>
  <SuperAdminIndex>
    <div class="admin-edit-container">
      <!-- Success Modal -->
      <div v-if="showSuccessModal" class="modal-overlay" @click.self="closeSuccessModal">
        <div class="modal-content">
          <div class="modal-icon success">
            <span class="material-icons">check_circle</span>
          </div>
          <h3 class="modal-title">Admin Updated Successfully!</h3>
          <p class="modal-message">
            The admin user <strong>{{ admin.name }}</strong> has been updated successfully.
          </p>
          <div class="modal-actions">
            <button 
              class="btn-secondary"
              @click="closeSuccessModal"
            >
              Stay on Page
            </button>
            <Link 
              :href="route('superadmin.editUser')" 
              class="btn-primary"
            >
              Back to Admin List
            </Link>
          </div>
        </div>
      </div>
      
      <!-- Error Modal -->
      <div v-if="showErrorModal" class="modal-overlay">
        <div class="modal-content">
          <div class="modal-icon error">
            <span class="material-icons">error_outline</span>
          </div>
          <h3 class="modal-title">{{ errorTitle }}</h3>
          <p class="modal-message">
            {{ errorMessage }}
          </p>
          <div class="modal-actions">
            <button 
              class="btn-primary"
              @click="closeErrorModal"
            >
              Close
            </button>
          </div>
        </div>
      </div>
      
      <!-- Confirmation Modal -->
      <div v-if="showConfirmModal" class="modal-overlay">
        <div class="modal-content">
          <div class="modal-icon warning">
            <span class="material-icons">help_outline</span>
          </div>
          <h3 class="modal-title">Confirm Admin Update</h3>
          <p class="modal-message">
            Are you sure you want to update this admin account for 
            <strong>{{ form.name || admin.name }}</strong>?
          </p>
          <div class="modal-actions">
            <button class="btn-secondary" @click="showConfirmModal = false">
              Cancel
            </button>
            <button class="btn-primary" @click="confirmUpdate">
              Yes, Update Admin
            </button>
          </div>
        </div>
      </div>
      
      <!-- Page Header -->
      <div class="page-header">
        <h1 class="page-title">
          Edit Administrator
          <span class="slash">/</span>
          <span class="name">{{ admin.name }}</span>
        </h1>
      </div>
      
      <!-- Main Form Container -->
      <div class="admin-form-container">
        <!-- Header Section -->
        <div class="display-header">
          <div class="header-main">
            <div class="admin-meta">
              <span class="meta-badge">Role: {{ admin.role }}</span>
              <span class="meta-badge">ID: {{ admin.id }}</span>
              <span :class="`status-badge status-${getStatusClass(admin.role)}`">
                {{ getStatusText(admin.role) }}
              </span>
            </div>
          </div>
        </div>

        <!-- Form Content -->
        <form @submit.prevent="validateAndSubmit" class="display-content">
          <!-- Admin Information Card -->
          <div class="info-card">
            <div class="card-header">
              <span class="material-icons">person</span>
              <h2 class="card-title">Admin Information</h2>
            </div>
            <div class="field-grid">
              <div class="field-row">
                <label class="field-label">Full Name *</label>
                <input 
                  v-model="form.name" 
                  type="text" 
                  class="form-input"
                  required
                  placeholder="Enter full name"
                  :class="{ 'error': form.errors.name }"
                >
                <div v-if="form.errors.name" class="error-text">
                  {{ form.errors.name }}
                </div>
              </div>
              <div class="field-row">
                <label class="field-label">Email Address *</label>
                <input 
                  v-model="form.email" 
                  type="email" 
                  class="form-input"
                  required
                  placeholder="admin@slsu.edu.ph"
                  :class="{ 'error': form.errors.email }"
                >
                <div v-if="form.errors.email" class="error-text">
                  {{ form.errors.email }}
                </div>
              </div>
              <div class="field-row">
                <label class="field-label">Role *</label>
                <select 
                  v-model="form.role" 
                  class="form-select"
                  required
                  :class="{ 'error': form.errors.role }"
                >
                  <option value="Admin">Admin</option>
                  <option value="SuperAdmin">SuperAdmin</option>
                  <option value="InactiveAdmin">Inactive Admin</option>
                  <option value="InactiveSuperAdmin">Inactive SuperAdmin</option>
                </select>
                <div v-if="form.errors.role" class="error-text">
                  {{ form.errors.role }}
                </div>
              </div>
            </div>
          </div>

          <!-- Password Update Card -->
          <div class="info-card">
            <div class="card-header">
              <span class="material-icons">lock</span>
              <h2 class="card-title">Password Update</h2>
            </div>
            <div class="field-grid">
              <div class="field-row">
                <label class="field-label ">New Password <span class="secondary">(leave blank to keep current)</span></label>
                <div class="input-wrapper">
                  <input 
                    v-model="form.password" 
                    :type="showPassword ? 'text' : 'password'" 
                    class="form-input"
                    placeholder="Enter new password"
                    autocomplete="new-password"
                    :class="{ 'error': form.errors.password }"
                  >
                  <button 
                    type="button" 
                    class="toggle-password"
                    @click="showPassword = !showPassword"
                  >
                    <span class="material-icons">
                      {{ showPassword ? 'visibility_off' : 'visibility' }}
                    </span>
                  </button>
                </div>
                <div v-if="form.errors.password" class="error-text">
                  {{ form.errors.password }}
                </div>
              </div>
              <div class="field-row">
                <label class="field-label">Confirm Password</label>
                <div class="input-wrapper">
                  <input 
                    v-model="form.password_confirmation" 
                    :type="showConfirmPassword ? 'text' : 'password'" 
                    class="form-input"
                    placeholder="Confirm new password"
                    autocomplete="new-password"
                  >
                  <button 
                    type="button" 
                    class="toggle-password"
                    @click="showConfirmPassword = !showConfirmPassword"
                  >
                    <span class="material-icons">
                      {{ showConfirmPassword ? 'visibility_off' : 'visibility' }}
                    </span>
                  </button>
                </div>
              </div>
              <div class="field-help">
                <span class="material-icons">info</span>
                <span class="help-text">Password must be at least 8 characters long</span>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="action-buttons">
            <Link :href="route('superadmin.editUser')" class="btn btn-secondary">
              <span class="material-icons">arrow_back</span>
              Back to List
            </Link>
            <button 
              type="submit" 
              class="btn btn-success" 
              :disabled="form.processing"
            >
              <span class="material-icons" v-if="!form.processing">save</span>
              <span class="material-icons" v-else>hourglass_empty</span>
              {{ form.processing ? 'Updating...' : 'Update Admin' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </SuperAdminIndex>
</template>

<script lang="ts" setup>
import { Link, useForm } from '@inertiajs/vue3';
import { onMounted, computed, ref, watch } from 'vue';
import SuperAdminIndex from '../SuperAdminIndex.vue';

interface Props {
  admin: {
    id: number;
    name: string;
    email: string;
    role: string;
  };
  errors?: Record<string, string>;
}

const props = defineProps<Props>();

// Modal states
const showSuccessModal = ref<boolean>(false);
const showErrorModal = ref<boolean>(false);
const showConfirmModal = ref<boolean>(false);
const errorTitle = ref<string>('Error!');
const errorMessage = ref<string>('');
const showPassword = ref<boolean>(false);
const showConfirmPassword = ref<boolean>(false);

// Load material icons
onMounted(() => {
  if (!document.querySelector('link[href*="material-icons"]')) {
    const link = document.createElement('link');
    link.href = 'https://fonts.googleapis.com/icon?family=Material+Icons';
    link.rel = 'stylesheet';
    document.head.appendChild(link);
  }
});

// Helper functions
const getStatusClass = (role: string): string => {
  if (role.includes('Inactive')) return 'inactive';
  if (role.includes('SuperAdmin')) return 'active';
  return 'active';
};

const getStatusText = (role: string): string => {
  if (role.includes('Inactive')) return 'Inactive';
  if (role.includes('SuperAdmin')) return 'Active';
  return 'Active';
};

const validatePassword = (password: string, confirmation: string): boolean => {
  if (password && password.length < 8) return false;
  if (password && password !== confirmation) return false;
  return true;
};

// Form setup
const form = useForm({
  name: props.admin.name,
  email: props.admin.email,
  role: props.admin.role,
  password: '',
  password_confirmation: '',
});

// Watch for password changes to clear errors
watch(() => form.password, () => {
  if (form.errors.password) delete form.errors.password;
  if (form.errors.password_confirmation) delete form.errors.password_confirmation;
});

watch(() => form.password_confirmation, () => {
  if (form.errors.password_confirmation) delete form.errors.password_confirmation;
});

// Computed properties
const isPasswordValid = computed(() => {
  return validatePassword(form.password, form.password_confirmation);
});

const hasChanges = computed(() => {
  return form.name !== props.admin.name ||
         form.email !== props.admin.email ||
         form.role !== props.admin.role ||
         (form.password && form.password.length > 0);
});

// Modal functions
function showSuccess(): void {
  showSuccessModal.value = true;
  
  // Auto-redirect after 5 seconds
  setTimeout(() => {
    if (showSuccessModal.value) {
      window.location.href = route('superadmin.editUser');
    }
  }, 5000);
}

function showError(title: string, message: string): void {
  errorTitle.value = title;
  errorMessage.value = message;
  showErrorModal.value = true;
  
  // Auto-hide after 8 seconds
  setTimeout(() => {
    if (showErrorModal.value) {
      closeErrorModal();
    }
  }, 8000);
}

function closeSuccessModal(): void {
  showSuccessModal.value = false;
  // Redirect to list when closing success modal
  window.location.href = route('superadmin.editUser');
}

function closeErrorModal(): void {
  showErrorModal.value = false;
  errorTitle.value = 'Error!';
  errorMessage.value = '';
}

// Form validation and submission
function validateAndSubmit(): void {
  // Clear previous errors
  form.clearErrors();
  
  if (!hasChanges.value) {
    showError('No Changes', 'No changes detected to update.');
    return;
  }

  if (form.password && !isPasswordValid.value) {
    if (form.password.length < 8) {
      showError('Invalid Password', 'Password must be at least 8 characters long.');
      return;
    }
    if (form.password !== form.password_confirmation) {
      showError('Password Mismatch', 'Passwords do not match.');
      return;
    }
  }

  // Show confirmation modal
  showConfirmModal.value = true;
}

function confirmUpdate(): void {
  showConfirmModal.value = false;
  
  form.put(route('superadmin.admins.update', props.admin.id), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      form.reset('password', 'password_confirmation');
      showPassword.value = false;
      showConfirmPassword.value = false;
      showSuccess();
    },
    onError: (errors) => {
      console.log('Update errors:', errors);
      
      // Handle specific errors
      if (errors.email) {
        showError('Email Error', errors.email);
      } else if (errors.password) {
        showError('Password Error', errors.password);
      } else if (errors.role) {
        showError('Role Error', errors.role);
      } else if (errors.name) {
        showError('Name Error', errors.name);
      } else {
        showError('Update Failed', 'Failed to update admin. Please check the form for errors.');
      }
    },
  });
}
</script>

<style scoped>
  /* Modal Styles - Copied from your add admin page */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  backdrop-filter: blur(4px);
}

.modal-content {
  background: var(--color-background);
  border-radius: var(--radius-m);
  padding: var(--space-8);
  max-width: 48rem;
  width: 90%;
  text-align: center;
  box-shadow: var(--elevation-3);
  animation: slideUp 0.3s ease;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.modal-icon {
  width: 6.4rem;
  height: 6.4rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto var(--space-4);
}

.modal-icon.success {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.modal-icon.success .material-icons {
  font-size: var(--font-size-h4);
  color: white;
}

.modal-icon.error {
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.modal-icon.error .material-icons {
  font-size: var(--font-size-h4);
  color: white;
}

.modal-icon.warning {
  background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.modal-icon.warning .material-icons {
  font-size: var(--font-size-h4);
  color: white;
}

.modal-title {
  font-size: var(--font-size-h5);
  font-weight: 600;
  color: var(--color-foreground);
  margin-bottom: var(--space-3);
}

.modal-message {
  font-size: var(--font-size-p);
  color: var(--color-text-light);
  line-height: 1.6;
  margin-bottom: var(--space-6);
}

.modal-message strong {
  color: var(--color-foreground);
  font-weight: 600;
}

.modal-actions {
  display: flex;
  justify-content: center;
  gap: var(--space-4);
}

/* Add password visibility toggle styles */
.input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.toggle-password {
  position: absolute;
  right: var(--space-3);
  background: none;
  border: none;
  color: var(--color-text-light);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: var(--space-1);
  border-radius: var(--radius-s);
  transition: color 0.2s ease;
}

.toggle-password:hover {
  color: var(--color-primary);
}

.toggle-password .material-icons {
  font-size: var(--font-size-h6);
}

/* Button styles for modals */
.btn-primary, .btn-secondary {
  padding: var(--space-3) var(--space-6);
  border-radius: var(--radius-s);
  font-size: var(--font-size-p);
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: var(--space-2);
  transition: all 0.2s ease;
  border: none;
  min-width: 120px;
}

.btn-primary {
  background: var(--color-primary);
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: var(--color-primary-80);
  transform: translateY(-1px);
  box-shadow: var(--elevation-2);
}

.btn-secondary {
  background: var(--color-background);
  color: var(--color-foreground);
  border: 1px solid var(--color-border);
}

.btn-secondary:hover {
  background: var(--color-light-bg-40);
  border-color: var(--color-primary);
  transform: translateY(-1px);
}

/* Responsive modals */
@media (max-width: 768px) {
  .modal-content {
    padding: var(--space-6);
    margin: var(--space-4);
  }
  
  .modal-actions {
    flex-direction: column;
    gap: var(--space-2);
  }
  
  .btn-primary, .btn-secondary {
    width: 100%;
  }
}

.admin-edit-container {
  padding: var(--space-4);
  background: var(--color-light-bg);
  min-height: 100vh;
  font-family: var(--font-alt);
  box-sizing: border-box;
}

/* Main Container */
.admin-form-container {
  max-width: 1400px;
  margin: 0 auto;
  background: var(--color-light-bg);
  width: 100%;
}

/* Page Header */
.page-header {
  margin-bottom: var(--space-4);
}

.page-title {
  font-size: var(--font-size-h6);
  font-weight: 600;
  color: var(--color-primary);
  text-transform: uppercase;
  margin-bottom: var(--space-2);
  font-family: var(--font-sans);
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: var(--space-2);
  line-height: 1.3;
}

.page-title .slash {
  font-size: var(--font-size-h6);
  font-weight: 400;
  color: var(--color-neutral);
}

.page-title .name {
  color: var(--color-foreground);
  text-transform: capitalize;
  font-weight: 600;
  word-break: break-word;
}

/* Header Section */
.display-header {
  background: var(--color-background);
  border-radius: var(--radius-l);
  padding: var(--space-4);
  margin-bottom: var(--space-4);
  box-shadow: var(--elevation-2);
  border-left: 4px solid var(--color-primary);
}

.header-main {
  display: flex;
  flex-direction: column;
  gap: var(--space-3);
  margin-bottom: var(--space-3);
}

.admin-meta {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2);
  align-items: center;
}

.meta-badge {
  display: inline-flex;
  align-items: center;
  padding: var(--space-1) var(--space-2);
  background: var(--color-light-bg);
  border-radius: var(--radius-m);
  font-size: 1.4rem; 
  font-weight: 500;
  color: var(--color-primary);
  white-space: nowrap;
}

.status-badge {
  padding: var(--space-1) var(--space-3);
  border-radius: var(--radius-m);
  font-size: 1.4rem; 
  font-weight: 600;
  text-transform: uppercase;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: var(--color-rejected-text);
}

.status-active {
  background: var(--color-light-bg-20);
  color: var(--color-primary-light);
}

.status-inactive {
  background: var(--color-rejected);
  color: var(-color-rejected-text);
}

/* Content Grid - MOBILE FIRST (single column) */
.display-content {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--space-4);
  margin-bottom: var(--space-4);
}

/* Card Components */
.info-card {
  background: var(--color-background);
  border-radius: var(--radius-l);
  padding: var(--space-4);
  box-shadow: var(--elevation-1);
  border: 1px solid var(--color-light-bg-40);
  border-left: 4px solid var(--color-primary);
  overflow: hidden;
}

.card-header {
  display: flex;
  align-items: center;
  gap: var(--space-2);
  margin-bottom: var(--space-3);
  padding-bottom: var(--space-2);
  border-bottom: 1px solid var(--color-light-bg);
}

.card-header .material-icons {
  color: var(--color-primary);
  font-size: 20px;
}

.card-title {
  font-size: var(--font-size-p);
  font-weight: 600;
  color: var(--color-primary);
  margin: 0;
}


/* Form Fields */
.field-grid {
  display: grid;
  gap: var(--space-3);
}

.field-row {
  display: flex;
  flex-direction: column;
  gap: var(--space-1);
}

.field-label {
  font-weight: 600;
  color: var(--color-text-light);
  font-size: 1.2rem; 
  text-transform: uppercase;
  letter-spacing: 0.3px;
   font-family: var(--font-alt);
}
.secondary{
  color: var(--color-secondary);
}

.form-input,
.form-select {
  padding: var(--space-2) var(--space-3);
  border: 1px solid var(--color-neutral);
  border-radius: var(--radius-m);
  font-size: 1.4rem;
  font-family: var(--font-alt);
  transition: all 0.2s ease;
  background: var(--color-background);
  color: var(--color-foreground);
}

.form-input:focus,
.form-select:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 3px var(--color-bright-green-25);
}

.form-input::placeholder {
  color: var(--color-text-light);
}

.form-select {
  cursor: pointer;
}

.field-help {
  display: flex;
  align-items: center;
  gap: var(--space-1);
  padding: var(--space-2);
  background: var(--color-light-bg);
  border-radius: var(--radius-m);
  margin-top: var(--space-1);
}

.field-help .material-icons {
  color: var(--color-primary);
  font-size: 16px;
}

.help-text {
  font-size: 1.2rem;
  color: var(--color-text-light);
}

/* Action Buttons */
.action-buttons {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: var(--space-2);
  padding-top: var(--space-4);
  border-top: 1px solid var(--color-neutral);
}

.btn {
  padding: var(--space-3);
  border: none;
  border-radius: var(--radius-m);
  font-size: 1.4rem; 
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: var(--space-1);
  text-transform: uppercase;
  letter-spacing: 0.3px;
  min-height: 60px;
  justify-content: center;
  text-align: center;
  overflow: hidden;
  text-decoration: none;
}

.btn .material-icons {
  font-size: 18px;
}

.btn-secondary {
  background: var(--color-light-bg);
  color: var(--color-primary);
  border: 1px solid var(--color-primary);
}

.btn-secondary .material-icons {
  color: var(--color-primary);
}

.btn-secondary:hover {
  background: var(--color-primary);
  color: var(--color-background);
}

.btn-secondary:hover .material-icons {
  color: var(--color-background);
}

.btn-success {
  background: var(--color-primary);
  color: var(--color-background);
  border: 1px solid var(--color-primary);
}

.btn-success:hover {
  background: var(--color-primary-light);
}

.btn-success:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* TABLET BREAKPOINT (768px and up) */
@media (min-width: 768px) {
  .admin-edit-container {
    padding: var(--space-6);
  }
  
  .page-header {
    margin-bottom: var(--space-6);
  }
  
  .page-title {
    font-size: var(--font-size-h5);
  }
  
  .display-header {
    padding: var(--space-5);
    margin-bottom: var(--space-5);
  }
  
  .display-content {
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-5);
    margin-bottom: var(--space-5);
  }
  
  .info-card {
    padding: var(--space-5);
  }
  
  .card-header {
    margin-bottom: var(--space-4);
    padding-bottom: var(--space-3);
    border-bottom: 2px solid var(--color-light-bg);
  }
  
  .card-header .material-icons {
    font-size: 24px;
  }
  
  .card-title {
    font-size: var(--font-size-h6);
    text-transform: uppercase;
  }
  
  .field-row {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: var(--space-4);
    align-items: center;
  }
  
  .field-label {
    font-size: 1.2rem;
  }
  
  .form-input,
  .form-select {
    font-size: var(--font-size-p);
    padding: var(--space-3) var(--space-4);
  }
  
  .help-text {
    font-size: var(--font-size-p);
  }
  
  .action-buttons {
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-3);
    padding-top: var(--space-5);
  }
  
  .btn {
    flex-direction: row;
    min-height: auto;
    padding: var(--space-3) var(--space-4);
    font-size: 1rem;
  }
  
  .btn .material-icons {
    font-size: 20px;
  }
}

/* DESKTOP BREAKPOINT (1024px and up) */
@media (min-width: 1024px) {
  .admin-edit-container {
    padding: var(--space-8);
  }
  
  .page-header {
    margin-bottom: var(--space-8);
  }
  
  .page-title {
    font-size: var(--font-size-h4);
  }
  
  .display-header {
    padding: var(--space-6);
    margin-bottom: var(--space-6);
    box-shadow: var(--elevation-3);
  }
  
  .display-content {
    gap: var(--space-6);
    margin-bottom: var(--space-6);
  }
  
  .info-card {
    padding: var(--space-6);
    box-shadow: var(--elevation-2);
  }
  
  .action-buttons {
    padding-top: var(--space-6);
  }
  
  .btn {
    padding: var(--space-3) var(--space-5);
  }
}

/* Extra Small Screens (less than 375px) */
@media (max-width: 374px) {
  .admin-edit-container {
    padding: var(--space-2);
  }
  
  .page-title {
    font-size: 1rem; /* ~16px */
    flex-direction: column;
    align-items: flex-start;
    gap: var(--space-1);
  }
  
  .page-title .slash {
    display: none;
  }
  
  .display-header,
  .info-card {
    padding: var(--space-3);
  }
  
  .action-buttons {
    grid-template-columns: 1fr;
  }
  
  .btn {
    min-height: 50px;
  }
}

/* Prevent horizontal overflow */
.display-content,
.info-card {
  max-width: 100%;
  overflow-x: hidden;
}

/* Form validation styles */
.form-input:invalid:not(:focus):not(:placeholder-shown) {
  border-color: var(--color-error);
}

/* Loading state */
@keyframes spin {
  to { transform: rotate(360deg); }
}

.btn-success .material-icons[class*="hourglass"] {
  animation: spin 1s linear infinite;
}
</style>
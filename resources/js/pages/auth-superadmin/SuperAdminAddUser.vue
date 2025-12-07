<template>
    <SuperAdminIndex>
        <div class="dashboard-container">
            <!-- Page Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1 class="page-title text-primary">Add New Admin</h1>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="content-card">
                <div class="card-header">
                    <div class="card-title-group">
                        <h6 class="card-title uppercase">Admin Information</h6>
                    </div>
                </div>
                
                <form @submit.prevent="submitForm" class="card-content">
                    <div class="form-grid">
                        <!-- Left Column -->
                        <div class="form-section">
                            <!-- Basic Information -->
                            <div class="form-group">
                                <label class="form-label required" for="full_name">Full Name</label>
                                <div class="input-wrapper">
                                    <input
                                        type="text"
                                        id="full_name"
                                        v-model="form.full_name"
                                        class="form-input"
                                        :class="{ 'error': errors.full_name }"
                                        placeholder="Enter full name"
                                        required
                                    />
                                    <span class="material-icons input-icon">person</span>
                                </div>
                                <div v-if="errors.full_name" class="error-message">
                                    {{ errors.full_name }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label required" for="email">Email Address</label>
                                <div class="input-wrapper">
                                    <input
                                        type="email"
                                        id="email"
                                        v-model="form.email"
                                        class="form-input"
                                        :class="{ 'error': errors.email }"
                                        placeholder="Enter email address"
                                        required
                                    />
                                    <span class="material-icons input-icon">email</span>
                                </div>
                                <div v-if="errors.email" class="error-message">
                                    {{ errors.email }}
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="form-section">
                            <!-- Password -->
                            <div class="form-group">
                                <label class="form-label required" for="password">Password</label>
                                <div class="input-wrapper">
                                    <input
                                        :type="showPassword ? 'text' : 'password'"
                                        id="password"
                                        v-model="form.password"
                                        class="form-input"
                                        :class="{ 'error': errors.password }"
                                        placeholder="Enter password"
                                        required
                                    />
                                    <span class="material-icons input-icon">lock</span>
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
                                <div v-if="errors.password" class="error-message">
                                    {{ errors.password }}
                                </div>
                                <div class="password-requirements">
                                    <p class="requirement-title text-lg">Password must contain:</p>
                                    <ul class="requirement-list">
                                        <li :class="{ 'met': passwordStrength.length }">At least 8 characters</li>
                                        <li :class="{ 'met': passwordStrength.uppercase }">One uppercase letter</li>
                                        <li :class="{ 'met': passwordStrength.lowercase }">One lowercase letter</li>
                                        <li :class="{ 'met': passwordStrength.number }">One number</li>
                                        <li :class="{ 'met': passwordStrength.special }">One special character</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label required" for="password_confirmation">Confirm Password</label>
                                <div class="input-wrapper">
                                    <input
                                        :type="showConfirmPassword ? 'text' : 'password'"
                                        id="password_confirmation"
                                        v-model="form.password_confirmation"
                                        class="form-input"
                                        :class="{ 'error': errors.password_confirmation }"
                                        placeholder="Confirm password"
                                        required
                                    />
                                    <span class="material-icons input-icon">lock</span>
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
                                <div v-if="errors.password_confirmation" class="error-message">
                                    {{ errors.password_confirmation }}
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="form-label required" for="role">Role</label>
                                <div class="input-wrapper">
                                    <select
                                        id="role"
                                        v-model="form.role"
                                        class="form-input"
                                        :class="{ 'error': errors.role }"
                                        required
                                    >
                                        <option value="" disabled>Select a role</option>
                                        <option value="Admin">Admin</option>
                                        <option value="SuperAdmin">Super Admin</option>
                                    </select>
                                    <span class="material-icons input-icon">admin_panel_settings</span>
                                </div>
                                <div v-if="errors.role" class="error-message">
                                    {{ errors.role }}
                                </div>
                            </div>
                        </div>
                    </div>
                   

                    <!-- Note about Admin Access -->
                    <div class="access-note color-secondary" style="margin-bottom: 0;">
                        <div class="note-icon">
                            <span class="material-icons">info</span>
                        </div>
                        <div class="note-content">
                            <h6 class="note-title">Admin Access Information</h6>
                            <p class="note-text">
                                All admin users have full access to the admin dashboard, applications, users, 
                                and application periods. This admin will be able to log in immediately after creation.
                            </p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button 
                            type="button" 
                            class="btn-secondary"
                            @click="resetForm"
                        >
                            Clear
                        </button>
                        <button 
                            type="submit" 
                            class="btn-primary"
                            :disabled="isSubmitting"
                        >
                            <span v-if="isSubmitting" class="spinner"></span>
                            <span v-else class="material-icons">person_add</span>
                            {{ isSubmitting ? 'Creating...' : 'Create Admin User' }}
                        </button>
                    </div>
                </form>
            </div>
            <!-- CONFIRMATION MODAL -->
            <div v-if="showConfirmModal" class="modal-overlay">
                <div class="modal-content">
                    <div class="modal-icon warning">
                        <span class="material-icons">help_outline</span>
                    </div>

                    <h3 class="modal-title">Confirm Admin Creation</h3>

                    <p class="modal-message">
                        Are you sure you want to create this admin account for 
                        <strong>{{ form.full_name || 'this user' }}</strong>?
                    </p>

                    <div class="modal-actions">
                        <button class="btn-secondary" @click="showConfirmModal = false">
                            Cancel
                        </button>
                        <button class="btn-primary" @click="confirmSubmission">
                            Yes, Create Admin
                        </button>
                    </div>
                </div>
            </div>

            <!-- Success Modal -->
            <div v-if="showSuccessModal" class="modal-overlay">
                <div class="modal-content">
                    <div class="modal-icon success">
                        <span class="material-icons">check_circle</span>
                    </div>
                    <h3 class="modal-title">Admin Created Successfully!</h3>
                    <p class="modal-message">
                        The admin user <strong>{{ createdAdminName }}</strong> has been created successfully.
                        They can now log in with the provided credentials.
                    </p>
                    <div class="modal-actions">
                        <button 
                            class="btn-secondary"
                            @click="showSuccessModal = false"
                        >
                            Create Another Admin
                        </button>

                        <Link 
                            :href="route('superadmin.editUser')" 
                            class="btn-primary"
                        >
                            View All Admins
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
                    <h3 class="modal-title">Failed to Create Admin</h3>
                    <p class="modal-message">
                        {{ errorMessage || 'There was an error creating the admin user. Please try again.' }}
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
        </div>
    </SuperAdminIndex>
</template>


<script setup lang="ts">

import { ref, computed, watch, nextTick } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import SuperAdminIndex from '../SuperAdminIndex.vue';

const form = useForm({
    full_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
});

const showPassword = ref(false);
const showConfirmPassword = ref(false);
const isSubmitting = ref(false);
const showSuccessModal = ref(false);
const createdAdminName = ref('');
const errors = ref<Record<string, string>>({});
const showConfirmModal = ref(false);
const showErrorModal = ref(false); 
const errorMessage = ref('');

const passwordStrength = computed(() => {
    const password = form.password;
    return {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password),
    };
});

watch(() => form.password, () => {
    if (errors.value.password_confirmation) delete errors.value.password_confirmation;
});

watch(() => form.password_confirmation, () => {
    if (errors.value.password_confirmation) delete errors.value.password_confirmation;
});

async function submitForm() {
    errors.value = {};

    // VALIDATION FIRST
    if (!form.full_name.trim()) errors.value.full_name = 'Full name is required';
    if (!form.email.trim()) errors.value.email = 'Email is required';
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) errors.value.email = 'Enter a valid email';

    if (!form.password) errors.value.password = 'Password is required';
    else if (!isPasswordStrong()) errors.value.password = 'Password does not meet requirements';

    if (!form.password_confirmation) errors.value.password_confirmation = 'Confirm your password';
    else if (form.password !== form.password_confirmation)
        errors.value.password_confirmation = 'Passwords do not match';

    if (!form.role) errors.value.role = 'Role is required';

    if (Object.keys(errors.value).length > 0) return;

    showConfirmModal.value = true;
}

async function confirmSubmission() {
    showConfirmModal.value = false; 
    isSubmitting.value = true;

    try {
        form.post(route('superadmin.admins.store'), {
            preserveScroll: true,
            onSuccess: (page) => {
                console.log('Success response:', page);
                createdAdminName.value = form.full_name;
                showSuccessModal.value = true;
                
                // Reset form after successful creation
                form.reset();
                showPassword.value = false;
                showConfirmPassword.value = false;
                errors.value = {};
                isSubmitting.value = false;
            },
            onError: (backendErrors) => {
                console.log('Error response:', backendErrors);
                
                // Handle Laravel validation errors
                if (backendErrors && typeof backendErrors === 'object') {
                    errors.value = backendErrors;
                    
                    // Extract email error if exists
                    if (backendErrors.email) {
                        errorMessage.value = Array.isArray(backendErrors.email) 
                            ? backendErrors.email[0] 
                            : backendErrors.email;
                        showErrorModal.value = true;
                    }
                } else {
                    // Handle server errors
                    errorMessage.value = 'Server error. Please try again.';
                    showErrorModal.value = true;
                }
                
                isSubmitting.value = false;
            },
            onFinish: () => {
                // Keep this as fallback
                setTimeout(() => {
                    if (isSubmitting.value) {
                        isSubmitting.value = false;
                    }
                }, 2000);
            }
        });

    } catch (e) {
        console.error('Unexpected error caught:', e);
        errorMessage.value = 'An unexpected error occurred. Please try again.';
        showErrorModal.value = true;
        isSubmitting.value = false;
    }
}

router.on('success', (event) => {
    console.log('Router success event:', event);
});

router.on('error', (errors) => {
    console.log('Router error event:', errors);
});

function closeErrorModal() {
    showErrorModal.value = false;
    errorMessage.value = '';
    
    // Focus on the email field after closing error modal
    nextTick(() => {
        const emailInput = document.getElementById('email') as HTMLInputElement;
        if (emailInput) {
            emailInput.focus();
        }
    });
}

function isPasswordStrong(): boolean {
    return Object.values(passwordStrength.value).every(condition => condition);
}

function resetForm() {
    form.reset();
    errors.value = {};
    showPassword.value = false;
    showConfirmPassword.value = false;
}
</script>

<style scoped>
.dashboard-container {
    padding: var(--space-6);
    margin: 0 auto;
    background: var(--color-light-bg);
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: var(--space-8);
    gap: var(--space-4);
}

.header-content {
    flex: 1;
}

.page-title {
    font-size: var(--font-size-h4);
    font-weight: 600;
    color: var(--color-foreground);
}

.page-subtitle {
    font-size: var(--font-size-p);
    color: var(--color-text-light);
    max-width: 60rem;
}

.btn-back:hover {
    background: var(--color-light-bg-40);
    border-color: var(--color-primary);
    transform: translateY(-1px);
}

.btn-back .material-icons {
    font-size: var(--font-size-h6);
}

/* Content Card */
.content-card {
    background: var(--color-background);
    border-radius: var(--radius-m);
    box-shadow: var(--elevation-1);
    border: 1px solid var(--color-border);
    border-left: 4px solid var(--color-primary);
    margin-bottom: var(--space-6);
    overflow: hidden;
    max-width: 120rem;
    margin: 0 auto;
}

.card-header {
    padding: var(--space-5) var(--space-6);
    border-bottom: 1px solid var(--color-border);
    background: var(--color-light-bg-40);
}

.card-title-group {
    display: flex;
    flex-direction: column;
    gap: var(--space-1);
}

.card-title {
    font-size: var(--font-size-h6);
    font-weight: 600;
    color: var(--color-foreground);
    margin: 0;
}

.card-subtitle {
    font-size: var(--font-size-p);
    color: var(--color-text-light);
}

/* Form Layout */
.card-content {
    padding: var(--space-6);
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-8);
    margin-bottom: var(--space-6);
}

@media (max-width: 992px) {
    .form-grid {
        grid-template-columns: 1fr;
        gap: var(--space-6);
    }
}

.form-section {
    display: flex;
    flex-direction: column;
    gap: var(--space-6);
}

/* Form Groups */
.form-group {
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
}

.form-label {
    font-size: var(--font-size-p);
    font-weight: 500;
    color: var(--color-foreground);
    display: flex;
    align-items: center;
    gap: var(--space-1);
}

.form-label.required::after {
    content: '*';
    color: var(--color-error);
    margin-left: 2px;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.form-input {
    width: 100%;
    padding: var(--space-3) var(--space-4);
    padding-left: var(--space-12);
    border: 1px solid var(--color-primary);
    border-radius: var(--radius-s);
    font-size: var(--font-size-p);
    color: var(--color-foreground);
    background: var(--color-background);
    transition: all 0.2s ease;

}

.form-input:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px var(--color-light-bg-20);
}

.form-input.error {
    border-color: var(--color-error);
}

.form-input.error:focus {
    box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.2);
}

.input-icon {
    position: absolute;
    left: var(--space-3);
    color: var(--color-text-light);
    font-size: var(--font-size-h6);
    pointer-events: none;
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

.error-message {
    font-size: 1.2rem;
    color: var(--color-error);
    margin-top: var(--space-1);
}

/* Password Requirements */
.password-requirements {
    margin-top: var(--space-3);
    padding: var(--space-3);
    background: var(--color-light-bg-40);
    border-radius: var(--radius-s);
    border-left: 3px solid var(--color-primary);
}

.requirement-title {
    font-size: 1.2rem;
    font-weight: 500;
    color: var(--color-foreground);
    margin-bottom: var(--space-2);
}

.requirement-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: var(--space-1);
}

.requirement-list li {
    font-size: 1.4rem;
    color: var(--color-text-light);
    display: flex;
    align-items: center;
    gap: var(--space-1);
}

.requirement-list li::before {
    content: '○';
    font-size: 1.4rem;
}

.requirement-list li.met {
    color: var(--color-primary);
}

.requirement-list li.met::before {
    content: '✓';
    color: var(--color-primary);
}

/* Page title */
.page-title.text-primary {
    color: var(--color-primary);
}

/* Card title */
.card-title.uppercase {
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--color-primary);
}

/* Access note */
.access-note.color-secondary {
    margin-bottom: 0;
    border-left: 3px solid var(--color-secondary);
}

.access-note.color-secondary .note-icon .material-icons {
    color: var(--color-secondary);
}

.access-note.color-secondary .note-title {
    color: var(--color-secondary);
}

.access-note.color-secondary .note-text {
    color: var(--color-text-light);
    font-size: var(--font-size-p);
    line-height: 1.7;
}

/* Password requirements */
.password-requirements .requirement-title.text-lg {
    font-size: var(--font-size-p);
    font-weight: 600;
    margin-bottom: var(--space-2);
}

/* Utility classes within the dashboard container */
.dashboard-container .text-primary {
    color: var(--color-primary);
}

.dashboard-container .text-secondary {
    color: var(--color-secondary);
}

.dashboard-container .text-lg {
    font-size: var(--font-size-p);
}

.dashboard-container .uppercase {
    text-transform: uppercase;
}

/* Access Note */
.access-note {
    display: flex;
    gap: var(--space-4);
    padding: var(--space-4);
    background: var(--color-light-bg-40);
    border-radius: var(--radius-m);
    border: 1px solid var(--color-border);
}

.note-icon {
    display: flex;
    align-items: flex-start;
}

.note-icon .material-icons {
    font-size: var(--font-size-h5);
    color: var(--color-primary);
}

.note-content {
    flex: 1;
}

.note-title {
    font-size: var(--font-size-p);
    font-weight: 600;
    color: var(--color-foreground);
    margin-bottom: var(--space-1);
}

.note-text {
    font-size: 1.2rem;
    color: var(--color-text-light);
    line-height: 1.6;
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: var(--space-4);
    padding-top: var(--space-6);
    border-top: 1px solid var(--color-border);
}

.btn-primary, .btn-secondary {
    padding: var(--space-3) var(--space-6);
    border-radius: var(--radius-s);
    font-size: var(--font-size-p);
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: var(--space-2);
    transition: all 0.2s ease;
    border: none;
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

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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

/* Spinner */
.spinner {
    width: 1.6rem;
    height: 1.6rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Success Modal */
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
.modal-icon.warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.modal-icon.warning .material-icons {
    font-size: var(--font-size-h4);
    color: white;
}


/* Responsive */
@media (max-width: 768px) {
    .dashboard-container {
        padding: var(--space-4);
    }
    
    .page-header {
        flex-direction: column;
        gap: var(--space-4);
    }
    
    .card-header,
    .card-content {
        padding: var(--space-4);
    }
    
    .access-note {
        flex-direction: column;
        gap: var(--space-2);
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
        justify-content: center;
    }
    
    .modal-content {
        padding: var(--space-6);
        margin: var(--space-4);
    }
    
    .modal-actions {
        flex-direction: column;
        gap: var(--space-2);
    }
}
</style>
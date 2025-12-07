<template>
    <AdminIndex>
        <div class="applications-container">
            <div class="page-header">
                <h1 class="page-title">Application Periods</h1>
            </div>

            <!-- TWO COLUMN LAYOUT -->
            <div class="applications-grid">
                <!-- CREATE NEW PERIOD CARD -->
                <div class="applications-card">
                    <div class="card-header">
                        <div class="card-title-group">
                            <h3 class="card-title">Open New Application Period</h3>
                        </div>
                    </div>
                    
                    <div class="card-content">
                        <form @submit.prevent="createApplicationPeriod" class="period-form">
                            <div class="form-group">
                                <label for="school_year" class="form-label">School Year Range:</label>
                                <input
                                    type="text"
                                    id="school_year"
                                    v-model="newApplicationPeriod.school_year"
                                    placeholder="e.g., 2025-2026"
                                    required
                                    class="form-input"
                                >
                                <small class="form-helper">Format: YYYY-YYYY (e.g., 2025-2026)</small>
                            </div>
                            <div class="form-group">
                                <label for="application_deadline" class="form-label">Application Deadline:</label>
                                <input
                                    type="date"
                                    id="application_deadline"
                                    v-model="newApplicationPeriod.application_deadline"
                                    required
                                    class="form-input"
                                >
                                <small class="form-helper">Forms will automatically close after this date</small>
                            </div>
                            <button type="submit" class="btn-primary">Open Application Period</button>
                        </form>
                    </div>
                </div>

                <!-- CURRENT ACTIVE PERIOD CARD -->
                <div class="applications-card" v-if="currentActive">
                    <div class="card-header">
                        <div class="card-title-group">
                            <h3 class="card-title">Current Active Period</h3>
                        </div>
                    </div>
                    
                    <div class="card-content">
                        <div class="active-period-info">
                            <div class="info-group">
                                <div class="info-label">School Year:</div>
                                <div class="info-value">{{ currentActive.school_year }}</div>
                            </div>
                            <div class="info-group">
                                <div class="info-label">Deadline:</div>
                                <div class="info-value">{{ formatDate(currentActive.application_deadline) }}</div>
                            </div>
                            <div class="info-group">
                                <div class="info-label">Status:</div>
                                <div class="info-value">
                                    <span :class="`status-badge status-${currentActive.is_open ? 'accepted' : 'rejected'}`">
                                        {{ currentActive.is_open ? 'Open' : 'Closed' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-actions" v-if="currentActive.is_open">
                            <button 
                                @click="closeApplications(currentActive)" 
                                class="btn-warning"
                            >
                                Close Applications Early
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ALL APPLICATION PERIODS CARD -->
            <div class="applications-card">
                <div class="card-header">
                    <div class="card-title-group">
                        <h3 class="card-title">All Application Periods</h3>
                        <span v-if="schoolYears.meta" class="meta-info">
                            {{ schoolYears.meta.from }}-{{ schoolYears.meta.to }} of {{ schoolYears.meta.total }}
                        </span>
                    </div>
                </div>
                
                <div class="card-content">
                  <div class="horizontal-scroll">
                      <table class="compact-table">
                        <thead>
                            <tr>
                                <th>School Year</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Applications</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="period in editablePeriods" :key="period.id">
                                <td class="applicant-name">{{ period.school_year }}</td>
                                <td>
                                   <input
                                        type="date"
                                        :value="period.application_deadline"
                                        @change="updateDeadline(period, $event)"
                                        class="date-input"
                                        :disabled="!period.is_active"
                                    />
                                </td>
                                <td>
                                    <span :class="`status-badge status-${period.is_active ? 'accepted' : 'for-review'}`">
                                        {{ period.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ period.applications_count }}</td>
                                <td class="actions-cell">
                                    <div class="actions">
                                        <button
                                            v-if="!period.is_active"
                                            @click="activateApplicationPeriod(period)"
                                            class="btn-secondary mini"
                                        >
                                            Activate
                                        </button>
                                        <button
                                            v-if="!period.is_active && period.applications_count === 0"
                                            @click="deleteApplicationPeriod(period)"
                                            class="btn-danger mini"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="editablePeriods.length === 0">
                                <td colspan="5" class="empty-state">
                                    <div class="empty-icon">📅</div>
                                    <div class="empty-text">No application periods found</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                  </div>
                   
                    
                    <!-- Pagination -->
                    <div v-if="schoolYears.links?.length > 3" class="pagination-section">
                        <Pagination 
                            :links="schoolYears.links" 
                            :from="schoolYears.meta?.from"
                            :to="schoolYears.meta?.to"
                            :total="schoolYears.meta?.total"
                            :current-page="schoolYears.meta?.current_page"
                            :last-page="schoolYears.meta?.last_page"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AdminIndex>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import AdminIndex from '../AdminIndex.vue';
import Pagination from './Pagination.vue';

interface ApplicationPeriod {
    id: number;
    school_year: string;
    application_deadline: string;
    is_active: boolean;
    applications_count: number;
    is_open?: boolean;
}

interface PaginatedSchoolYears {
    data: ApplicationPeriod[];
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
    meta: {
        current_page: number;
        from: number;
        to: number;
        total: number;
        last_page: number;
        per_page: number;
        path: string;
    };
}

// Props from Laravel
const props = defineProps<{
    schoolYears: PaginatedSchoolYears;
    currentActive: ApplicationPeriod | null;
}>();

// Reactive copy where v-model works
const editablePeriods = ref<ApplicationPeriod[]>([]);

onMounted(() => {
    editablePeriods.value = props.schoolYears.data ? [...props.schoolYears.data] : [];
});

const newApplicationPeriod = ref({
    school_year: '',
    application_deadline: ''
});

function createApplicationPeriod() {
    // Validate year format
    const yearRegex = /^\d{4}-\d{4}$/;
    if (!yearRegex.test(newApplicationPeriod.value.school_year)) {
        alert('Please use format: YYYY-YYYY (e.g., 2025-2026)');
        return;
    }

    const years = newApplicationPeriod.value.school_year.split('-');
    if (years[1] <= years[0]) {
        alert('End year must be greater than start year');
        return;
    }

    // Validate date is in the future
    const selectedDate = new Date(newApplicationPeriod.value.application_deadline);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate < today) {
        alert('Application deadline must be in the future');
        return;
    }

    // Submit form to Laravel using Inertia
    Inertia.post('/admin/school-years', newApplicationPeriod.value, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            newApplicationPeriod.value = {
                school_year: '',
                application_deadline: ''
            };
            alert('Application period created successfully!');
        },
        onError: (errors) => {
            console.log('Create errors:', errors);
            if (errors.school_year) {
                alert(errors.school_year);
            } else if (errors.application_deadline) {
                alert(errors.application_deadline);
            } else {
                alert("Something went wrong, please check your input.");
            }
        }
    });
}

function updateDeadline(period: ApplicationPeriod, event: Event) {
    const target = event.target as HTMLInputElement;
    const newDate = target.value;
    
    const selectedDate = new Date(newDate);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate < today) {
        alert('Application deadline must be in the future');
        target.value = period.application_deadline;
        return;
    }

    const periodIndex = editablePeriods.value.findIndex(p => p.id === period.id);
    if (periodIndex !== -1) {
        editablePeriods.value[periodIndex].application_deadline = newDate;
    }

    Inertia.put(`/admin/school-years/${period.id}`, {
        application_deadline: newDate
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            console.log(`Deadline updated successfully for ${period.school_year}`);
        },
        onError: (errors) => {
            if (periodIndex !== -1) {
                editablePeriods.value[periodIndex].application_deadline = period.application_deadline;
            }
            alert(errors.application_deadline || 'Something went wrong updating the deadline');
        }
    });
}

function activateApplicationPeriod(period: ApplicationPeriod) {
    if (confirm(`Activate application period for ${period.school_year}? This will deactivate any currently active period.`)) {
        Inertia.post(`/admin/school-years/${period.id}/activate`, {}, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                alert(`Application period for ${period.school_year} is now active!`);
            },
            onError: (errors) => {
                alert(errors.message || 'Failed to activate application period');
            }
        });
    }
}

function closeApplications(period: ApplicationPeriod) {
    if (confirm(`Close applications for ${period.school_year}? This cannot be undone.`)) {
        Inertia.post(`/admin/school-years/${period.id}/close`, {}, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                alert(`Applications for ${period.school_year} have been closed.`);
            },
            onError: (errors) => {
                alert(errors.message || 'Failed to close applications');
            }
        });
    }
}

function deleteApplicationPeriod(period: ApplicationPeriod) {
    if (confirm(`Are you sure you want to delete the ${period.school_year} application period? This action cannot be undone.`)) {
        Inertia.delete(`/admin/school-years/${period.id}`, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                editablePeriods.value = editablePeriods.value.filter(p => p.id !== period.id);
                alert('Application period deleted successfully!');
            },
            onError: (errors) => {
                alert(errors.message || 'Failed to delete application period');
            }
        });
    }
}

function formatDate(dateString: string) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}
</script>

<style scoped>
.applications-container {
  padding: var(--space-8);
  background: var(--color-light-bg);
  min-height: 100vh;
  font-family: var(--font-alt);
}

.page-header {
  margin-bottom: var(--space-8);
}

.page-title {
  font-size: var(--font-size-h4);
  font-weight: 600;
  color: var(--color-primary);
  text-transform: uppercase;
  margin: 0;
  font-family: var(--font-sans);
}

.applications-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-6);
  margin: 0 auto 24px;
}

@media (max-width: 1200px) {
  .applications-grid {
    grid-template-columns: 1fr;
    gap: var(--space-8);
  }
}

.applications-card {
  background: var(--color-background);
  border-radius: var(--radius-l);
  overflow: hidden;
  box-shadow: var(--elevation-2);
  border: 1px solid var(--color-neutral);
  border-left: 4px solid var(--color-primary);
  height: fit-content;
  display: flex;
  flex-direction: column;
}

.card-header {
  padding: var(--space-5) var(--space-6);
  border-bottom: 1px solid var(--color-neutral);
  background: var(--color-background);
}

.card-title-group {
  display: flex;
  flex-direction: column;
  gap: var(--space-3);
}

.card-title {
  font-size: var(--font-size-h6);
  font-weight: 600;
  color: var(--color-primary);
  margin: 0;
  text-transform: uppercase;
  font-family: var(--font-sans);
  display: flex;
  align-items: center;
  gap: var(--space-3);
}

.meta-info {
  font-size: var(--font-size-p);
  color: var(--color-neutral-dark);
  font-weight: 500;
  font-family: var(--font-alt);
}

.card-content {
  padding: var(--space-6);
  flex: 1;
  display: flex;
  flex-direction: column;
}

.period-form {
  display: flex;
  flex-direction: column;
  gap: var(--space-4);
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
}

.form-label {
  font-size: var(--font-size-p);
  font-weight: 600;
  color: var(--color-text-light);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-family: var(--font-sans);
}

.form-input {
  padding: var(--space-3);
  border: 1px solid var(--color-neutral);
  border-radius: var(--radius-m);
  font-size: var(--font-size-p);
  background: var(--color-background);
  color: var(--color-foreground);
  transition: all 0.2s ease;
  font-family: var(--font-alt);
}

.form-input:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
}

.form-helper {
  font-size: var(--font-size-p);
  color: var(--color-neutral-dark);
  font-family: var(--font-alt);
}

.btn-primary {
  padding: var(--space-3) var(--space-5);
  background: var(--color-primary);
  color: var(--color-background);
  border: none;
  border-radius: var(--radius-m);
  font-size: var(--font-size-p);
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-family: var(--font-sans);
  align-self: flex-start;
}

.btn-primary:hover {
  background: rgba(13, 110, 253, 0.8);
  transform: translateY(-1px);
}

.active-period-info {
  display: flex;
  flex-direction: column;
  gap: var(--space-4);
  margin-bottom: var(--space-6);
}

.info-group {
  display: flex;
  align-items: center;
  gap: var(--space-4);
}

.info-label {
  font-size: var(--font-size-p);
  font-weight: 600;
  color: var(--color-text-light);
  min-width: 100px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-family: var(--font-sans);
}

.info-value {
  font-size: var(--font-size-p);
  color: var(--color-foreground);
  font-family: var(--font-alt);
}

.card-actions {
  margin-top: auto;
  padding-top: var(--space-4);
  border-top: 1px solid var(--color-neutral);
}

.btn-warning {
  padding: var(--space-2) var(--space-4);
  background: var(--color-secondary-20);
  color: var(--color-secondary);
  border: 1px solid var(--color-secondary);
  border-radius: var(--radius-m);
  font-size: var(--font-size-p);
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-family: var(--font-sans);
}

.btn-warning:hover {
  background: var(--color-secondary);
  color: var(--color-background);
}

.compact-table {
  width: 100%;
  border-collapse: collapse;
  font-family: var(--font-alt);
  font-size: var(--font-size-p);
  margin-bottom: var(--space-6);
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
  letter-spacing: 0.5px;
  font-size: var(--font-size-p);
  font-family: var(--font-sans);
  border-bottom: 2px solid var(--color-primary-light);
}

.compact-table td {
  padding: var(--space-3) var(--space-4);
  border-bottom: 1px solid var(--color-neutral);
  vertical-align: middle;
}

.compact-table tr:last-child td {
  border-bottom: none;
}

.compact-table tr:hover {
  background-color: rgba(13, 110, 253, 0.05);
  transition: background-color 0.2s ease;
}

.compact-table tr:hover td {
  border-color: var(--color-primary-light);
}
.horizontal-scroll{
  overflow-x: auto;
  overflow-y: hidden;
  width: 100%;
  white-space: nowrap;
}
.applicant-name {
  font-weight: 500;
  color: var(--color-foreground);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 150px;
}

.date-input {
  padding: var(--space-2) var(--space-3);
  border: 1px solid var(--color-neutral);
  border-radius: var(--radius-m);
  font-size: var(--font-size-p);
  background: var(--color-background);
  color: var(--color-foreground);
  transition: all 0.2s ease;
  font-family: var(--font-alt);
}

.date-input:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
}

.date-input:disabled {
  background: var(--color-light-bg);
  color: var(--color-neutral-dark);
  cursor: not-allowed;
}

.actions-cell {
  min-width: 120px;
}

.actions {
  display: flex;
  gap: var(--space-2);
  flex-wrap: wrap;
}

.btn-secondary.mini,
.btn-danger.mini {
  padding: var(--space-1) var(--space-3);
  border-radius: var(--radius-s);
  font-size: var(--font-size-p);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-family: var(--font-sans);
  white-space: nowrap;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-secondary.mini {
  background: var(--color-primary);
  color: var(--color-background);
}

.btn-secondary.mini:hover {
    transform: translateY(1px);
}

.btn-danger.mini {
  background: var(--color-rejected);
  color: var(--color-rejected-text);
}

.btn-danger.mini:hover {
  background: var(--color-rejected-text);
  color: var(--color-background);
}

.status-badge {
  display: inline-block;
  padding: 3px 10px;
  border-radius: var(--radius-s);
  font-size: var(--font-size-p);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-family: var(--font-sans);
  white-space: nowrap;
}

.status-accepted {
  background: var(--color-bright-green-25);
  color: var(--color-primary);
}

.status-rejected {
  background: var(--color-rejected);
  color: var(--color-rejected-text);
}

.status-for-review {
  background: var(--color-for-review);
  color: var(--color-for-review-text);
}

.empty-state {
  text-align: center;
  padding: var(--space-8) var(--space-4);
  color: var(--color-neutral);
}

.empty-icon {
  font-size: var(--font-size-h5);
  margin-bottom: var(--space-3);
  opacity: 0.5;
}

.empty-text {
  font-size: var(--font-size-p);
  font-style: italic;
  font-family: var(--font-alt);
}

.pagination-section {
  margin-top: auto;
  padding-top: var(--space-6);
  border-top: 1px solid var(--color-neutral);
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .applications-container {
    padding: var(--space-4);
  }
  
  .applications-grid {
    gap: var(--space-4);
  }
  
  .applications-card {
    border-radius: var(--radius-m);
  }
  
  .card-header {
    padding: var(--space-4);
  }
  
  .card-content {
    padding: var(--space-4);
  }
  
  .card-title {
    font-size: var(--font-size-p);
  }
  
  .compact-table {
    font-size: 1.3rem;
  }
  
  .compact-table th,
  .compact-table td {
    padding: var(--space-2) var(--space-3);
  }
  
  .info-group {
    flex-direction: column;
    align-items: flex-start;
    gap: var(--space-1);
  }
  
  .info-label {
    min-width: auto;
  }
  
  .actions {
    flex-direction: column;
  }
  
  .btn-secondary.mini,
  .btn-danger.mini {
    width: 100%;
  }
  
  .applicant-name {
    max-width: 120px;
  }
}
</style>
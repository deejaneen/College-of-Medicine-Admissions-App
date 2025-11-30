<template>
    <AdminIndex>
        <div class="application-periods-container">
            <div class="page-title">Application Periods</div>
            <p class="page-subtitle">Manage application deadlines and school years</p>

            <!-- Create New Application Period -->
            <div class="card">
                <h3>Open New Application Period</h3>
                <form @submit.prevent="createApplicationPeriod" class="form">
                    <div class="form-group">
                        <label for="school_year">School Year Range:</label>
                        <input
                            type="text"
                            id="school_year"
                            v-model="newApplicationPeriod.school_year"
                            placeholder="e.g., 2025-2026"
                            required
                            class="form-input"
                        >
                        <small class="help-text">Format: YYYY-YYYY (e.g., 2025-2026)</small>
                    </div>
                    <div class="form-group">
                        <label for="application_deadline">Application Deadline:</label>
                        <input
                            type="date"
                            id="application_deadline"
                            v-model="newApplicationPeriod.application_deadline"
                            required
                            class="form-input"
                        >
                        <small class="help-text">Forms will automatically close after this date</small>
                    </div>
                    <button type="submit" class="btn-primary">Open Application Period</button>
                </form>
            </div>

            <!-- Current Active Application Period -->
            <div class="card" v-if="currentActive">
                <h3>Current Active Application Period</h3>
                <div class="active-period">
                    <div class="period-info">
                        <strong>School Year: {{ currentActive.school_year }}</strong>
                        <span>Deadline: {{ formatDate(currentActive.application_deadline) }}</span>
                        <span :class="`status status-${currentActive.is_open ? 'accepted' : 'rejected'}`">
                            {{ currentActive.is_open ? 'Open' : 'Closed' }}
                        </span>
                    </div>
                    <div class="period-actions">
                        <button 
                            @click="closeApplications(currentActive)" 
                            class="btn-warning"
                            v-if="currentActive.is_open"
                        >
                            Close Applications Early
                        </button>
                    </div>
                </div>
            </div>

            <!-- All Application Periods -->
            <div class="card">
                <h3>All Application Periods</h3>
                <table class="table-elevated-minimal">
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
                        <tr v-for="period in schoolYears" :key="period.id">
                            <td>{{ period.school_year }}</td>
                            <td>
                               <input
                                    type="date"
                                    v-model="period.application_deadline"
                                    @change="updateDeadline(period, $event)"
                                    class="date-input"
                                    :disabled="!period.is_active"
                                />

                            </td>
                            <td>
                                <span :class="`status status-${period.is_active ? 'accepted' : 'for-review'}`">
                                    {{ period.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ period.applications_count }}</td>
                            <td class="actions">
                                <button
                                    v-if="!period.is_active"
                                    @click="activateApplicationPeriod(period)"
                                    class="btn-secondary"
                                >
                                    Activate
                                </button>
                                <button
                                    v-if="!period.is_active && period.applications_count === 0"
                                    @click="deleteApplicationPeriod(period)"
                                    class="btn-danger"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminIndex>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import AdminIndex from '../AdminIndex.vue';

interface ApplicationPeriod {
    id: number;
    school_year: string;
    application_deadline: string;
    is_active: boolean;
    applications_count: number;
    is_open?: boolean;
}

// Props from Laravel
const props = defineProps<{
    schoolYears: ApplicationPeriod[];
    currentActive: ApplicationPeriod | null;
}>();

// Reactive copy where v-model works
const editablePeriods = ref<ApplicationPeriod[]>([]);

onMounted(() => {
    editablePeriods.value = props.schoolYears.map(p => ({ ...p }));
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

    // Submit form to Laravel using Inertia
    Inertia.post('/admin/school-years', newApplicationPeriod.value, {
        onSuccess: () => {
            // reset form
            newApplicationPeriod.value = {
                school_year: '',
                application_deadline: ''
            };
        },
        onError: (errors) => {
            console.log(errors);
            alert("Something went wrong, check input.");
        }
    });
}


function updateDeadline(period: ApplicationPeriod, event: Event) {
    const target = event.target as HTMLInputElement;

    Inertia.put(`/admin/school-years/${period.id}`, {
        application_deadline: target.value
    }, {
        onSuccess: () => {
            alert(`Deadline updated successfully for ${period.school_year}`);
        },
        onError: (errors) => {
            alert(errors.application_deadline || 'Something went wrong');
        }
    });
}



function activateApplicationPeriod(period: ApplicationPeriod) {
    Inertia.post(`/admin/school-years/${period.id}/activate`, {});
}

function closeApplications(period: ApplicationPeriod) {
    if (confirm(`Close applications for ${period.school_year}? This cannot be undone.`)) {
        Inertia.post(`/admin/school-years/${period.id}/close`, {});
    }
}

function deleteApplicationPeriod(period: ApplicationPeriod) {
    if (confirm('Are you sure you want to delete this application period?')) {
        Inertia.delete(`/admin/school-years/${period.id}`);
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
.application-periods-container {
    padding: var(--space-8);
    background: var(--color-light-bg);
}

.page-title {
    font-size: var(--font-size-h4);
    font-weight: 600;
    color: var(--color-primary);
    text-transform: uppercase;
    margin-bottom: var(--space-2);
}

.page-subtitle {
    color: var(--color-text-light);
    margin-bottom: var(--space-6);
    font-size: var(--font-size-p);
}

.card {
    background: var(--color-background);
    padding: var(--space-6);
    border-radius: var(--radius-l);
    box-shadow: var(--elevation-2);
    margin-bottom: var(--space-6);

}

.card h3 {
    color: var(--color-primary);
    margin-bottom: var(--space-4);
    font-size: var(--font-size-h5);
}

.form {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: var(--space-4);
    align-items: end;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.help-text {
    color: var(--color-text-light);
    font-size: var(--font-size-p);
    margin-top: var(--space-1);
}

.form-input, .date-input {
    padding: var(--space-3);
    border: 1px solid var(--color-neutral);
    border-radius: var(--radius-m);
    font-size: var(--font-size-p);
}

.active-period {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.period-info {
    display: flex;
    gap: var(--space-6);
    align-items: center;
     font-size: var(--font-size-p);
}

.period-actions {
    display: flex;
    gap: var(--space-4);
    font-size: var(--font-size-p);
}

.actions {
    display: flex;
    gap: var(--space-2);
    flex-wrap: wrap;
}

.btn-primary, .btn-secondary, .btn-danger, .btn-warning {
    padding: var(--space-3) var(--space-5);
    border: none;
    border-radius: var(--radius-m);
    cursor: pointer;
    font-size: var(--font-size-p);
    white-space: nowrap;
    transition: all 0.2s ease;
}

.btn-primary {
    background: var(--color-primary-80);
    color: white;
}

.btn-primary:hover {
    background: var(--color-primary);
}

.btn-secondary {
    background: var(--color-bright-green-25);
    color: var(--color-primary);
    border: 1px solid var(--color-bright-green);
}

.btn-secondary:hover {
    background: var(--color-bright-green);
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.btn-warning {
    background: var(--color-secondary-80);
    color: #212529;
}

.btn-warning:hover {
    background: var(--color-secondary);
}

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

.table-elevated-minimal th {
  background-color: var(--color-light-bg);
  color: var(--color-primary);
  font-weight: 600;
  text-transform: uppercase;
  text-align: left;
  padding: var(--space-4);
  border-bottom: 2px solid var(--color-primary-light);
}

.table-elevated-minimal td {
  padding: var(--space-5);
}


.table-elevated-minimal tr {
  border-bottom: 1px solid var(--color-neutral);
}
.table-elevated-minimal tr:last-child td {
  border-bottom: none;
}

.table-elevated-minimal tr:nth-child(even) {
  background-color: var(--color-light-bg-40);
}

.table-elevated-minimal tr:hover {
  background-color: var(--color-bright-green-25);
  transition: background-color 0.2s ease;
}

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

/* Responsive */
@media (max-width: 768px) {
    .form {
        grid-template-columns: 1fr;
    }
    
    .active-period {
        flex-direction: column;
        gap: var(--space-4);
        align-items: start;
    }
    
    .period-info {
        flex-direction: column;
        gap: var(--space-2);
        align-items: start;
    }
    
    .actions {
        flex-direction: column;
    }
}
</style>
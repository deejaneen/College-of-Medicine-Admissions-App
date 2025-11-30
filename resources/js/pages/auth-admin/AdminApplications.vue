<template>
  <AdminIndex>
    <div class="applications-container">
        <!-- Debug Information -->
        <div v-if="!hasData" class="debug-info" style="background: #ffebee; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
            <strong>DEBUG: No application data received</strong>
            <p>Check browser console for details</p>
        </div>
        
        <div class="page-title">Applications for Active Year ({{ activeApplications.length }})</div>
        <table class="table-elevated-minimal">
            <thead>
                <tr>
                    <th>School Year</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="app in activeApplications" :key="app.id" @click="goToApplication(app.id)">
                    <td>{{ app.school_year?.school_year || 'N/A' }}</td>
                    <td>
                        {{ app.lastName || app.last_name || '' }}, 
                        {{ app.firstName || app.first_name || '' }} 
                        {{ app.middleName || app.middle_name || '' }}
                    </td>
                    <td>{{ app.emailAddress || app.email_address || '' }}</td>
                    <td>
                        <span :class="`status status-${getStatusClass(app.applicationStatus || app.application_status)}`">
                            {{ app.applicationStatus || app.application_status || 'For Review' }}
                        </span>
                    </td>
                </tr>
                <tr v-if="activeApplications.length === 0">
                    <td colspan="4" class="text-center">No applications for the active year</td>
                </tr>
            </tbody>
        </table>

        <div class="page-title" style="margin-top:2rem;">Applications for Archived Years ({{ archivedApplications.length }})</div>
        <table class="table-elevated-minimal">
            <thead>
                <tr>
                    <th>School Year</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="app in archivedApplications" :key="app.id" @click="goToApplication(app.id)">
                    <td>{{ app.school_year?.school_year || 'N/A' }}</td>
                    <td>
                        {{ app.lastName || app.last_name || '' }}, 
                        {{ app.firstName || app.first_name || '' }} 
                        {{ app.middleName || app.middle_name || '' }}
                    </td>
                    <td>{{ app.emailAddress || app.email_address || '' }}</td>
                    <td>
                        <span :class="`status status-${getStatusClass(app.applicationStatus || app.application_status)}`">
                            {{ app.applicationStatus || app.application_status || 'For Review' }}
                        </span>
                    </td>
                </tr>
                <tr v-if="archivedApplications.length === 0">
                    <td colspan="4" class="text-center">No applications for archived years</td>
                </tr>
            </tbody>
        </table>
    </div>
  </AdminIndex>
</template>

<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import AdminIndex from '../AdminIndex.vue';

const hasData = ref(false);

// Debugging - log the received data
onMounted(() => {
    console.log('=== APPLICATIONS DATA DEBUG ===');
    console.log('Props received:', props);
    console.log('Applications array:', props.applications);
    console.log('Applications type:', typeof props.applications);
    console.log('Is array?:', Array.isArray(props.applications));
    
    if (props.applications && Array.isArray(props.applications)) {
        console.log('Applications count:', props.applications.length);
        hasData.value = true;
        
        // Check first application structure
        if (props.applications.length > 0) {
            console.log('First application:', props.applications[0]);
            console.log('First app keys:', Object.keys(props.applications[0]));
            console.log('First app school_year:', props.applications[0].school_year);
            
            // Check specific field names
            console.log('Available name fields:', {
                firstName: props.applications[0].firstName,
                first_name: props.applications[0].first_name,
                lastName: props.applications[0].lastName,
                last_name: props.applications[0].last_name,
                emailAddress: props.applications[0].emailAddress,
                email_address: props.applications[0].email_address,
                applicationStatus: props.applications[0].applicationStatus,
                application_status: props.applications[0].application_status
            });
        }
    } else {
        console.error('Applications is not an array or is undefined');
        hasData.value = false;
    }
});

function goToApplication(id: number) {
    Inertia.get(`/admin/applications/${id}`);
}

// Fixed getStatusClass with null check
function getStatusClass(status: string | undefined | null): string {
  if (!status) return 'for-review';
  
  const statusLower = status.toLowerCase();
  if (statusLower.includes('accepted')) return 'accepted';
  if (statusLower.includes('rejected')) return 'rejected';
  return 'for-review';
}

// Updated interface to handle both naming conventions
interface Application {
    id: number;
    // Support both camelCase and snake_case
    firstName?: string;
    first_name?: string;
    middleName?: string;
    middle_name?: string;
    lastName?: string;
    last_name?: string;
    emailAddress?: string;
    email_address?: string;
    applicationStatus?: string;
    application_status?: string;
    school_year: {
        id: number;
        school_year: string;
        is_active: boolean;
    };
}

const props = defineProps<{ applications: Application[] }>();

// Helper function to get field values safely

// Split applications by active school year with safety checks
const activeApplications = computed(() => {
    if (!props.applications || !Array.isArray(props.applications)) {
        return [];
    }
    return props.applications.filter(a => a.school_year?.is_active);
});

const archivedApplications = computed(() => {
    if (!props.applications || !Array.isArray(props.applications)) {
        return [];
    }
    return props.applications.filter(a => !a.school_year?.is_active);
});
</script>
<style scoped>
.applications-container{
    padding: var(--space-8);
    background: var(--color-light-bg);
}
.page-title{
    font-size: var(--font-size-h4);
    font-weight: 600;
    color: var(--color-primary);
    text-transform: uppercase;
    margin-bottom: var(--space-6);
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
  background-color: var(--color-rejected);
  color: var(--color-rejected-text);
}

.status-for-review {
  background-color: var(--color-for-review);
  color: var(--color-for-review-text);
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
}
</style>
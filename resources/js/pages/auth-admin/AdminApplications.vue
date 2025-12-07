<template>
  <AdminIndex>
    <div class="applications-container">
        <div class="page-header">
            <h1 class="page-title">Applications</h1>
        </div>
      <!-- TWO COLUMN LAYOUT -->
      <div class="applications-grid">
        <!-- ACTIVE APPLICATIONS CARD -->
        <div class="applications-card">
          <div class="card-header">
            <div class="card-title-group">
              <h3 class="card-title">
                Applications for Active Year
                <span v-if="activeYearName" class="year-name">{{ activeYearName }}</span>
              </h3>
              <span v-if="activeApplications.meta" class="meta-info">
                {{ activeApplications.meta.from }}-{{ activeApplications.meta.to }} of {{ activeApplications.meta.total }}
              </span>
            </div>
        </div>
       
        <div class="card-content">
            <table class="compact-table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="app in activeApplications.data" :key="app.id" @click="goToApplication(app.id)">
                  <td class="applicant-name">
                    {{ app.lastName || app.last_name || '' }}, 
                    {{ app.firstName || app.first_name || '' }}
                      {{ app.middleName || app.middle_name || '' }}
                    <!-- <span class="name-helper">
                    </span> -->
                  </td>
                  <td class="applicant-email">{{ app.emailAddress || app.email_address || '' }}</td>
                  <td>
                    <span :class="`status-badge status-${getStatusClass(app.applicationStatus || app.application_status)}`">
                      {{ app.applicationStatus || app.application_status || 'For Review' }}
                    </span>
                  </td>
                </tr>
                <tr v-if="activeApplications.data.length === 0">
                  <td colspan="3" class="empty-state">
                    <div class="empty-icon">📋</div>
                    <div class="empty-text">No active applications</div>
                  </td>
                </tr>
              </tbody>
            </table>
            
            <!-- ACTIVE PAGINATION -->
            <div v-if="activeApplications.links?.length > 3" class="pagination-section">
              <Pagination 
                :links="activeApplications.links" 
                :from="activeApplications.meta?.from"
                :to="activeApplications.meta?.to"
                :total="activeApplications.meta?.total"
                :current-page="activeApplications.meta?.current_page"
                :last-page="activeApplications.meta?.last_page"
              />
            </div>
          </div>
        </div>

        <!-- ARCHIVED APPLICATIONS CARD -->
        <div class="applications-card">
          <div class="card-header">
            <div class="card-title-group">
              <h3 class="card-title">Archived Applications</h3>
              <div class="filter-controls">
                <select 
                  v-model="archivedYearFilter" 
                  @change="filterArchivedApplications"
                  class="year-filter-select mini"
                >
                  <option value="all">All Archived Years</option>
                  <option 
                    v-for="year in archivedSchoolYears" 
                    :key="year.id" 
                    :value="year.id"
                  >
                    {{ year.school_year }}
                  </option>
                </select>
                <span v-if="filteredArchivedApplications.meta" class="meta-info">
                  {{ filteredArchivedApplications.meta.from }}-{{ filteredArchivedApplications.meta.to }} of {{ filteredArchivedApplications.meta.total }}
                </span>
              </div>
            </div>
          </div>
          
          <div class="card-content">
            <table class="compact-table">
              <thead>
                <tr>
                  <th>Year</th>
                  <th>Name</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="app in filteredArchivedApplications.data" :key="app.id" @click="goToApplication(app.id)">
                  <td class="year-cell">
                    <span class="year-badge">{{ app.school_year?.school_year || 'N/A' }}</span>
                  </td>
                  <td class="applicant-name">
                    {{ app.lastName || app.last_name || '' }}, 
                    {{ app.firstName || app.first_name || '' }}
                  </td>
                  <td>
                    <span :class="`status-badge status-${getStatusClass(app.applicationStatus || app.application_status)}`">
                      {{ app.applicationStatus || app.application_status || 'For Review' }}
                    </span>
                  </td>
                </tr>
                <tr v-if="filteredArchivedApplications.data.length === 0">
                  <td colspan="3" class="empty-state">
                    <div class="empty-icon">📁</div>
                    <div v-if="archivedYearFilter === 'all'" class="empty-text">No archived applications</div>
                    <div v-else class="empty-text">No applications for selected year</div>
                  </td>
                </tr>
              </tbody>
            </table>
            
            <!-- ARCHIVED PAGINATION -->
            <div v-if="filteredArchivedApplications.links?.length > 3" class="pagination-section">
              <Pagination 
                :links="filteredArchivedApplications.links" 
                :from="filteredArchivedApplications.meta?.from"
                :to="filteredArchivedApplications.meta?.to"
                :total="filteredArchivedApplications.meta?.total"
                :current-page="filteredArchivedApplications.meta?.current_page"
                :last-page="filteredArchivedApplications.meta?.last_page"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminIndex>
</template>

<script lang="ts" setup>
import { Inertia } from '@inertiajs/inertia';
import { computed, ref } from 'vue';
import AdminIndex from '../AdminIndex.vue';
import Pagination from './Pagination.vue';

// Define interfaces
interface Application {
    id: number;
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

// Add this interface definition
interface PaginatedApplications {
    data: Application[];
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

// Then update your Props interface to use it
interface Props {
    activeApplications: PaginatedApplications;
    archivedApplications: PaginatedApplications;
    activeYearId?: number;
    allSchoolYears?: any[];
    activeYear?: any;
    currentRoute?: string;
}

const props = defineProps<Props>();

// Filter state for archived applications
const archivedYearFilter = ref<string>('all');

// Get archived school years (non-active years) - UPDATED WITH DEBUG
const archivedSchoolYears = computed(() => {
  console.log('DEBUG - allSchoolYears:', props.allSchoolYears);
  console.log('DEBUG - activeYear:', props.activeYear);
  
  if (!props.allSchoolYears || !Array.isArray(props.allSchoolYears)) {
    console.log('DEBUG - allSchoolYears is empty or not an array');
    return [];
  }
  
  console.log('DEBUG - Total years in allSchoolYears:', props.allSchoolYears.length);
  
  const filtered = props.allSchoolYears.filter((year: any) => {
    console.log(`DEBUG - Checking year ${year.id} (${year.school_year}): is_active=${year.is_active}, activeYear.id=${props.activeYear?.id}`);
    
    // Exclude the active year
    if (props.activeYear && year.id === props.activeYear.id) {
      console.log(`DEBUG - Excluding active year: ${year.school_year}`);
      return false;
    }
    return true;
  });
  
  console.log('DEBUG - Archived years after filter:', filtered.length);
  console.log('DEBUG - Archived years:', filtered);
  
  return filtered.sort((a: any, b: any) => 
    b.school_year.localeCompare(a.school_year)
  );
});

// Filter archived applications by selected year
const filteredArchivedApplications = computed(() => {
  if (archivedYearFilter.value === 'all') {
    return props.archivedApplications;
  }
  
  const yearId = parseInt(archivedYearFilter.value);
  const filteredData = props.archivedApplications.data.filter((app: any) => 
    app.school_year?.id === yearId
  );
  
  // Calculate filtered metadata
  const total = filteredData.length;
  const perPage = props.archivedApplications.meta?.per_page || 10;
  const currentPage = 1;
  const lastPage = Math.ceil(total / perPage);
  
  return {
    data: filteredData,
    links: [], // Simplified for client-side filtering
    meta: {
      current_page: currentPage,
      from: total > 0 ? 1 : 0,
      to: Math.min(total, perPage),
      total: total,
      last_page: lastPage,
      per_page: perPage,
      path: props.archivedApplications.meta?.path || ''
    }
  };
});

function goToApplication(id: number) {
    Inertia.get(`/admin/applications/${id}`);
}

function getStatusClass(status: string | undefined | null): string {
  if (!status) return 'for-review';
  const statusLower = status.toLowerCase();
  if (statusLower.includes('accepted')) return 'accepted';
  if (statusLower.includes('rejected')) return 'rejected';
  if (statusLower.includes('waitlist')) return 'waitlist';
  return 'for-review';
}

function filterArchivedApplications(): void {
  console.log('Filtering archived applications by year:', archivedYearFilter.value);
  
  // Use Inertia.reload with the filter parameter
  Inertia.reload({
    data: {
      archived_year_filter: archivedYearFilter.value,
      archived_page: 1 // Reset to first page when filtering
    },
    only: ['archivedApplications'], // Only reload archived applications
    preserveScroll: true,
    preserveState: true
  });
}

// Get active year name from first active application
const activeYearName = computed(() => {
  if (props.activeApplications.data.length > 0) {
    return props.activeApplications.data[0].school_year?.school_year || '';
  }
  return '';
});
</script>
<style scoped>
/* MOBILE FIRST STYLES */
.applications-container {
  padding: var(--space-4);
  background: var(--color-light-bg);
  min-height: 100vh;
  font-family: var(--font-alt);
  box-sizing: border-box;
}

.page-header {
  margin-bottom: var(--space-4);
}

.page-title {
  font-size: var(--font-size-h5);
  font-weight: 600;
  color: var(--color-primary);
  text-transform: uppercase;
  margin-bottom: var(--space-2);
  font-family: var(--font-sans);
}

.applications-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--space-4);
  max-width: 1600px;
  margin: 0 auto;
}

.applications-card {
  background: var(--color-background);
  border-radius: var(--radius-m);
  overflow: hidden;
  box-shadow: var(--elevation-1);
  border: 1px solid var(--color-neutral);
  border-left: 4px solid var(--color-primary);
  height: fit-content;
  display: flex;
  flex-direction: column;
  max-width: 100%;
}

.card-header {
  padding: var(--space-3);
  border-bottom: 1px solid var(--color-neutral);
  background: var(--color-background);
}

.card-title-group {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
}

.card-title {
  font-size: var(--font-size-p);
  font-weight: 600;
  color: var(--color-primary);
  margin: 0;
  text-transform: uppercase;
  font-family: var(--font-sans);
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: var(--space-2);
}

.filter-controls {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
}

.year-filter-select.mini {
  padding: var(--space-1) var(--space-2);
  border: 1px solid var(--color-neutral);
  border-radius: var(--radius-m);
  font-size: 1.2rem;
  background: var(--color-background);
  color: var(--color-foreground);
  cursor: pointer;
  transition: all 0.2s ease;
  font-family: var(--font-alt);
  width: 100%;
  max-width: 100%;
}

.year-name {
  font-size: 1.2rem;
  color: var(--color-foreground);
  text-transform: none;
  font-weight: 400;
  font-family: var(--font-alt);
}

.meta-info {
  font-size: 1.2rem;
  color: var(--color-neutral-dark);
  font-weight: 500;
  font-family: var(--font-alt);
}

.card-content {
  padding: var(--space-3);
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow-x: auto; 
}

.compact-table {
  width: 100%;
  border-collapse: collapse;
  font-family: var(--font-alt);
  font-size: 1.2rem;
  margin-bottom: var(--space-4);
  min-width: 100%;
}

.compact-table thead {
  background: var(--color-light-bg);
  position: sticky;
  top: 0;
  z-index: 1;
}

.compact-table th {
  padding: var(--space-2) var(--space-2);
  text-align: left;
  font-weight: 600;
  color: var(--color-text-light);
  text-transform: uppercase;
  letter-spacing: 0.3px;
  font-size: 1rem;
  font-family: var(--font-sans);
  border-bottom: 1px solid var(--color-primary-light);
  white-space: nowrap;
  min-width: 100px; /* MINIMUM COLUMN WIDTH */
}

.compact-table td {
  padding: var(--space-2) var(--space-2);
  border-bottom: 1px solid var(--color-neutral);
  vertical-align: middle;
  white-space: nowrap;
  min-width: 100px; /* MINIMUM COLUMN WIDTH */
}

.compact-table tr:last-child td {
  border-bottom: none;
}

.compact-table tr:hover {
  background-color: rgba(13, 110, 253, 0.05);
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.applicant-name {
  font-weight: 500;
  color: var(--color-foreground);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-size: 1.2rem;
  text-transform: uppercase;
}

.name-helper {
  display: block;
  font-size: 1rem;
  color: var(--color-neutral-dark);
  margin-top: 1px;
}

.applicant-email {
  color: var(--color-neutral-dark);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-size: 1.2rem;
}

.year-cell {
  min-width: 80px;
}

.year-badge {
  display: inline-block;
  padding: 1px 6px;
  background: var(--color-light-bg);
  color: var(--color-neutral-dark);
  border-radius: var(--radius-xs);
  font-size: 1rem;
  font-weight: 500;
  border: 1px solid var(--color-neutral);
}

.status-badge {
  display: inline-block;
  padding: 2px 8px;
  border-radius: var(--radius-xs);
  font-size: 1rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.3px;
  font-family: var(--font-sans);
  white-space: nowrap;
}

.status-accepted {
  background: var(--color-bright-green-25);
  color: var(--color-primary);
}

.status-waitlist {
  background: var(--color-secondary-20);
  color: var(--color-secondary);
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
  padding: var(--space-6) var(--space-2);
  color: var(--color-neutral);
}

.empty-icon {
  font-size: 2rem;
  margin-bottom: var(--space-2);
  opacity: 0.5;
}

.empty-text {
  font-size: 1.2rem;
  font-style: italic;
  font-family: var(--font-alt);
}

.pagination-section {
  margin-top: auto;
  padding-top: var(--space-4);
  border-top: 1px solid var(--color-neutral);
}

/* SCROLLBAR STYLING FOR TABLE */
.card-content::-webkit-scrollbar {
  height: 8px;
}

.card-content::-webkit-scrollbar-track {
  background: var(--color-light-bg);
  border-radius: var(--radius-s);
}

.card-content::-webkit-scrollbar-thumb {
  background: var(--color-neutral);
  border-radius: var(--radius-s);
}

.card-content::-webkit-scrollbar-thumb:hover {
  background: var(--color-primary);
}

/* SMALL TABLET (600px and up) */
@media (min-width: 600px) {
  .applications-container {
    padding: var(--space-5);
  }
  
  .page-header {
    margin-bottom: var(--space-5);
  }
  
  .applications-grid {
    gap: var(--space-5);
  }
  
  .card-header {
    padding: var(--space-4);
  }
  
  .card-content {
    padding: var(--space-4);
  }
  
  .card-title {
    font-size: var(--font-size-h6);
  }
  
  .compact-table {
    font-size: 1.4rem;
  }
  
  .compact-table th {
    font-size: 1rem;
    padding: var(--space-2) var(--space-3);
  }
  
  .compact-table td {
    padding: var(--space-2) var(--space-3);
  }
  
  .applicant-name {
    font-size: 1.4rem;
  }
  
  .applicant-email {
    font-size: 1.4rem;
  }
  
  .filter-controls {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }
  
  .year-filter-select.mini {
    width: auto;
    min-width: 180px;
    font-size: 1.4rem;
  }
  
  .year-name,
  .meta-info {
    font-size: var(--font-size-h6);
  }
  
  .status-badge {
    font-size: 1rem;
  }
}

/* TABLET (768px and up) */
@media (min-width: 768px) {
  .applications-container {
    padding: var(--space-6);
  }
  
  .page-header {
    margin-bottom: var(--space-6);
  }
  
  .page-title {
    font-size: var(--font-size-h4);
  }
  
  .applications-grid {
    gap: var(--space-6);
  }
  
  .applications-card {
    border-radius: var(--radius-l);
    box-shadow: var(--elevation-2);
  }
  
  .card-header {
    padding: var(--space-5);
  }
  
  .card-content {
    padding: var(--space-5);
  }
  
  .compact-table {
    font-size: 1.4rem;
    margin-bottom: var(--space-5);
  }
  
  .compact-table th {
    font-size: 1.2rem;
    padding: var(--space-3) var(--space-4);
  }
  
  .compact-table td {
    padding: var(--space-3) var(--space-4);
  }
  
  .applicant-name {
    font-size: 1.4rem;
  }
  
  .name-helper {
    font-size: 1.2rem;
  }
  
  .applicant-email {
    font-size: 1.4rem;
  }
  
  .year-badge {
    font-size: 1.2rem;
  }
  
  .status-badge {
    font-size: 1.2rem;
    padding: 3px 10px;
  }
  
  .empty-icon {
    font-size: 2.4rem;
  }
  
  .empty-text {
    font-size: 1.4rem;
  }
  
  .pagination-section {
    padding-top: var(--space-5);
  }
}

/* DESKTOP (1024px and up) - TWO COLUMNS */
@media (min-width: 1024px) {
  .applications-grid {
    grid-template-columns: 1fr 1fr;
    gap: var(--space-8);
  }
  
  .applications-container {
    padding: var(--space-8);
  }
  
  .page-header {
    margin-bottom: var(--space-8);
  }
  
  .applications-card {
    box-shadow: var(--elevation-3);
  }
  
  .card-content {
    overflow-x: visible; /* DISABLE SCROLLING ON DESKTOP */
  }
  
  .compact-table {
    min-width: auto; /* RESET MIN-WIDTH ON DESKTOP */
  }
}

/* EXTRA LARGE DESKTOP (1400px and up) */
@media (min-width: 1400px) {
  .applications-grid {
    max-width: 1800px;
  }
}

/* EXTRA SMALL SCREENS (less than 375px) */
@media (max-width: 374px) {
  .applications-container {
    padding: var(--space-2);
  }
  
  .page-title {
    font-size: 1.4rem;
  }
  
  .applications-grid {
    gap: var(--space-3);
  }
  
  .card-header {
    padding: var(--space-2);
  }
  
  .card-content {
    padding: var(--space-2);
  }
  
  .card-title {
    font-size: 1rem;
  }
  
  .compact-table {
    font-size: 1rem;
  }
  
  .compact-table th,
  .compact-table td {
    padding: var(--space-1) var(--space-1);
  }
  
  .applicant-name,
  .applicant-email {
    font-size: 1rem;
  }
  
  .status-badge {
    font-size: 0.9rem;
    padding: 1px 6px;
  }
  
  .empty-icon {
    font-size: 1.6rem;
  }
  
  .empty-text {
    font-size: 1rem;
  }
}

.applications-card,
.compact-table {
  max-width: 100%;
}

.applicant-name,
.applicant-email,
.year-badge,
.status-badge {
  overflow-wrap: break-word;
  word-wrap: break-word;
}
</style>
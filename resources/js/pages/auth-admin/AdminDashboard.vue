<template>
  <AdminIndex>
    <div class="dashboard-container">
          <div class="page-header">
                <h1 class="page-title">Dashboard</h1>
            </div>
      <!-- YEAR FILTER SECTION -->
      <div class="year-filter-section">
        <label for="schoolYearFilter" class="filter-label">Filter by School Year:</label>
        <select 
          id="schoolYearFilter" 
          v-model="selectedYear" 
          @change="updateDashboardByYear"
          class="year-filter-select"
        >
          <option value="all">All Years</option>
          <!-- First, show the active year if it exists -->
          <option v-if="activeYear" :value="activeYear.id" :selected="selectedYear === activeYear.id.toString()">
            {{ activeYear.school_year }} (Active Year)
          </option>
          <!-- Then show all other years -->
          <option 
            v-for="year in nonActiveSchoolYears" 
            :key="year.id" 
            :value="year.id"
          >
            {{ year.school_year }}
          </option>
        </select>
      </div>

      <!-- STATS CARDS -->
      <div class="stats-grid">
        <!-- Total Applications Card -->
        <div class="stat-card stat-card-primary">
          <div class="stat-icon">
            <span class="material-icons">people</span>
          </div>
          <div class="stat-content">
            <div class="stat-items">
              <div class="stat-value">{{ displayedStats.totalApplications }}</div>
            </div>
            <div class="stat-items">
              <div class="stat-label">Total Applications</div>
              <div class="stat-change">All school years</div>
            </div>
          </div>
        </div>

        <!-- Accepted Card -->
        <div class="stat-card stat-card-success">
          <div class="stat-icon">
            <span class="material-icons">check_circle</span>
          </div>
          <div class="stat-content">
            <div class="stat-items">
              <div class="stat-value">{{ displayedStats.acceptedApplications }}</div>
            </div>
            <div class="stat-items">
              <div class="stat-label">Accepted</div>
              <div class="stat-change" v-if="displayedStats.acceptanceRate > 0">
                {{ displayedStats.acceptanceRate }}% acceptance rate
              </div>
            </div>
          </div>
        </div>

        <!-- Pending Review Card -->
        <div class="stat-card stat-card-warning">
          <div class="stat-icon">
            <span class="material-icons">error</span>
          </div>
          <div class="stat-content">
            <div class="stat-items">
              <div class="stat-value">{{ displayedStats.pendingApplications }}</div>
            </div>
            <div class="stat-items">
              <div class="stat-label">Pending Review</div>
              <div class="stat-change">Need attention</div>
            </div>
          </div>
        </div>

        <!-- Waitlist Card -->
        <div class="stat-card stat-card-info">
          <div class="stat-icon">
            <span class="material-icons">access_time</span>
          </div>
          <div class="stat-content">
            <div class="stat-items">
              <div class="stat-value">{{ displayedStats.waitlistApplications }}</div>
            </div>
            <div class="stat-items">
              <div class="stat-label">Waitlist</div>
              <div class="stat-change" v-if="displayedStats.waitlistRate > 0">
                {{ displayedStats.waitlistRate }}% waitlist rate
              </div>
            </div>
          </div>
        </div>

        <!-- Rejected Card -->
        <div class="stat-card stat-card-danger">
          <div class="stat-icon">
            <span class="material-icons">cancel</span>
          </div>
          <div class="stat-content">
            <div class="stat-items">
              <div class="stat-value">{{ displayedStats.rejectedApplications }}</div>
            </div>
            <div class="stat-items">
              <div class="stat-label">Rejected</div>
              <div class="stat-change" v-if="displayedStats.rejectionRate > 0">
                {{ displayedStats.rejectionRate }}% rejection rate
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- MAIN CONTENT GRID -->
      <div class="content-grid">
        <!-- RECENT APPLICATIONS -->
        <div class="content-card">
          <div class="card-header">
            <h3 class="card-title">Recent Applications</h3>
            <div class="card-actions">
              <select 
                v-model="recentFilter" 
                @change="filterRecentApplications"
                class="mini-select"
              >
                <option value="all">All Statuses</option>
                <option value="accepted">Accepted</option>
                <option value="rejected">Rejected</option>
                <option value="pending">Pending</option>
              </select>
              <a href="/admin/applications" class="card-action">View All</a>
            </div>
          </div>
          <div class="card-content">
           <div class="horizontal-scroll">
             <div class="applications-list">
              <div 
                v-for="app in filteredRecentApplications" 
                :key="app.id" 
                class="application-item" 
                @click="goToApplication(app.id)"
              >
                <div class="application-info">
                  <div class="applicant-name">
                    {{ app.last_name || '' }}, {{ app.first_name || '' }}
                    <span v-if="app.school_year" class="year-badge">
                      {{ app.school_year.school_year }}
                    </span>
                  </div>
                  <div class="applicant-email">{{ app.email_address || '' }}</div>
                </div>
                <div class="application-meta">
                  <span :class="`status-badge status-${getStatusClass(app.application_status)}`">
                    {{ app.application_status || 'For Review' }}
                  </span>
                  <div class="application-date">
                    {{ formatDate(app.created_at) }}
                  </div>
                </div>
              </div>
              <div v-if="filteredRecentApplications.length === 0" class="empty-state">
                No applications found
              </div>
            </div>
           </div>
          </div>
        </div>

        <!-- STATUS DISTRIBUTION -->
        <div class="content-card">
          <div class="card-header">
            <div class="card-title-group">
              <h3 class="card-title">Application Status Distribution</h3>
              <div class="card-subtitle">
                <select 
                  v-model="distributionYearFilter" 
                  @change="updateDistribution"
                  class="mini-select"
                >
                  <option value="all">All Years</option>
                  <option v-if="activeYear" :value="activeYear.id">
                    {{ activeYear.school_year }} (Active Year)
                  </option>
                  <option 
                    v-for="year in distributionNonActiveSchoolYears" 
                    :key="year.id" 
                    :value="year.id"
                  >
                    {{ year.school_year }}
                  </option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-content">
            <div class="distribution-chart">
              <div v-for="item in filteredDistribution" :key="item.status" class="distribution-item">
                <div class="distribution-label">
                  <span class="status-dot" :class="`dot-${item.class}`"></span>
                  {{ item.label }}
                </div>
                <div class="distribution-bar">
                  <div class="bar-track">
                    <div 
                      :class="`bar-fill fill-${item.class}`" 
                      :style="{ width: item.percentage + '%' }"
                    ></div>
                  </div>
                  <div class="distribution-value">{{ item.count }} ({{ item.percentage }}%)</div>
                </div>
              </div>
            </div>
            
            <!-- SCHOOL YEAR SUMMARY -->
            <div class="year-summary">
              <h4 v-if="distributionYearFilter === 'all'">All School Years</h4>
              <h4 v-else-if="activeYear && parseInt(distributionYearFilter) === activeYear.id">Current Active Year</h4>
              <h4 v-else>Selected School Year</h4>
              
              <div v-if="distributionYearFilter === 'all'" class="year-info">
                <div class="year-name">All Years Combined</div>
                <div class="year-stats">
                  <span>{{ distributionStats.totalApplications }} total applications</span>
                  <span class="stat-separator">•</span>
                  <span>{{ distributionStats.acceptedApplications }} accepted</span>
                </div>
              </div>
              <div v-else-if="distributionSelectedYearObject" class="year-info">
                <div class="year-name">{{ distributionSelectedYearObject.school_year }}</div>
                <div class="year-stats">
                  <span>{{ distributionStats.totalApplications }} applications</span>
                  <span class="stat-separator">•</span>
                  <span>{{ distributionStats.acceptedApplications }} accepted</span>
                </div>
              </div>
              <div v-else class="year-info">
                <div class="no-active-year">No school year selected</div>
              </div>
            </div>
            
            <!-- QUICK FILTER LINKS -->
            <div class="quick-filters">
              <h4>Quick View</h4>
              <div class="filter-links">
                <a 
                  :href="`/admin/applications?status=For+Review&year=${distributionYearFilter}`" 
                  class="filter-link filter-link-warning"
                >
                  Pending Review ({{ distributionStats.pendingApplications }})
                </a>
                <a 
                  :href="`/admin/applications?status=Accepted&year=${distributionYearFilter}`" 
                  class="filter-link filter-link-success"
                >
                  Accepted ({{ distributionStats.acceptedApplications }})
                </a>
                <a 
                  :href="`/admin/applications?status=Rejected&year=${distributionYearFilter}`" 
                  class="filter-link filter-link-danger"
                >
                  Rejected ({{ distributionStats.rejectedApplications }})
                </a>
                <a 
                  :href="`/admin/applications?status=Waitlist&year=${distributionYearFilter}`" 
                  class="filter-link filter-link-info"
                >
                  Waitlist ({{ distributionStats.waitlistApplications }})
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="content-card">
          <div class="card-header">
            <h3 class="card-title">Quick Actions</h3>
          </div>
          <div class="card-content">
            <div class="actions-grid">
              <a href="/admin/applications" class="action-item">
                <div class="action-icon">
                  <span class="material-icons">grading</span>
                </div>
                <div class="action-content">
                  <div class="action-title">Review Applications</div>
                  <div class="action-description">{{ stats.pendingApplications }} pending review</div>
                </div>
              </a>

              <a href="/admin/application-periods" class="action-item">
                <div class="action-icon">
                  <span class="material-icons">calendar_today</span>
                </div>
                <div class="action-content">
                  <div class="action-title">Manage School Years</div>
                  <div class="action-description">Set active year</div>
                </div>
              </a>
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
import { onMounted } from 'vue'

onMounted(() => {
  if (!document.querySelector('link[href*="material-icons"]')) {
    const link = document.createElement('link')
    link.href = 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200'
    link.rel = 'stylesheet'
    document.head.appendChild(link)
  }
})

// Define interfaces
interface Application {
  id: number;
  first_name?: string;
  middle_name?: string;
  last_name?: string;
  email_address?: string;
  application_status?: string;
  created_at?: string;
  school_year?: {
    id: number;
    school_year: string;
    is_active: boolean;
  };
}

interface SchoolYear {
  id: number;
  school_year: string;
  is_active: boolean;
}

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

interface Props {
  activeApplications: PaginatedApplications;
  archivedApplications: PaginatedApplications;
  activeYear?: SchoolYear | null;
  stats: {
    totalApplications: number;
    acceptedApplications: number;
    rejectedApplications: number;
    pendingApplications: number;
    waitlistApplications: number;
    recentApplications: Application[];
    acceptanceRate: number;
    waitlistRate: number;
    rejectionRate: number;
    pendingRate: number;
    distributionStats?: {
      totalApplications: number;
      acceptedApplications: number;
      rejectedApplications: number;
      pendingApplications: number;
      waitlistApplications: number;
    };
  };
  allSchoolYears: SchoolYear[];
  // ADD FILTERS PROP (like ApplicationPeriods)
  filters?: {
    selected_year?: string;
    distribution_year?: string;
    recent_filter?: string;
  };
}

const props = defineProps<Props>();

// Initialize filter values from server (like ApplicationPeriods)
const selectedYear = ref<string>(props.filters?.selected_year || props.activeYear?.id?.toString() || 'all');
const distributionYearFilter = ref<string>(props.filters?.distribution_year || props.activeYear?.id?.toString() || 'all');
const recentFilter = ref<string>(props.filters?.recent_filter || 'all');

// ========== COMPUTED PROPERTIES ==========
// Simple computed properties that just format server data (like ApplicationPeriods)

const displayedStats = computed(() => {
  return {
    totalApplications: props.stats.totalApplications,
    acceptedApplications: props.stats.acceptedApplications,
    rejectedApplications: props.stats.rejectedApplications,
    pendingApplications: props.stats.pendingApplications,
    waitlistApplications: props.stats.waitlistApplications,
    acceptanceRate: props.stats.acceptanceRate,
    rejectionRate: props.stats.rejectionRate,
    waitlistRate: props.stats.waitlistRate
  };
});

const filteredRecentApplications = computed((): Application[] => {
  return props.stats.recentApplications || [];
});

const distributionStats = computed(() => {
  // Use distributionStats if provided, otherwise use main stats
  return props.stats.distributionStats || {
    totalApplications: props.stats.totalApplications,
    acceptedApplications: props.stats.acceptedApplications,
    rejectedApplications: props.stats.rejectedApplications,
    pendingApplications: props.stats.pendingApplications,
    waitlistApplications: props.stats.waitlistApplications,
  };
});

// School years for dropdowns
const nonActiveSchoolYears = computed((): SchoolYear[] => {
  if (!props.allSchoolYears || !Array.isArray(props.allSchoolYears)) {
    return [];
  }
  
  return props.allSchoolYears.filter((year: SchoolYear): boolean => {
    if (props.activeYear && year.id === props.activeYear.id) {
      return false;
    }
    return true;
  }).sort((a: SchoolYear, b: SchoolYear): number => 
    b.school_year.localeCompare(a.school_year)
  );
});

const distributionNonActiveSchoolYears = computed((): SchoolYear[] => {
  return nonActiveSchoolYears.value;
});

// For distribution chart - USE SERVER DATA DIRECTLY
interface DistributionItem {
  status: string;
  label: string;
  count: number;
  percentage: number;
  class: string;
}

const filteredDistribution = computed((): DistributionItem[] => {
  const stats = distributionStats.value;
  const total = stats.totalApplications;
  
  if (total === 0) return [];
  
  // Calculate percentages from server counts (or server should provide these)
  const acceptedPercentage = Math.round((stats.acceptedApplications / total) * 100);
  const pendingPercentage = Math.round((stats.pendingApplications / total) * 100);
  const waitlistPercentage = Math.round((stats.waitlistApplications / total) * 100);
  const rejectedPercentage = Math.round((stats.rejectedApplications / total) * 100);
  
  return [
    { 
      status: 'accepted', 
      label: 'Accepted', 
      count: stats.acceptedApplications, 
      percentage: acceptedPercentage,
      class: 'accepted'
    },
    { 
      status: 'pending', 
      label: 'Pending Review', 
      count: stats.pendingApplications, 
      percentage: pendingPercentage,
      class: 'pending'
    },
    { 
      status: 'waitlist', 
      label: 'Waitlist', 
      count: stats.waitlistApplications, 
      percentage: waitlistPercentage,
      class: 'waitlist'
    },
    { 
      status: 'rejected', 
      label: 'Rejected', 
      count: stats.rejectedApplications, 
      percentage: rejectedPercentage,
      class: 'rejected'
    }
  ].filter(item => item.count > 0);
});

const distributionSelectedYearObject = computed(() => {
  if (distributionYearFilter.value === 'all') return null;
  const yearId = parseInt(distributionYearFilter.value);
  
  if (props.activeYear && props.activeYear.id === yearId) {
    return props.activeYear;
  }
  
  return props.allSchoolYears?.find(y => y.id === yearId) || null;
});

// ========== METHODS ==========
// Use Inertia.get with filters (like ApplicationPeriods)

function updateDashboardByYear(): void {
  // Just like ApplicationPeriods - make a request to server with filters
  Inertia.visit(window.location.pathname, {
    data: {
      selected_year: selectedYear.value,
      distribution_year: distributionYearFilter.value,
      recent_filter: recentFilter.value,
    },
    preserveScroll: true,
    preserveState: true,
    replace: true, 
    only: ['stats', 'filters'], 
  });
}

function updateDistribution(): void {
  // Update all filters and reload
  updateDashboardByYear();
}

function filterRecentApplications(): void {
  // Update all filters and reload
  updateDashboardByYear();
}

// Other methods remain the same
function goToApplication(id: number): void {
  Inertia.get(`/admin/applications/${id}`);
}

function getStatusClass(status: string | undefined | null): string {
  if (!status) return 'pending';
  const statusLower = status.toLowerCase();
  if (statusLower.includes('accepted')) return 'accepted';
  if (statusLower.includes('rejected')) return 'rejected';
  if (statusLower.includes('waitlist')) return 'waitlist';
  return 'pending';
}

function formatDate(dateString?: string): string {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
}
</script>


<style scoped>
.dashboard-container {
  padding: var(--space-6);
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

/* Year Filter Section */
.year-filter-section {
  background: var(--color-background);
  border-radius: var(--radius-l);
  padding: var(--space-5) var(--space-6);
  margin-bottom: var(--space-6);
  box-shadow: var(--elevation-2);
  border: 1px solid var(--color-neutral);
  border-left: 4px solid var(--color-primary);
  display: flex;
  align-items: center;
  gap: var(--space-4);
}

.filter-label {
  font-size: var(--font-size-p);
  font-weight: 600;
  color: var(--color-text-light);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-family: var(--font-sans);
  white-space: nowrap;
}

.year-filter-select {
  padding: var(--space-2) var(--space-3);
  border: 1px solid var(--color-neutral);
  border-radius: var(--radius-m);
  font-size: var(--font-size-p);
  background: var(--color-background);
  color: var(--color-foreground);
  cursor: pointer;
  transition: all 0.2s ease;
  font-family: var(--font-alt);
  min-width: 250px;
}

.year-filter-select:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
}

/* Filtered Stats */
.filtered-stats {
  background: var(--color-background);
  border-radius: var(--radius-l);
  padding: var(--space-5);
  margin-bottom: var(--space-6);
  box-shadow: var(--elevation-2);
  border: 1px solid var(--color-neutral);
  border-left: 4px solid var(--color-primary);
}

.filtered-stats-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--space-4);
  padding-bottom: var(--space-3);
  border-bottom: 2px solid var(--color-neutral);
}

.filtered-stats-header h3 {
  font-size: var(--font-size-h6);
  font-weight: 600;
  color: var(--color-primary);
  margin: 0;
  text-transform: uppercase;
  font-family: var(--font-sans);
}

.filtered-count {
  font-size: var(--font-size-p);
  color: var(--color-neutral-dark);
  font-weight: 500;
  font-family: var(--font-alt);
}

/* Stats Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: var(--space-6);
  margin-bottom: var(--space-8);
}

.stats-grid.compact {
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: var(--space-3);
}

.stat-card {
  background: var(--color-background);
  border-radius: var(--radius-l);
  padding: var(--space-6);
  display: flex;
  align-items: center;
  gap: var(--space-4);
  box-shadow: var(--elevation-2);
  transition: all 0.2s ease;
  border: 1px solid var(--color-neutral);
  border-left: 4px solid transparent;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: var(--elevation-3);
}

.stat-card.compact {
  padding: var(--space-4);
}

.stat-card.compact .stat-value {
  font-size: 2rem;
}

/* Card border colors */
.stat-card-primary {
  border-left-color: var(--color-primary);
}
.stat-card-success {
  border-left-color: var(--color-primary-light);
}
.stat-card-warning {
  border-left-color: var(--color-for-review-text);
}
.stat-card-danger {
  border-left-color: var(--color-rejected-text);
}
.stat-card-info {
  border-left-color: var(--color-secondary);
}

.stat-icon {
  width: 48px;
  height: 48px;
  border-radius: var(--radius-m);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  background: var(--color-background);
  border: 1px solid var(--color-neutral);
}

/* Icon backgrounds matching AdminApplications */
.stat-card-primary .stat-icon {
  background-color: var(--color-primary-20);
}
.stat-card-success .stat-icon {
  background-color: var(--color-light-bg-20);
}
.stat-card-warning .stat-icon {
  background-color: var(--color-for-review);
}
.stat-card-danger .stat-icon .material-icons {
  color: var(--color-rejected-text);
}
.stat-card-info .stat-icon {
  background-color: var(--color-secondary-20);
}

/* Icon colors */
.stat-card-primary .stat-icon .material-icons {
  color: var(--color-primary);
}
.stat-card-success .stat-icon .material-icons {
  color: var(--color-primary-light);
}
.stat-card-warning .stat-icon .material-icons {
  color: var(--color-for-review-text);
}
.stat-card-danger .stat-icon .material-icons {
  color: var(--color-rejected-text);
}
.stat-card-info .stat-icon .material-icons {
  color: var(--color-secondary);
}

.stat-content {
  flex: 1;
  display: flex;
  gap: var(--space-4);
}

.stat-value {
  font-size: var(--font-size-h4);
  font-weight: 700;
  line-height: 1;
  color: var(--color-foreground);
  font-family: var(--font-alt);
}

.stat-label {
  font-size: var(--font-size-p);
  font-weight: 600;
  color: var(--color-text-light);
  margin-bottom: var(--space-1);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-family: var(--font-sans);
}
.stat-change,
.stat-subtext {
  color: var(--color-neutral-dark);
  font-family: var(--font-alt);
}

.stat-card-success .stat-change {
  color: var(--color-primary-light);
}
.stat-card-danger .stat-change {
  color: var(--color-rejected-text);
}

/* Content Grid */
.content-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: var(--space-6);
  margin-bottom: var(--space-8);
}

.content-card {
  background: var(--color-background);
  border-radius: var(--radius-l);
  overflow: hidden;
  box-shadow: var(--elevation-2);
  border: 1px solid var(--color-neutral);
  border-left: 4px solid var(--color-primary);
  height: fit-content;
}

.card-header {
  padding: var(--space-5) var(--space-6);
  border-bottom: 1px solid var(--color-neutral);
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: var(--color-background);
}

.card-title-group {
  display: flex;
  flex-direction: column;
  gap: var(--space-1);
}

.card-title {
  font-size: var(--font-size-h6);
  font-weight: 600;
  color: var(--color-primary);
  margin: 0;
  text-transform: uppercase;
  font-family: var(--font-sans);
}

.card-subtitle {
  font-size: var(--font-size-p);
  color: var(--color-neutral-dark);
  font-family: var(--font-alt);
}

.card-actions {
  display: flex;
  align-items: center;
  gap: var(--space-3);
}

.mini-select {
  padding: var(--space-1) var(--space-3);
  border: 1px solid var(--color-neutral);
  border-radius: var(--radius-m);
  font-size: var(--font-size-p);
  background: var(--color-background);
  color: var(--color-foreground);
  cursor: pointer;
  transition: all 0.2s ease;
  font-family: var(--font-alt);
  min-width: 180px;
}

.mini-select:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
}

.card-action {
  font-size: var(--font-size-p);
  font-weight: 600;
  color: var(--color-primary);
  text-decoration: none;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-family: var(--font-sans);
}

.card-action:hover {
  text-decoration: underline;
}

.card-content {
  padding: var(--space-6);
  flex: 1;
  display: flex;
  flex-direction: column;
}

/* Applications List */
.horizontal-scroll{
  overflow-x: auto;
  overflow-y: hidden;
  width: 100%;
  padding-bottom: 8px;
   display: flex;
  gap: var(--space-5)
}
.horizontal-scroll .applications-list {
  width: 100%;
}
.applications-list {
  display: flex;
  flex-direction: column;
  gap: var(--space-3);
}

.application-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: var(--space-3) var(--space-4);
  border-radius: var(--radius-m);
  cursor: pointer;
  transition: all 0.2s ease;
  border: 1px solid var(--color-neutral);
  background: var(--color-background);
}

.application-item:hover {
  background-color: rgba(13, 110, 253, 0.05);
  border-color: var(--color-primary-light);
  transform: translateX(2px);
}

.application-info {
  flex: 1;
}

.applicant-name {
  font-weight: 500;
  font-size: 1.4rem;
  color: var(--color-foreground);
  margin-bottom: var(--space-1);
  display: flex;
  align-items: center;
  gap: var(--space-2);
  font-family: var(--font-alt);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.year-badge {
  font-size: 1.4rem;
  padding: 2px 8px;
  background: var(--color-light-bg);
  color: var(--color-neutral-dark);
  border-radius: var(--radius-s);
  border: 1px solid var(--color-neutral);
  font-weight: 500;
  font-family: var(--font-alt);
}

.applicant-email {
  font-size: 1.4rem;
  color: var(--color-neutral-dark);
  font-family: var(--font-alt);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.application-meta {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: var(--space-1);
}

.application-date {
  font-size: var(--font-size-p);
  color: var(--color-neutral-dark);
  font-family: var(--font-alt);
}

/* Status badges - matching AdminApplications */
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
.status-pending {
  background: var(--color-for-review);
  color: var(--color-for-review-text);
}
.status-waitlist {
  background: var(--color-secondary-20);
  color: var(--color-secondary);
}

.empty-state {
  text-align: center;
  padding: var(--space-8) var(--space-4);
  color: var(--color-neutral);
  font-style: italic;
  background: var(--color-background);
  border-radius: var(--radius-m);
  border: 1px solid var(--color-neutral);
  font-size: var(--font-size-p);
  font-family: var(--font-alt);
}

/* Distribution Chart */
.distribution-chart {
  display: flex;
  flex-direction: column;
  gap: var(--space-4);
  margin-bottom: var(--space-6);
}

.distribution-item {
  display: flex;
  align-items: center;
  gap: var(--space-4);
}

.distribution-label {
  display: flex;
  align-items: center;
  gap: var(--space-2);
  font-size: var(--font-size-p);
  color: var(--color-text-light);
  width: 140px;
  flex-shrink: 0;
  text-transform: uppercase;
  font-weight: 600;
  font-family: var(--font-sans);
}

.status-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
}

.distribution-bar {
  flex: 1;
  display: flex;
  align-items: center;
  gap: var(--space-4);
}

.bar-track {
  flex: 1;
  height: 8px;
  background-color: var(--color-background);
  border-radius: var(--radius-s);
  overflow: hidden;
  border: 1px solid var(--color-neutral);
}

.bar-fill {
  height: 100%;
  border-radius: var(--radius-s);
}
.dot-accepted,
.fill-accepted {
  background-color: var(--color-primary-light);
}
.dot-pending,
.fill-pending {
  background-color: var(--color-for-review-text);
}
.dot-rejected,
.fill-rejected {
  background-color: var(--color-rejected-text);
}
.dot-waitlist,
.fill-waitlist {
  background-color: var(--color-secondary);
}

.distribution-value {
  font-size: var(--font-size-p);
  font-weight: 600;
  color: var(--color-foreground);
  width: 80px;
  text-align: right;
  font-family: var(--font-alt);
}

/* Year Summary */
.year-summary {
  padding-top: var(--space-4);
  border-top: 1px solid var(--color-neutral);
  margin-top: var(--space-4);
}

.year-summary h4 {
  font-size: var(--font-size-p);
  font-weight: 600;
  color: var(--color-primary);
  margin-bottom: var(--space-2);
  text-transform: uppercase;
  font-family: var(--font-sans);
}

.year-info {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
  background: var(--color-background);
  padding: var(--space-3);
  border-radius: var(--radius-m);
  border: 1px solid var(--color-neutral);
}

.year-name {
  font-size: var(--font-size-p);
  font-weight: 700;
  color: var(--color-foreground);
  font-family: var(--font-alt);
}

.year-stats {
  display: flex;
  align-items: center;
  gap: var(--space-2);
  font-size: var(--font-size-p);
  color: var(--color-neutral-dark);
  font-family: var(--font-alt);
}

.stat-separator {
  color: var(--color-neutral);
}

.no-active-year {
  font-style: italic;
  color: var(--color-neutral);
  font-family: var(--font-alt);
}

/* Quick Filters */
.quick-filters {
  margin-top: var(--space-6);
  padding-top: var(--space-4);
  border-top: 1px solid var(--color-neutral);
}

.quick-filters h4 {
  font-size: var(--font-size-p);
  font-weight: 600;
  color: var(--color-primary);
  margin-bottom: var(--space-3);
  text-transform: uppercase;
  font-family: var(--font-sans);
}

.filter-links {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
}

.filter-link {
  display: block;
  padding: var(--space-2) var(--space-3);
  border-radius: var(--radius-m);
  text-decoration: none;
  font-size: var(--font-size-p);
  font-weight: 600;
  transition: all 0.2s ease;
  background: var(--color-background);
  border: 1px solid var(--color-neutral);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-family: var(--font-sans);
  border-left: 3px solid;
}

.filter-link:hover {
  transform: translateX(2px);
  background: rgba(13, 110, 253, 0.05);
}

.filter-link-success {
  border-left-color: var(--color-primary-light);
  color: var(--color-primary-light);
}
.filter-link-warning {
  border-left-color: var(--color-for-review-text);
  color: var(--color-for-review-text);
}
.filter-link-danger {
  border-left-color: var(--color-rejected-text);
  color: var(--color-rejected-text);
}
.filter-link-info {
  border-left-color: var(--color-secondary);
  color: var(--color-secondary);
}

/* Quick Actions */
.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: var(--space-4);
}

.action-item {
  display: flex;
  align-items: center;
  gap: var(--space-4);
  padding: var(--space-4);
  border-radius: var(--radius-m);
  text-decoration: none;
  color: inherit;
  transition: all 0.2s ease;
  border: 1px solid var(--color-neutral);
  background: var(--color-background);
  border-left: 4px solid var(--color-primary);
}

.action-item:hover {
  border-color: var(--color-primary);
  transform: translateX(2px);
  background: rgba(13, 110, 253, 0.05);
}

.action-icon {
  width: 40px;
  height: 40px;
  border-radius: var(--radius-s);
  color: var(--color-primary);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  flex: 0 0 auto;
  border: 1px solid var(--color-primary);
  background: var(--color-light-bg);
}

.action-icon .material-icons {
  color: var(--color-primary);
  font-size: 20px;
}

.action-content {
  flex: 1;
}

.action-title {
  font-weight: 600;
  color: var(--color-foreground);
  margin-bottom: var(--space-1);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-size: var(--font-size-p);
  font-family: var(--font-sans);
}

.action-description {
  font-size: var(--font-size-p);
  color: var(--color-neutral-dark);
  font-family: var(--font-alt);
}
 .stat-items {
    display: flex;
    justify-content: center;
    flex-direction: column;
    
  }
/* Material Icons */
.material-icons {
  color: var(--color-primary);
  font-family: 'Material Icons';
  font-weight: normal;
  font-style: normal;
  font-size: 24px;
  line-height: 1;
  letter-spacing: normal;
  text-transform: none;
  display: inline-block;
  white-space: nowrap;
  word-wrap: normal;
  direction: ltr;
  -webkit-font-feature-settings: 'liga';
  -webkit-font-smoothing: antialiased;
}

/* Responsive Design - matching AdminApplications breakpoints */
@media (max-width: 1200px) {
  .content-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .dashboard-container {
    padding: var(--space-4);
  }
  
  .year-filter-section {
    flex-direction: column;
    align-items: flex-start;
    gap: var(--space-2);
    padding: var(--space-4);
  }
  
  .year-filter-select {
    width: 100%;
    min-width: 0;
  }
 

  .stat-content {
    flex: 1;
    min-width: 0;
    display: flex;
    gap: 1rem;

  }
  .stats-grid {
    grid-template-columns: 1fr;
    gap: var(--space-4);
  }
  
  .content-grid {
    grid-template-columns: 1fr;
    gap: var(--space-4);
  }
  
  .content-card {
    border-radius: var(--radius-m);
  }
  
  .card-header {
    padding: var(--space-4);
    flex-direction: column;
    align-items: flex-start;
    gap: var(--space-3);
  }
  
  .card-content {
    padding: var(--space-4);
  }
  
  .card-title {
    font-size: var(--font-size-p);
  }
  
  
  .card-actions {
    width: 100%;
    justify-content: space-between;
  }
  
  .distribution-item {
    flex-direction: column;
    align-items: stretch;
    gap: var(--space-2);
  }
  
  .distribution-label {
    width: 100%;
  }
  
  .distribution-value {
    width: 100%;
    text-align: left;
  }
  
  .actions-grid {
    grid-template-columns: 1fr;
  }
  
  .filter-links {
    flex-direction: column;
  }
  
  .mini-select {
    width: 100%;
    min-width: 0;
  }
}
</style>
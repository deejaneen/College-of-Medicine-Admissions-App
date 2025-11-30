<template>
  <AdminIndex>
    <div class="application-detail-container">
       <div class="application-display-container">
      <!-- Header Section -->
      <div class="display-header">
        <div class="header-main">
          <h1 class="page-title">
            Application Details
            <span class="slash">/</span>
            <span class="name">{{ application.last_name }}, {{ application.first_name }} {{ application.middle_name }}</span>
          </h1>
          <div class="application-meta">
            <span class="meta-badge">Submitted: {{ formatDate(application.created_at) }}</span>
            <span class="meta-badge">School Year: {{ application.school_year?.school_year }}</span>
            <span :class="`status-badge status-${getStatusClass(application.application_status)}`">
              {{ application.application_status }}
            </span>
          </div>
        </div>
        <p>Medical School Application - Complete Application Details</p>
      </div>

      <!-- Main Content Grid -->
      <div class="display-content">
        <!-- Personal Information -->
        <div class="info-card">
          <div class="card-header">
            <span class="material-icons">person</span>
            <h2 class="card-title">Personal Information</h2>
          </div>
          <div class="field-grid">
            <div class="field-row">
              <span class="field-label">Full Name</span>
              <span class="field-value">{{ application.last_name }}, {{ application.first_name }} {{ application.middle_name }}</span>
            </div>
            <div class="field-row">
              <span class="field-label">Email Address</span>
              <span class="field-value">{{ application.email_address }}</span>
            </div>
            <div class="field-row">
              <span class="field-label">Active Gmail Account</span>
              <span class="field-value">{{ application.active_gmail_account }}</span>
            </div>
            <div class="field-row">
              <span class="field-label">Cellphone Number</span>
              <span class="field-value">{{ application.cellphone_number }}</span>
            </div>
            <div class="field-row">
              <span class="field-label">Date of Birth</span>
              <span class="field-value">{{ formatDate(application.date_of_birth) }}</span>
            </div>
            <div class="field-row">
              <span class="field-label">Citizenship</span>
              <span class="field-value">{{ application.citizenship }}</span>
            </div>
            <div class="field-row">
              <span class="field-label">Civil Status</span>
              <span class="field-value">{{ application.civil_status }}</span>
            </div>
            <div class="field-row">
              <span class="field-label">Sex</span>
              <span class="field-value">{{ application.sex }}</span>
            </div>
            <div class="field-row">
              <span class="field-label">Religion</span>
              <span class="field-value">{{ application.religion }}</span>
            </div>
            <div class="field-row">
              <span class="field-label">Indigenous Group</span>
              <span class="field-value">
                <span :class="`yes-no-badge ${application.indigenous_group?.toLowerCase() === 'yes' ? 'yes-badge' : 'no-badge'}`">
                  {{ application.indigenous_group }}
                </span>
                <div v-if="application.indigenous_group?.toLowerCase() === 'yes' && application.indigenous_group_specify" class="conditional-field">
                  <strong>Specify:</strong> {{ application.indigenous_group_specify }}
                </div>
              </span>
            </div>
            <div class="field-row">
              <span class="field-label">Physical Disability</span>
              <span class="field-value">
                <span :class="`yes-no-badge ${application.physical_disability?.toLowerCase() === 'yes' ? 'yes-badge' : 'no-badge'}`">
                  {{ application.physical_disability }}
                </span>
                <div v-if="application.physical_disability?.toLowerCase() === 'yes' && application.physical_disability_specify" class="conditional-field">
                  <strong>Specify:</strong> {{ application.physical_disability_specify }}
                </div>
              </span>
            </div>
            <div class="field-row">
              <span class="field-label">Community Health Volunteer</span>
              <span class="field-value">
                <span :class="`yes-no-badge ${application.community_health_volunteer?.toLowerCase() === 'yes' ? 'yes-badge' : 'no-badge'}`">
                  {{ application.community_health_volunteer }}
                </span>
                <div v-if="application.community_health_volunteer?.toLowerCase() === 'yes' && application.community_health_organization" class="conditional-field">
                  <strong>Organization:</strong> {{ application.community_health_organization }}
                </div>
              </span>
            </div>
            <div class="field-row">
              <span class="field-label">Complete Address</span>
              <span class="field-value">
                {{ application.permanent_home_address }}, 
                {{ application.municipality }}, 
                {{ application.province }}, 
                {{ application.zip_code }}
              </span>
            </div>
          </div>
        </div>

        <!-- Educational Background -->
        <div class="info-card">
          <div class="card-header">
            <span class="material-icons">school </span>
            <h2 class="card-title">Educational Background</h2>
          </div>
          <div class="field-grid">
            <!-- Bachelor's Degree -->
            <div class="field-row" style="grid-template-columns: 1fr; margin-bottom: var(--space-3);">
              <span class="field-label" style="font-size: var(--font-size-p); color: var(--color-primary);">Bachelor's Degree</span>
            </div>
            <div class="field-row">
              <span class="field-label">Degree</span>
              <span class="field-value">{{ application.bachelors_degree }}</span>
            </div>
            <div class="field-row">
              <span class="field-label">School/University</span>
              <span class="field-value">{{ application.name_of_school_bachelors_degree }}</span>
            </div>
            <div class="field-row">
              <span class="field-label">GWA</span>
              <span class="field-value">{{ application.gwa_for_bachelors_degree }}</span>
            </div>
            
            <!-- Graduate Studies -->
            <div v-if="application.graduate_studies" class="field-row" style="grid-template-columns: 1fr; margin-top: var(--space-4); padding-top: var(--space-4); border-top: 1px dashed var(--color-neutral);">
              <span class="field-label" style="font-size: var(--font-size-p); color: var(--color-primary);">Graduate Studies</span>
            </div>
            <div v-if="application.graduate_studies" class="field-row">
              <span class="field-label">Degree</span>
              <span class="field-value">{{ application.graduate_studies }}</span>
            </div>
            <div v-if="application.name_of_school_graduate_studies" class="field-row">
              <span class="field-label">School/University</span>
              <span class="field-value">{{ application.name_of_school_graduate_studies }}</span>
            </div>
            <div v-if="application.date_of_graduation" class="field-row">
              <span class="field-label">Date of Graduation</span>
              <span class="field-value">{{ formatDate(application.date_of_graduation) }}</span>
            </div>
          </div>
        </div>

        <!-- Family Information -->
        <div class="info-card">
          <div class="card-header">
            <span class="material-icons">diversity_3 </span>
            <h2 class="card-title">Family Information</h2>
          </div>
          <div class="field-grid">
            <!-- Father's Information -->
            <div class="field-row" style="grid-template-columns: 1fr; margin-bottom: var(--space-3);">
              <span class="field-label" style="font-size: var(--font-size-p); color: var(--color-primary);">Father's Information</span>
            </div>
            <div class="field-row">
              <span class="field-label">Full Name</span>
              <span class="field-value">{{ application.father_first_name }} {{ application.father_middle_name }} {{ application.father_last_name }}</span>
            </div>
            <div class="field-row">
              <span class="field-label">Occupation</span>
              <span class="field-value">{{ application.father_occupation || 'Not specified' }}</span>
            </div>

            <!-- Mother's Information -->
            <div class="field-row" style="grid-template-columns: 1fr; margin-top: var(--space-4); padding-top: var(--space-4); border-top: 1px dashed var(--color-neutral);">
              <span class="field-label" style="font-size: var(--font-size-p); color: var(--color-primary);">Mother's Information</span>
            </div>
            <div class="field-row">
              <span class="field-label">Full Name</span>
              <span class="field-value">{{ application.mother_first_name }} {{ application.mother_middle_name }} {{ application.mother_last_name }}</span>
            </div>
            <div class="field-row">
              <span class="field-label">Occupation</span>
              <span class="field-value">{{ application.mother_occupation || 'Not specified' }}</span>
            </div>

            <!-- Guardian's Information (if provided) -->
            <div v-if="application.guardian_first_name || application.guardian_last_name" class="field-row" style="grid-template-columns: 1fr; margin-top: var(--space-4); padding-top: var(--space-4); border-top: 1px dashed var(--color-neutral);">
              <span class="field-label" style="font-size: var(--font-size-p); color: var(--color-primary);">Guardian's Information</span>
            </div>
            <div v-if="application.guardian_first_name || application.guardian_last_name" class="field-row">
              <span class="field-label">Full Name</span>
              <span class="field-value">{{ application.guardian_first_name }} {{ application.guardian_middle_name }} {{ application.guardian_last_name }}</span>
            </div>
            <div v-if="application.guardian_occupation" class="field-row">
              <span class="field-label">Occupation</span>
              <span class="field-value">{{ application.guardian_occupation }}</span>
            </div>

            <!-- Family Income -->
            <div class="field-row" style="grid-template-columns: 1fr; margin-top: var(--space-4); padding-top: var(--space-4); border-top: 1px dashed var(--color-neutral);">
              <span class="field-label" style="font-size: var(--font-size-p); color: var(--color-primary);">Family Financial Information</span>
            </div>
            <div class="field-row">
              <span class="field-label">Combined Annual Income</span>
              <span class="field-value">{{ application.combined_annual_income_of_family }}</span>
            </div>
          </div>
        </div>

        <!-- NMAT Information -->
        <div class="info-card">
          <div class="card-header">
          <span class="material-icons">check_circle_outline</span>
            <h2 class="card-title">NMAT Information</h2>
          </div>
          <div class="field-grid">
            <div class="field-row">
              <span class="field-label">NMAT Score</span>
              <span class="field-value">{{ application.nmat_score }}</span>
            </div>
            <div class="field-row">
              <span class="field-label">Examination Date</span>
              <span class="field-value">{{ formatDate(application.date_of_nmat_examination) }}</span>
            </div>
          </div>
        </div>

        <!-- File Attachments -->
        <div class="info-card full-width-card" v-if="hasFiles(application)">
          <div class="card-header">
            <span class="material-icons">attachment</span>
            <h2 class="card-title">Documents & Attachments</h2>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-6);">
            <!-- Bachelor's Degree Documents -->
            <div v-if="application.bachelors_transcript_of_records && application.bachelors_transcript_of_records.length > 0">
              <h3 style="font-size: var(--font-size-p); margin-bottom: var(--space-4); color: var(--color-foreground);">Bachelor's Degree Documents</h3>
              <div class="file-attachments">
                <div v-for="(file, index) in application.bachelors_transcript_of_records" :key="`bachelors-${index}`" class="file-item">
                  <span class="material-icons">description </span>
                  <span class="file-name">{{ getFileName(file) }}</span>
                  <button class="file-download" @click="downloadFile(file)">Download</button>
                </div>
              </div>
            </div>

            <div>
              <!-- Graduate Studies Documents -->
              <div v-if="application.graduate_studies_transcript_of_records && application.graduate_studies_transcript_of_records.length > 0">
                <h3 style="font-size: var(--font-size-p); margin-bottom: var(--space-4); color: var(--color-foreground);">Graduate Studies Documents</h3>
                <div class="file-attachments">
                  <div v-for="(file, index) in application.graduate_studies_transcript_of_records" :key="`graduate-${index}`" class="file-item">
                    <span class="material-icons">description </span>
                    <span class="file-name">{{ getFileName(file) }}</span>
                    <button class="file-download" @click="downloadFile(file)">Download</button>
                  </div>
                </div>
              </div>
              
              <!-- NMAT Documents -->
              <div v-if="application.nmat_certification && application.nmat_certification.length > 0">
                <h3 style="font-size: var(--font-size-p); margin: var(--space-6) 0 var(--space-4) 0; color: var(--color-foreground);">NMAT Documents</h3>
                <div class="file-attachments">
                  <div v-for="(file, index) in application.nmat_certification" :key="`nmat-${index}`" class="file-item">
                     <span class="material-icons">description</span>
                    <span class="file-name">{{ getFileName(file) }}</span>
                    <button class="file-download" @click="downloadFile(file)">Download</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Essay Section -->
      <div class="essay-section" v-if="application.why_choose_southern_luzon_state_university">
        <div class="card-header">
          <span class="material-icons">edit_note</span>
          <h2 class="card-title">Personal Essay</h2>
        </div>
        <div class="essay-content">
          {{ application.why_choose_southern_luzon_state_university }}
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="action-buttons">
        <button class="btn btn-secondary" @click="goBack">
          <span class="material-icons button">exit_to_app</span>
          Back to List
        </button>
        <button class="btn btn-success" @click="approveApplication">
          <span class="material-icons">check</span>
          Approve Application
        </button>
        <button class="btn btn-waitlist" @click="waitlistApplication">
           <span class="material-icons ">access_time</span>
          Waitlist
        </button>
        <button class="btn btn-error" @click="rejectApplication">
          <span class="material-icons">close</span>
          Reject Application
        </button>
      </div>
    </div>
    </div>
   
  </AdminIndex>
</template>

<script lang="ts" setup>
import { Inertia } from '@inertiajs/inertia';
import AdminIndex from '../AdminIndex.vue';
import { onMounted } from 'vue'

onMounted(() => {
  if (!document.querySelector('link[href*="material-icons"]')) {
    const link = document.createElement('link')
    link.href = 'https://fonts.googleapis.com/icon?family=Material+Icons'
    link.rel = 'stylesheet'
    document.head.appendChild(link)
  }
})

const { application } = defineProps<{ application: any }>();

// Helper functions
function getStatusClass(status: string): string {
  if (!status) return 'pending';
  const statusLower = status.toLowerCase();
  if (statusLower.includes('accepted') || statusLower.includes('approved')) return 'approved';
  if (statusLower.includes('rejected')) return 'rejected';
  if (statusLower.includes('waitlist')) return 'waitlist';
  return 'pending';
}

function formatDate(dateString: string): string {
  if (!dateString) return 'Not specified';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  });
}

function hasFiles(app: any): boolean {
  return (app.bachelors_transcript_of_records && app.bachelors_transcript_of_records.length > 0) ||
         (app.graduate_studies_transcript_of_records && app.graduate_studies_transcript_of_records.length > 0) ||
         (app.nmat_certification && app.nmat_certification.length > 0);
}

function getFileName(filePath: string): string {
  return filePath.split('/').pop() || 'Document';
}

function downloadFile(filePath: string): void {
  const encoded = encodeURIComponent(filePath); // Encode slashes for URL
  window.open(`/admin/files/download/${encoded}`, '_blank');
}

function goBack(): void {
  Inertia.get('/admin/applications');
}
function approveApplication(): void {
  if (confirm('Are you sure you want to approve this application?')) {
    Inertia.post(`/admin/applications/${application.id}/status`, {
      application_status: 'Accepted'
    });
  }
}

function waitlistApplication(): void {
  if (confirm('Are you sure you want to waitlist this application?')) {
    Inertia.post(`/admin/applications/${application.id}/status`, {
      application_status: 'Waitlist'
    });
  }
}

function rejectApplication(): void {
  if (confirm('Are you sure you want to reject this application?')) {
    Inertia.post(`/admin/applications/${application.id}/status`, {
      application_status: 'Rejected'
    });
  }
}
</script>

<style scoped>
.application-detail-container {
  background: var(--color-light-bg);
}
/* Main Container */
.application-display-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: var(--space-16) 0;
  background: var(--color-light-bg);
  min-height: 100vh;
}

/* Header Section */
.display-header {
  background: var(--color-background);
  border-radius: var(--radius-l);
  padding: var(--space-6);
  margin-bottom: var(--space-6);
  box-shadow: var(--elevation-3);
  border-left: 4px solid var(--color-primary);
}

.header-main {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: var(--space-4);
  flex-wrap: wrap;
  gap: var(--space-4);
}

.page-title {
  font-size: var(--font-size-h4);
  font-weight: 600;
  color: var(--color-primary);
  text-transform: uppercase;
  margin: 0;
}

.page-title .slash {
  font-size: var(--font-size-h4);
  font-weight: 400;
  color: var(--color-neutral);
  margin: 0 var(--space-2);
}

.page-title .name {
  color: var(--color-foreground);
  text-transform: capitalize;
  font-weight: 600;
}

.application-meta {
  display: flex;
  gap: var(--space-4);
  flex-wrap: wrap;
}

.meta-badge {
  display: inline-flex;
  align-items: center;
  padding: var(--space-2) var(--space-3);
  background: var(--color-light-bg);
  border-radius: var(--radius-m);
  font-size: var(--font-size-p);
  font-weight: 500;
  color: var(--color-primary);
}

.status-badge {
  padding: var(--space-2) var(--space-4);
  border-radius: var(--radius-m);
  font-size: var(--font-size-p);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.status-pending {
  background: var(--color-for-review);
  color: var(--color-for-review-text);
}

.status-approved {
  background: var(--color-bright-green-25);
  color: var(--color-primary);
}

.status-rejected {
  background: rgba(231, 76, 60, 0.15);
  color: var(--color-error);
}


.status-waitlist {
  background: var(--color-blue-green-15);
  color: var(--color-primary);
}

/* Content Grid */
.display-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-6);
  margin-bottom: var(--space-6);
}


@media (max-width: 1024px) {
  .display-content {
    grid-template-columns: 1fr;
  }
}

/* Card Components */
.info-card {
  background: var(--color-background);
  border-radius: var(--radius-l);
  padding: var(--space-6);
  box-shadow: var(--elevation-2);
  height: fit-content;
  border: 1px solid var(--color-light-bg-40);
  border-left: 4px solid var(--color-primary);
}

.card-header {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  margin-bottom: var(--space-5);
  padding-bottom: var(--space-3);
  border-bottom: 2px solid var(--color-light-bg);
}

.material-icons{
  color: var(--color-primary);
}

.card-icon {
  width: 24px;
  height: 24px;
  color: var(--color-primary);
}

.card-title {
  font-size: var(--font-size-h6);
  font-weight: 600;
  color: var(--color-primary);
  margin: 0;
  text-transform: uppercase;
}

/* Form Fields Display */
.field-grid {
  display: grid;
  gap: var(--space-4);
}

.field-row {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: var(--space-4);
  align-items: start;
}

@media (max-width: 640px) {
  .field-row {
    grid-template-columns: 1fr;
    gap: var(--space-2);
  }
}

.field-label {
  font-weight: 600;
  color: var(--color-text-light);
  font-size: var(--font-size-p);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.field-value {
  color: var(--color-foreground);
  font-size: var(--font-size-p);
  line-height: 1.6;
}

.field-value.empty {
  color: var(--color-neutral);
  font-style: italic;
}

.yes-no-badge {
  display: inline-block;
  padding: var(--space-1) var(--space-3);
  border-radius: var(--radius-s);
  font-size: var(--font-size-p);
  font-weight: 600;
  text-transform: uppercase;
}

.yes-badge {
  background: var(--color-bright-green-25);
  color: var(--color-primary);
}

.no-badge {
  background: var(--color-secondary-20);
  color: var(--color-secondary);
}

/* File Attachments */
.file-attachments {
  display: flex;
  flex-direction: column;
  gap: var(--space-3);
}

.file-item {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  padding: var(--space-3);
  background: var(--color-light-bg);
  border-radius: var(--radius-m);
  border: 1px solid var(--color-neutral);
  transition: all 0.2s ease;
}

.file-item:hover {
  background: var(--color-bright-green-25);
  border-color: var(--color-primary);
}

.file-icon {
  width: 20px;
  height: 20px;
  color: var(--color-primary);
}

.file-name {
  flex: 1;
  font-size: var(--font-size-p);
  font-weight: 500;
  color: var(--color-foreground);
}

.file-download {
  padding: var(--space-2) var(--space-3);
  background: var(--color-primary);
   color: var(--color-background);
  border: none;
  border-radius: var(--radius-s);
  font-size: var(--font-size-p);
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.file-download:hover {
  background: var(--color-primary-light);
}

/* Action Buttons */
.action-buttons {
  display: flex;
  gap: var(--space-3);
  justify-content: flex-end;
  padding-top: var(--space-6);
  border-top: 1px solid var(--color-neutral);
  flex-wrap: wrap;
}

.btn {
  padding: var(--space-3) var(--space-5);
  border: none;
  border-radius: var(--radius-m);
  font-size: var(--font-size-p);
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: var(--space-2);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.btn-secondary {
  background: var(--color-light-bg);
  color: var(--color-primary);
  border: 1px solid var(--color-primary);
}
.btn-secondary .material-icons {
  color: var(--color-primary);
}
.btn-secondary:hover .material-icons {
  color: var(--color-light-bg);
}

.btn-secondary:hover {
  background: var(--color-primary);
   color: var(--color-background);
}
.btn-success .material-icons{
   color: var(--color-background);
}
.btn-success {
  background: var(--color-primary);
   color: var(--color-background);
}
.btn-success:hover .material-icons,
.btn-success:hover {
  background: var(--color-primary-light);
  transform: translateY(-1px);
}

.btn-waitlist .material-icons{
  color: var(--color-primary);
}
.btn-waitlist {
  background: var(--color-blue-green-15);
  color: var(--color-primary);
  border: 1px solid var(--color-primary);
}

.btn-waitlist:hover .material-icons,
.btn-waitlist:hover {
  background: var(--color-primary);
   color: var(--color-background);
}

.btn-error .material-icons,
.btn-error {
  background: var(--color-error);
   color: var(--color-background);
}

.btn-error:hover .material-icons,
.btn-error:hover {
  background: #c0392b;
}

/* Essay/Long Text Section */
.essay-section {
  background: var(--color-background);
  border-radius: var(--radius-l);
  padding: var(--space-6);
  box-shadow: var(--elevation-2);
  margin-bottom: var(--space-6);
  border: 1px solid var(--color-light-bg-40);
  border-left: 4px solid var(--color-primary);
}

.essay-content {
  line-height: 1.7;
  color: var(--color-foreground);
  white-space: pre-wrap;
  font-size: var(--font-size-p);
}

/* Full Width Card */
.full-width-card {
  grid-column: 1 / -1;
}

/* Section with conditional fields */
.conditional-field {
  background: var(--color-light-bg);
  padding: var(--space-4);
  border-radius: var(--radius-m);
  margin-top: var(--space-2);
  border: 1px solid var(--color-light-bg-40);
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .application-display-container {
    padding: var(--space-4);
  }
  
  .display-header,
  .info-card,
  .essay-section {
    padding: var(--space-4);
  }
  
  .header-main {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .action-buttons {
    justify-content: center;
  }
  
  .btn {
    flex: 1;
    min-width: 140px;
    justify-content: center;
  }
  
  .page-title {
    font-size: var(--font-size-h5);
  }
  
  .card-title {
    font-size: var(--font-size-p);
  }
}
</style>
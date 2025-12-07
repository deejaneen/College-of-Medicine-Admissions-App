<template>
  <AdminIndex>
    <div class="application-detail-container">
      <div class="page-header">
        <h1 class="page-title">
            Application Details
            <span class="slash">/</span>
            <span class="name">{{ application.last_name }}, {{ application.first_name }} {{ application.middle_name }}</span>
          </h1>
      </div>
       <div class="application-display-container">
      <!-- Header Section -->
      <div class="display-header">
        <div class="header-main">
          
          <div class="application-meta">
            <span class="meta-badge">Submitted: {{ formatDate(application.created_at) }}</span>
            <span class="meta-badge">School Year: {{ application.school_year?.school_year }}</span>
            <span :class="`status-badge status-${getStatusClass(application.application_status)}`">
              {{ application.application_status }}
            </span>
          </div>
        </div>
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
   <!-- Approve Confirmation Modal -->
<div v-if="showApproveModal" class="modal-overlay">
  <div class="modal-content">
    <div class="modal-icon warning">
      <span class="material-icons">check_circle_outline</span>
    </div>
    <!-- In Approve Confirmation Modal -->
    <h3 class="modal-title">{{ modalTitle }}</h3>
    <div class="modal-message" v-html="modalMessage"></div>

    <div class="application-summary">
      <p><strong>Applicant:</strong> {{ application.first_name }} {{ application.last_name }}</p>
      <p><strong>Email:</strong> {{ application.email_address }}</p>
      <p><strong>NMAT Score:</strong> {{ application.nmat_score }}</p>
    </div>
    <p class="warning-text">
      <span class="material-icons" style="vertical-align: middle; margin-right: 5px;">warning</span>
      Approving this does not send a direct email to the applicant.
    </p>
    <div class="modal-actions">
      <button class="btn-secondary" @click="cancelAction" :disabled="isProcessing">
        Cancel
      </button>
      <button class="btn-success" @click="confirmAction" :disabled="isProcessing">
        <span v-if="isProcessing" class="spinner"></span>
        {{ isProcessing ? 'Processing...' : 'Yes, Approve' }}
      </button>
    </div>
  </div>
</div>

<!-- Waitlist Confirmation Modal -->
<div v-if="showWaitlistModal" class="modal-overlay">
  <div class="modal-content">
    <div class="modal-icon warning">
      <span class="material-icons">access_time</span>
    </div>
    
    <h3 class="modal-title">{{ modalTitle }}</h3>
    <div class="modal-message" v-html="modalMessage"></div>

    <div class="application-summary">
      <p><strong>Applicant:</strong> {{ application.first_name }} {{ application.last_name }}</p>
      <p><strong>Email:</strong> {{ application.email_address }}</p>
      <p><strong>Current Status:</strong> <span :class="`status-badge status-${getStatusClass(application.application_status)}`">
        {{ application.application_status }}
      </span></p>
    </div>
    <p class="warning-text">
      <span class="material-icons" style="vertical-align: middle; margin-right: 5px;">warning</span>
      The applicant will be placed on the waitlist and may be considered if spots become available.
    </p>
    <div class="modal-actions">
      <button class="btn-secondary" @click="cancelAction" :disabled="isProcessing">
        Cancel
      </button>
      <button class="btn-waitlist" @click="confirmAction" :disabled="isProcessing">
        <span v-if="isProcessing" class="spinner"></span>
        {{ isProcessing ? 'Processing...' : 'Yes, Waitlist' }}
      </button>
    </div>
  </div>
</div>

<!-- Reject Confirmation Modal -->
<div v-if="showRejectModal" class="modal-overlay">
  <div class="modal-content">
    <div class="modal-icon warning">
      <span class="material-icons">close</span>
    </div>
    
    <h3 class="modal-title">{{ modalTitle }}</h3>
    <div class="modal-message" v-html="modalMessage"></div>
    <div class="application-summary">
      <p><strong>Applicant:</strong> {{ application.first_name }} {{ application.last_name }}</p>
      <p><strong>Email:</strong> {{ application.email_address }}</p>
      <p><strong>Current Status:</strong> <span :class="`status-badge status-${getStatusClass(application.application_status)}`">
        {{ application.application_status }}
      </span></p>
    </div>
    <p class="warning-text">
      <span class="material-icons" style="vertical-align: middle; margin-right: 5px;">warning</span>
      Once rejected, the applicant will be notified and cannot reapply for this cycle.
    </p>
    <div class="modal-actions">
      <button class="btn-secondary" @click="cancelAction" :disabled="isProcessing">
        Cancel
      </button>
      <button class="btn-error" @click="confirmAction" :disabled="isProcessing">
        <span v-if="isProcessing" class="spinner"></span>
        {{ isProcessing ? 'Processing...' : 'Yes, Reject' }}
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
    <h3 class="modal-title">Success!</h3>
    <div class="modal-message">
      <p>{{ successMessage }}</p>
      <div class="application-details">
        <p><strong>Applicant:</strong> {{ application.first_name }} {{ application.last_name }}</p>
        <p><strong>New Status:</strong> <span :class="`status-badge status-${getStatusClass(actionType === 'approve' ? 'Accepted' : actionType === 'waitlist' ? 'Waitlist' : 'Rejected')}`">
          {{ actionType === 'approve' ? 'Accepted' : actionType === 'waitlist' ? 'Waitlisted' : 'Rejected' }}
        </span></p>
        <p><strong>Updated:</strong> {{ new Date().toLocaleDateString() }}</p>
      </div>
    </div>
    <div class="modal-actions">
      <button class="btn-secondary" @click="closeSuccessModal">
        Close
      </button>
      <button class="btn-primary" @click="goBack">
        Back to Applications
      </button>
    </div>
  </div>
</div>

<!-- Error Modal -->
<div v-if="showErrorModal" class="modal-overlay">
  <div class="modal-content">
    <div class="modal-icon error">
      <span class="material-icons">error_outline</span>
    </div>
    <h3 class="modal-title">Action Failed</h3>
    <div class="modal-message">
      <p>{{ errorMessage }}</p>
    </div>
    <div class="modal-actions">
      <button class="btn-primary" @click="closeErrorModal">
        Close
      </button>
    </div>
  </div>
</div>
  </AdminIndex>
</template>

<script lang="ts" setup>
import { Inertia } from '@inertiajs/inertia';
import AdminIndex from '../AdminIndex.vue';
import { ref, computed, onMounted } from 'vue'

// Modal states
const showApproveModal = ref(false);
const showWaitlistModal = ref(false);
const showRejectModal = ref(false);
const showSuccessModal = ref(false);
const showErrorModal = ref(false);
const isProcessing = ref(false);
const actionType = ref<'approve' | 'waitlist' | 'reject' | null>(null);
const successMessage = ref('');
const errorMessage = ref('');

onMounted(() => {
  if (!document.querySelector('link[href*="material-icons"]')) {
    const link = document.createElement('link')
    link.href = 'https://fonts.googleapis.com/icon?family=Material+Icons'
    link.rel = 'stylesheet'
    document.head.appendChild(link)
  }
})

const { application } = defineProps<{ application: any }>();

// Computed properties for modal content
const modalTitle = computed(() => {
  switch (actionType.value) {
    case 'approve':
      return 'Approve Application';
    case 'waitlist':
      return 'Waitlist Application';
    case 'reject':
      return 'Reject Application';
    default:
      return 'Confirm Action';
  }
});

const modalMessage = computed(() => {
  const name = `${application.first_name || ''} ${application.last_name || ''}`.trim();
  
  switch (actionType.value) {
    case 'approve':
      return `Are you sure you want to approve the application of <strong>${name}</strong>?`;
    case 'waitlist':
      return `Are you sure you want to waitlist the application of <strong>${name}</strong>?`;
    case 'reject':
      return `Are you sure you want to reject the application of <strong>${name}</strong>?`;
    default:
      return '';
  }
});

// const applicantName = computed(() => {
//   return `${application.first_name || ''} ${application.last_name || ''}`.trim();
// });

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
  const encoded = encodeURIComponent(filePath);
  window.open(`/admin/files/download/${encoded}`, '_blank');
}

function goBack(): void {
  Inertia.get('/admin/applications');
}

// ========== MODAL FUNCTIONS ==========

function approveApplication(): void {
  actionType.value = 'approve';
  showApproveModal.value = true;
}

function waitlistApplication(): void {
  actionType.value = 'waitlist';
  showWaitlistModal.value = true;
}

function rejectApplication(): void {
  actionType.value = 'reject';
  showRejectModal.value = true;
}

function confirmAction(): void {
  isProcessing.value = true;
  
  // Determine status based on action type
  let status = '';
  let actionText = '';
  
  switch (actionType.value) {
    case 'approve':
      status = 'Accepted';
      actionText = 'approved';
      showApproveModal.value = false;
      break;
    case 'waitlist':
      status = 'Waitlist';
      actionText = 'waitlisted';
      showWaitlistModal.value = false;
      break;
    case 'reject':
      status = 'Rejected';
      actionText = 'rejected';
      showRejectModal.value = false;
      break;
    default:
      isProcessing.value = false;
      return;
  }
  
  Inertia.post(`/admin/applications/${application.id}/status`, {
    application_status: status
  }, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      isProcessing.value = false;
      successMessage.value = `Application ${actionText} successfully`;
      showSuccessModal.value = true;
    },
    onError: (errors) => {
      isProcessing.value = false;
      errorMessage.value = errors?.message || `Failed to ${actionText} application. Please try again.`;
      showErrorModal.value = true;
    }
  });
}

function closeSuccessModal(): void {
  showSuccessModal.value = false;
  successMessage.value = '';
}

function closeErrorModal(): void {
  showErrorModal.value = false;
  errorMessage.value = '';
}

function cancelAction(): void {
  showApproveModal.value = false;
  showWaitlistModal.value = false;
  showRejectModal.value = false;
  actionType.value = null;
}
</script>

<style scoped>

/* Modal styles */
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
  z-index: 9999;
  backdrop-filter: blur(4px);
}

.modal-content {
  background: white;
  border-radius: 12px;
  padding: 2rem;
  max-width: 600px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
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
  width: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.5rem;
  color: var(--color-primary);
}

.modal-icon .material-icons {
  font-size: var(--font-size-h3);
  color: var(--color-primary);
}

.modal-title {
  font-size: var(--font-size-h3);
  font-weight: 600;
  color: var(--color-primary);
  margin-bottom: 1rem;
  text-align: center;
  font-family: var(--font-alt);
}

.modal-message {
  color: #4b5563;
  line-height: 1.6;
  margin-bottom: 2rem;
  text-align: center;
  font-size: var(--font-size-p);
}

.modal-message p {
  margin-bottom: 1rem;
}

.application-summary {
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 1rem;
  margin: 1rem 0;
  text-align: left;
}

.application-summary h4 {
  font-size: 1rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 0.5rem;
}

.application-summary ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.application-summary li {
  padding: 0.25rem 0;
  border-bottom: 1px solid #f3f4f6;
}

.warning-text {
  color: #d97706;
  background: #fef3c7;
  padding: 0.75rem;
  border-radius: 6px;
  border-left: 4px solid #f59e0b;
  text-align: left;
  margin-top: 1rem;
}

.application-details {
  background: #f0f9ff;
  border: 1px solid #bae6fd;
  border-radius: 8px;
  padding: 1rem;
  margin: 1rem 0;
  text-align: left;
}

.application-details p {
  margin: 0.5rem 0;
}

.status-pending {
  color: #f59e0b;
  font-weight: 600;
  background: #fef3c7;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.875rem;
}

.next-steps {
  background: #f9fafb;
  border-radius: 8px;
  padding: 1rem;
  margin-top: 1rem;
  text-align: left;
}

.next-steps h5 {
  font-size: 1rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 0.75rem;
}

.next-steps ol {
  padding-left: 1.5rem;
  margin: 0;
}

.next-steps li {
  margin-bottom: 0.5rem;
  color: #4b5563;
}

.modal-actions {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-top: 2rem;
  font-family: var(--font-alt);
}

.btn-error, .btn-waitlist, .btn-success, .btn-secondary {
  padding: var(--space-3) var(--space-5);
  border-radius: 8px;
  font-weight: 500;
  cursor: pointer;
  border: none;
  transition: all 0.2s ease;
  min-width: 150px;
  font-size: var(--font-size-p);
}
.btn-primary:hover:not(:disabled) {
  background: #1d4ed8;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-secondary {
  background: white;
  color: #374151;
  border: 1px solid #d1d5db;
}

.btn-secondary:hover {
  background: #f9fafb;
  border-color: #9ca3af;
}

/* Spinner for loading state */
.spinner {
  display: inline-block;
  width: 1rem;
  height: 1rem;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: white;
  animation: spin 1s ease-in-out infinite;
  margin-right: 0.5rem;
  vertical-align: middle;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* Responsive styles */
@media (max-width: 640px) {
  .modal-content {
    padding: 1.5rem;
    margin: 1rem;
  }
  
  .modal-actions {
    flex-direction: column;
  }
  
  .btn-primary, .btn-secondary {
    width: 100%;
    min-width: unset;
  }
  
  .modal-icon {
    width: 60px;
    height: 60px;
  }
  
  .modal-icon .material-icons {
    font-size: 2rem;
  }
}
.application-detail-container {
  padding: var(--space-4);
  background: var(--color-light-bg);
  min-height: 100vh;
  font-family: var(--font-alt);
  box-sizing: border-box;
}

/* Main Container */
.application-display-container {
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

.application-meta {
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
  text-transform: uppercase;
}

/* Form Fields Display */
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
  font-size: 1.4rem; 
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

.field-value {
  color: var(--color-foreground);
  font-size: 1.4rem; 
  line-height: 1.5;
  word-break: break-word;
}

.yes-no-badge {
  display: inline-block;
  padding: var(--space-1) var(--space-2);
  border-radius: var(--radius-s);
  font-size: 1.2rem; 
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

.conditional-field {
  background: var(--color-light-bg);
  padding: var(--space-2);
  border-radius: var(--radius-m);
  margin-top: var(--space-2);
  border: 1px solid var(--color-light-bg-40);
  font-size: 1.2rem; 
}

/* File Attachments */
.full-width-card .file-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--space-4);
}

.file-attachments {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
}

.file-item {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
  padding: var(--space-2);
  background: var(--color-light-bg);
  border-radius: var(--radius-m);
  border: 1px solid var(--color-neutral);
  transition: all 0.2s ease;
  overflow: hidden;
}

.file-item:hover {
  background: var(--color-bright-green-25);
  border-color: var(--color-primary);
}

.file-item .material-icons {
  color: var(--color-primary);
  font-size: 18px;
}

.file-name {
  flex: 1;
  font-size: 1.4rem; 
  font-weight: 500;
  color: var(--color-foreground);
  word-break: break-all;
}

.file-download {
  padding: var(--space-2);
  background: var(--color-primary);
  color: var(--color-background);
  border: none;
  border-radius: var(--radius-s);
  font-size: 1.4rem; 
  font-weight: 600;
  cursor: pointer;
  width: 100%;
  text-align: center;
  transition: background-color 0.2s ease;
}

.file-download:hover {
  background: var(--color-primary-light);
}

/* Essay Section */
.essay-section {
  background: var(--color-background);
  border-radius: var(--radius-l);
  padding: var(--space-4);
  box-shadow: var(--elevation-1);
  margin-bottom: var(--space-4);
  border: 1px solid var(--color-light-bg-40);
  border-left: 4px solid var(--color-primary);
}

.essay-content {
  line-height: 1.6;
  color: var(--color-foreground);
  white-space: pre-wrap;
  font-size: 1.4rem; 
  text-align: justify;
}

/* Full Width Card */
.full-width-card {
  grid-column: 1 / -1;
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
}

.btn-success:hover {
  background: var(--color-primary-light);
}

.btn-waitlist {
  background: var(--color-blue-green-15);
  color: var(--color-primary);
  border: 1px solid var(--color-primary);
}

.btn-waitlist:hover {
  background: var(--color-primary);
  color: var(--color-background);
}

.btn-waitlist:hover .material-icons {
  color: var(--color-background);
}

.btn-error {
  background: var(--color-error);
  color: var(--color-background);
}

.btn-error:hover {
  background: #c0392b;
}

/* TABLET BREAKPOINT (768px and up) */
@media (min-width: 768px) {
  .application-detail-container {
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
  }
  
  .field-row {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: var(--space-4);
    align-items: start;
  }
  
  .field-label {
    font-size: var(--font-size-p);
  }
  
  .field-value {
    font-size: var(--font-size-p);
  }
  
  .yes-no-badge {
    font-size: var(--font-size-p);
  }
  
  .conditional-field {
    font-size: var(--font-size-p);
  }
  
  .full-width-card .file-grid {
    grid-template-columns: 1fr 1fr;
    gap: var(--space-6);
  }
  
  .file-item {
    flex-direction: row;
    align-items: center;
    padding: var(--space-3);
  }
  
  .file-item .material-icons {
    font-size: 20px;
  }
  
  .file-name {
    font-size: var(--font-size-p);
  }
  
  .file-download {
    font-size: var(--font-size-p);
    width: auto;
    min-width: 100px;
  }
  
  .essay-section {
    padding: var(--space-5);
    margin-bottom: var(--space-5);
  }
  
  .essay-content {
    font-size: var(--font-size-p);
  }
  
  .action-buttons {
    grid-template-columns: repeat(4, 1fr);
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
  .application-detail-container {
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
  
  .essay-section {
    padding: var(--space-6);
    margin-bottom: var(--space-6);
    box-shadow: var(--elevation-2);
  }
  
  .action-buttons {
    padding-top: var(--space-6);
  }
  
  .btn {
    padding: var(--space-3) var(--space-5);
  }
}

/* Large Desktop (1400px and up) */
@media (min-width: 1400px) {
  .display-content {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .full-width-card {
    grid-column: 1 / -1;
  }
}

/* Extra Small Screens (less than 375px) */
@media (max-width: 374px) {
  .application-detail-container {
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
  .info-card,
  .essay-section {
    padding: var(--space-3);
  }
  
  .field-value {
    font-size: 1.4rem; 
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
.info-card,
.essay-section,
.file-item {
  max-width: 100%;
  overflow-x: hidden;
}

/* Ensure text doesn't overflow */
.field-value,
.file-name,
.essay-content {
  overflow-wrap: break-word;
  word-wrap: break-word;
  hyphens: auto;
}
</style>
<script lang="ts" setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { watch, computed } from 'vue';  

// ref declarations 
const bachelorsTranscriptInput = ref<HTMLInputElement | null>(null);
const graduateTranscriptInput = ref<HTMLInputElement | null>(null);
const nmatCertificationInput = ref<HTMLInputElement | null>(null);

// Step management
const currentStep = ref(1);
const totalSteps = 5;

// Router for submission
const router = useRouter();

// Props
interface Props {
  userEmail: string;
}
const props = defineProps<Props>();

// TypeScript interface for the full form
interface ApplicationForm {
  emailAddress: string;
  verifyEmailAddress: string;
  firstName: string;
  middleName: string;
  lastName: string;
  dateOfBirth: string;
  citizenship: string;
  permanentHomeAddress: string;
  municipality: string;
  province: string;
  zipCode: string;
  activeGmailAccount: string;
  cellphoneNumber: string;
  civilStatus: string;
  sex: string;
  religion: string;
  indigenousGroup: string;
  indigenousGroupSpecify?: string;
  physicalDisability: string;
  physicalDisabilitySpecify?: string;

  bachelorsDegree: string;
  nameOfSchoolBachelorsDegree: string;
  gwaforBachelorsDegree: string;
  bachelorsTranscriptOfRecords: File[];
  graduateStudies: string;
  nameOfSchoolGraduateStudies: string;
  dateOfGraduation: string;
  graduateStudiesTranscriptOfRecords?: File[];

  fatherFirstName: string;
  fatherMiddleName: string;
  fatherLastName: string;
  fatherOccupation: string;
  motherFirstName: string;
  motherMiddleName: string;
  motherLastName: string;
  motherOccupation: string;
  guardianFirstName: string;
  guardianMiddleName: string;
  guardianLastName: string;
  guardianOccupation: string;
  combinedAnnualIncomeOfFamily: string;
  communityHealthVolunteer: string;
  communityHealthOrganization?: string;

  nmatNationalMedicalAdmissionTestScore: string;
  nmatNationalMedicalAdmissionTestCertification: File[];
  dateOfNmatNationalMedicalAdmissionTestExamination: string;

  whyChooseSouthernLuzonStateUniversity: string;
}

// Initialize reactive form
const form = reactive<ApplicationForm>({
  emailAddress: props.userEmail,
  verifyEmailAddress: '',
  firstName: '',
  middleName: '',
  lastName: '',
  dateOfBirth: '',
  citizenship: '',
  permanentHomeAddress: '',
  municipality: '',
  province: '',
  zipCode: '',
  activeGmailAccount: '',
  cellphoneNumber: '',
  civilStatus: '',
  sex: '',
  religion: '',
  indigenousGroup: '',
  indigenousGroupSpecify: '',
  physicalDisability: '',
  physicalDisabilitySpecify: '',

  bachelorsDegree: '',
  nameOfSchoolBachelorsDegree: '',
  gwaforBachelorsDegree: '',
  bachelorsTranscriptOfRecords: [],
  graduateStudies: '',
  nameOfSchoolGraduateStudies: '',
  dateOfGraduation: '',
  graduateStudiesTranscriptOfRecords: [],

  fatherFirstName: '',
  fatherMiddleName: '',
  fatherLastName: '',
  fatherOccupation: '',
  motherFirstName: '',
  motherMiddleName: '',
  motherLastName: '',
  motherOccupation: '',
  guardianFirstName: '',
  guardianMiddleName: '',
  guardianLastName: '',
  guardianOccupation: '',
  combinedAnnualIncomeOfFamily: '',
  communityHealthVolunteer: '',
  communityHealthOrganization: '',

  nmatNationalMedicalAdmissionTestScore: '',
  nmatNationalMedicalAdmissionTestCertification: [],
  dateOfNmatNationalMedicalAdmissionTestExamination: '',

  whyChooseSouthernLuzonStateUniversity: '',
});

// Reactive error object
const errors = reactive<Record<string, string>>({});

// Display name mapping
const fieldDisplayNames: Record<keyof ApplicationForm, string> = {
  firstName: 'First Name',
  middleName: 'Middle Name',
  lastName: 'Last Name (Include Suffix if applicable)',
  emailAddress: 'Current Email Address',
  verifyEmailAddress: 'Verify Email Address',
  dateOfBirth: 'Date of Birth',
  citizenship: 'Citizenship',
  permanentHomeAddress: 'Permanent Home Address',
  municipality: 'Municipality',
  province: 'Province',
  zipCode: 'ZIP Code',
  activeGmailAccount: 'Active Gmail Account',
  cellphoneNumber: 'Cellphone Number',
  civilStatus: 'Civil Status',
  sex: 'Sex',
  religion: 'Religion',
  indigenousGroup: 'Are you a member of any indigenous group?',
  indigenousGroupSpecify: 'Please specify (E.g. Aeta, Agta, Badjao, Mangyan, etc.)',
  physicalDisability: 'Do you have any physical disability or condition?',
  physicalDisabilitySpecify: 'Please specify',

  bachelorsDegree: 'Bachelors Degree (Do not abbreviate)',
  nameOfSchoolBachelorsDegree: "Name of School attended for the Bachelor's Degree",
  gwaforBachelorsDegree: "GWA for Bachelor's Degree",
  bachelorsTranscriptOfRecords: "Bachelor's Transcript of Records",
  graduateStudies: 'Graduate Studies (Optional, keep empty if not applicable)',
  nameOfSchoolGraduateStudies: 'Name of School attended for Graduate Studies (Optional, keep empty if not applicable)',
  dateOfGraduation: 'Date of Graduation (use format mm/dd/yyyy)',
  graduateStudiesTranscriptOfRecords: 'Graduate Transcript of Records',

  fatherFirstName: "Father's First Name",
  fatherMiddleName: "Father's Middle Name",
  fatherLastName: "Father's Last Name (Include Suffix if applicable)",
  fatherOccupation: "Father's Occupation",
  motherFirstName: "Mother's First Name",
  motherMiddleName: "Mother's Middle Name",
  motherLastName: "Mother's Last Name (Include Suffix if applicable)",
  motherOccupation: "Mother's Occupation",
  guardianFirstName: "Guardian's First Name",
  guardianMiddleName: "Guardian's Middle Name",
  guardianLastName: "Guardian's Last Name (Include Suffix if applicable)",
  guardianOccupation: "Guardian's Occupation",
  combinedAnnualIncomeOfFamily: 'Combined Annual Income',
  communityHealthVolunteer: 'Are any of your parents a Community Health Volunteer?',
  communityHealthOrganization: 'Under what organization?',

  nmatNationalMedicalAdmissionTestScore: 'NMAT Score',
  nmatNationalMedicalAdmissionTestCertification: 'NMAT Certification',
  dateOfNmatNationalMedicalAdmissionTestExamination: 'NMAT Examination Date',

  whyChooseSouthernLuzonStateUniversity: 'Why Choose Southern Luzon State University?',
};

// Step validation fields
const stepFields: Record<number, Array<keyof ApplicationForm>> = {
  1: [
    'emailAddress', 'verifyEmailAddress', 'lastName', 'middleName', 'firstName',
    'dateOfBirth', 'citizenship', 'permanentHomeAddress', 'municipality',
    'province', 'zipCode', 'activeGmailAccount', 'cellphoneNumber', 'civilStatus',
    'sex', 'religion'
  ],
  2: [
    'bachelorsDegree', 'nameOfSchoolBachelorsDegree', 'gwaforBachelorsDegree', 'bachelorsTranscriptOfRecords',
    'graduateStudies', 'nameOfSchoolGraduateStudies', 'dateOfGraduation', 'graduateStudiesTranscriptOfRecords'
  ],
  3: [
    'fatherLastName', 'fatherMiddleName', 'fatherFirstName', 'fatherOccupation',
    'motherLastName', 'motherMiddleName', 'motherFirstName', 'motherOccupation',
    'guardianLastName', 'guardianMiddleName', 'guardianFirstName', 'guardianOccupation'
  ],
  4: [
    'nmatNationalMedicalAdmissionTestScore',
    'nmatNationalMedicalAdmissionTestCertification',
    'dateOfNmatNationalMedicalAdmissionTestExamination'
  ],
  5: ['whyChooseSouthernLuzonStateUniversity'],
};

// File fields
const fileFields: Array<keyof ApplicationForm> = [
  'bachelorsTranscriptOfRecords',
  'graduateStudiesTranscriptOfRecords',
  'nmatNationalMedicalAdmissionTestCertification',
];


const onInput = (field: keyof ApplicationForm) => {
  // Clear error immediately if field has a value
  const value = form[field];

  if (typeof value === 'string' && value.trim() !== '') {
    delete errors[field];
  }

  // For yes/no radios, clear "specify" error when NO is selected
  if (field === 'indigenousGroup' && form.indigenousGroup === 'NO') {
    form.indigenousGroupSpecify = '';
    delete errors.indigenousGroupSpecify;
  }

  if (field === 'physicalDisability' && form.physicalDisability === 'NO') {
    form.physicalDisabilitySpecify = '';
    delete errors.physicalDisabilitySpecify;
  }

  if (field === 'communityHealthVolunteer' && form.communityHealthVolunteer === 'NO') {
    form.communityHealthOrganization = '';
    delete errors.communityHealthOrganization;
  }
};

watch(
  () => form.indigenousGroup,
  (value) => {
    if (value) delete errors.indigenousGroup;
    if (value === 'NO') {
      form.indigenousGroupSpecify = '';
      delete errors.indigenousGroupSpecify;
    }
  }
);

watch(
  () => form.physicalDisability,
  (value) => {
    if (value) delete errors.physicalDisability;
    if (value === 'NO') {
      form.physicalDisabilitySpecify = '';
      delete errors.physicalDisabilitySpecify;
    }
  }
);

watch(
  () => form.communityHealthVolunteer,
  (value) => {
    if (value) delete errors.communityHealthVolunteer;
    if (value === 'NO') {
      form.communityHealthOrganization = '';
      delete errors.communityHealthOrganization;
    }
  }
);

const allowedExtensions = ["jpg", "jpeg", "png", "doc", "docx", "pdf"];

const handleFileUpload = (event: Event, field: keyof ApplicationForm) => {
  const target = event.target as HTMLInputElement;
  if (!target.files) return;

  const files = Array.from(target.files);

  // Filter out files with invalid extensions
  const validFiles = files.filter(file => {
    const ext = file.name.split('.').pop()?.toLowerCase();
    return ext && allowedExtensions.includes(ext);
  });

  if (validFiles.length !== files.length) {
    alert("Some files were rejected. Allowed formats: jpg, png, doc, docx, pdf.");
  }

  form[field] = validFiles as any;

  if (validFiles.length > 0) {
    delete errors[field];
  } else {
    errors[field] = "Please upload a valid file.";
  }
};

// Validation per step
const validateStep = (step: number) => {
  let valid = true;
  const fields = stepFields[step];

  // Default validation for most steps
  fields.forEach((field) => {
    // Skip graduate studies fields here — handled separately below
    if (
      step === 2 &&
      [
        'graduateStudies',
        'nameOfSchoolGraduateStudies',
        'dateOfGraduation',
        'graduateStudiesTranscriptOfRecords'
      ].includes(field)
    ) {
      return;
    }

    if (fileFields.includes(field)) {
      if (!form[field] || (form[field] as File[]).length === 0) {
        errors[field] = `${fieldDisplayNames[field]} is required.`;
        valid = false;
      } else {
        delete errors[field];
      }
    } else {
      const value = form[field];
      if (!value || (typeof value === 'string' && value.trim() === '')) {
        errors[field] = `${fieldDisplayNames[field]} is required.`;
        valid = false;
      } else {
        delete errors[field];
      }
    }
  });

  // ⭐⭐⭐ STEP 2: Graduate Studies OPTIONAL ⭐⭐⭐
  if (step === 2) {
    const hasGraduateStudies =
      form.graduateStudies.trim() !== '' ||
      form.nameOfSchoolGraduateStudies.trim() !== '' ||
      form.dateOfGraduation.trim() !== '' ||
      form.graduateStudiesTranscriptOfRecords.length > 0;

    if (!hasGraduateStudies) {
      // User did NOT enter anything → remove errors and skip requirements
      delete errors.graduateStudies;
      delete errors.nameOfSchoolGraduateStudies;
      delete errors.dateOfGraduation;
      delete errors.graduateStudiesTranscriptOfRecords;
    } else {
      // If they enter ANYTHING, all fields become required
      if (form.graduateStudies.trim() === '') {
        errors.graduateStudies = "Graduate Studies is required.";
        valid = false;
      }
      if (form.nameOfSchoolGraduateStudies.trim() === '') {
        errors.nameOfSchoolGraduateStudies = "Name of School for Graduate Studies is required.";
        valid = false;
      }
      if (form.dateOfGraduation.trim() === '') {
        errors.dateOfGraduation = "Date of Graduation is required.";
        valid = false;
      }
      if (form.graduateStudiesTranscriptOfRecords.length === 0) {
        errors.graduateStudiesTranscriptOfRecords = "Graduate Studies Transcript of Records is required.";
        valid = false;
      }
    }
  }
// Step 1: Indigenous Group Specify
if (form.indigenousGroup === 'YES') {
  if (!form.indigenousGroupSpecify || form.indigenousGroupSpecify.trim() === '') {
    errors.indigenousGroupSpecify = "Please specify the indigenous group.";
    valid = false;
  } else {
    delete errors.indigenousGroupSpecify;
  }
}

// Step 1: Physical Disability Specify
if (form.physicalDisability === 'YES') {
  if (!form.physicalDisabilitySpecify || form.physicalDisabilitySpecify.trim() === '') {
    errors.physicalDisabilitySpecify = "Please specify the disability.";
    valid = false;
  } else {
    delete errors.physicalDisabilitySpecify;
  }
}

// Step 1: Community Health Volunteer Organization
if (form.communityHealthVolunteer === 'YES') {
  if (!form.communityHealthOrganization || form.communityHealthOrganization.trim() === '') {
    errors.communityHealthOrganization = "Please specify the organization.";
    valid = false;
  } else {
    delete errors.communityHealthOrganization;
  }
}

  // Step 1: Email match
  if (step === 1 && form.emailAddress !== form.verifyEmailAddress) {
    errors.verifyEmailAddress = "Email and Verify Email must match.";
    valid = false;
  }

  return valid;
};
// Function to remove individual files
const removeFile = (field: keyof ApplicationForm, index: number) => {
  (form[field] as File[]).splice(index, 1);
};

// Computed property to check if graduate studies section should be required
const hasGraduateStudies = computed(() => {
  return form.graduateStudies.trim() !== '' || 
         form.nameOfSchoolGraduateStudies.trim() !== '' || 
         form.dateOfGraduation.trim() !== '' || 
         (form.graduateStudiesTranscriptOfRecords && form.graduateStudiesTranscriptOfRecords.length > 0);
});

// Navigation
const nextStep = () => {
  if (validateStep(currentStep.value) && currentStep.value < totalSteps) {
    currentStep.value++;
  }
};

const prevStep = () => {
  if (currentStep.value > 1) currentStep.value--;
};

// Form submission
const submitForm = () => {
  if (validateStep(currentStep.value)) {
    console.log('Form data:', form);
  }
};
</script>

<script lang="ts">
 export default {
      name: "ApplicantForm"
  }
</script>
<template>
  <form class="form-container" @submit.prevent="submitForm">
    <!-- Step 1 -->
    <div class="form-header">
        <h1>Admission for College of medicine</h1>
        <p>Disclamer: Data gathered will be used as an assessment for the offering of Doctor of Medicine in the Academic Year <!-- {{ academicYear }} -->  20XX-20XX at the Southern Luzon State University (Lucena City, Quezon) and other internal reports & legitimate academic interests.</p>
    </div>
    <div class="form-consent">
      <div class="form-consent__title">DATA PRIVACY CONSENT/AGREEMENT</div>
      <p>
       Southern Luzon State University aims to comply with the Republic Act No. 10173, otherwise known as the Data Privacy Act of 2012. Hence, it recognizes the responsibility of safeguarding personal privacy through ensuring that any piece of information obtained from SLSU-provided platforms is entered and stored within the University's database system and shall only be accessed by authorized SLSU personnel. Moreover, the obtained information shall strictly be used as permitted or required by law to pursue legitimate interests as an academe.
      </p>
    </div>
    <div class="form-consent">
      <div class="form-consent__title required">APPLICANT'S CONSENT:</div>
      <div class="form-consent__checkbox-container">
          <input class="form-consent__checkbox" name="applicantConsent" id="applicantConsent" required type="checkbox">
          <div class="consent-text">
            <label for="applicantConsent" id="applicantConsentLabel">
              I hereby give my consent that my personal data in the custody of SLSU may be used by the University for internal reports and for other legitimate academic interests. Further, I hereby affirm my right to be informed, object to processing, access and rectify, suspend or withdraw my personal data, and be indemnified in case of damages pursuant to the provisions of the Data Privacy Act of 2012.
            </label>
            <p class="consent-warning">
              By agreeing to this, I acknowledge that the accuracy of the information I provide is my responsibility. I understand that submitting incorrect or incomplete data may affect my academic records and other University processes. I certify that all information provided is true and correct to the best of my knowledge.
            </p>
          </div>
         
      </div>
      <p v-if="errors.applicantConsent" style="color: red">
        {{ errors.applicantConsent }}
      </p>
    </div>
    
    <div v-if="currentStep === 1">
      <h2 class="section-title">Personal Information</h2>
      <div  class="input-container" v-for="field in stepFields[1]" :key="field">
        <label :for="field" class="required">{{ fieldDisplayNames[field] }}</label>

        <!-- Sex Dropdown -->
        <select v-if="field === 'sex'" id="sex" v-model="form.sex">
          <option value="">Select Sex</option>
          <option value="MALE">MALE</option>
          <option value="FEMALE">FEMALE</option>
        </select>

        <!-- Civil Status Dropdown -->
        <select v-else-if="field === 'civilStatus'" id="civilStatus" v-model="form.civilStatus">
          <option value="">Select Civil Status</option>
          <option value="MARRIED">MARRIED</option>
          <option value="SINGLE">SINGLE</option>
          <option value="WIDOWED">WIDOWED</option>
          <option value="DIVORCED">DIVORCED</option>
        </select>

        <!-- Email field: prefilled and disabled -->
        <input
          v-else-if="field === 'emailAddress'"
          :id="field"
          v-model="form.emailAddress"
          disabled
        />
      <!-- Date of Birth -->
        <input
          v-else-if="field === 'dateOfBirth'"
          type="date"
          :id="field"
          v-model="form.dateOfBirth"
          @input="onInput(field)"
        />
        <!-- Default input for other fields -->
        <input
          v-else
          :id="field"
          v-model="form[field]"
          @input="onInput(field)"
        />

        <p v-if="errors[field]" style="color: red">{{ errors[field] }}</p>
      </div>
    
    
         <!-- Indigenous Group Radio -->
      <div class="radio-group-container">
        <label class="required">{{ fieldDisplayNames.indigenousGroup }}</label>

        <div class="radio-group">
          <label class="radio-group__label">
            <input type="radio" value="YES" v-model="form.indigenousGroup" class="radio-button" />
            YES
          </label>

          <label class="radio-group__label">
            <input type="radio" value="NO" v-model="form.indigenousGroup" class="radio-button" />
            NO
          </label>
        </div>

        <p v-if="errors.indigenousGroup" style="color:red;">
          {{ errors.indigenousGroup }}
        </p>
        <!-- Conditional Specify Field -->
        <div v-if="form.indigenousGroup === 'YES'">
          <label class="required">{{ fieldDisplayNames.indigenousGroupSpecify }}</label>
          <input
            id="indigenousGroupSpecify"
            v-model="form.indigenousGroupSpecify"
            @input="onInput('indigenousGroupSpecify')"
          />
          <p v-if="errors.indigenousGroupSpecify" style="color:red;">
            {{ errors.indigenousGroupSpecify }}
          </p>
        </div>
      </div>

     
      <div class="radio-group-container">
        <label class="required">{{ fieldDisplayNames.physicalDisability }}</label>

        <div class="radio-group">
          <label class="radio-group__label">
            <input type="radio" value="YES" v-model="form.physicalDisability" class="radio-button" />
            YES
          </label>

          <label class="radio-group__label"> 
            <input type="radio" value="NO" v-model="form.physicalDisability" class="radio-button" />
            NO
          </label>
        </div>

        <p v-if="errors.physicalDisability" style="color:red;">
          {{ errors.physicalDisability }}
        </p>
        <div
        v-if="form.physicalDisability === 'YES'"
      >
        <label class="required">{{ fieldDisplayNames.physicalDisabilitySpecify }}</label>
        <input
          id="physicalDisabilitySpecify"
          v-model="form.physicalDisabilitySpecify"
          @input="onInput('physicalDisabilitySpecify')"
        />

        <p v-if="errors.physicalDisabilitySpecify" style="color:red;">
          {{ errors.physicalDisabilitySpecify }}
        </p>
      </div>
      </div>

      
      <div class="radio-group-container">
        <label class="required">{{ fieldDisplayNames.communityHealthVolunteer }}</label>

        <div class="radio-group">
          <label class="radio-group__label">
            <input type="radio" value="YES" v-model="form.communityHealthVolunteer" class="radio-button" />
            YES
          </label>

          <label class="radio-group__label">
            <input type="radio" value="NO" v-model="form.communityHealthVolunteer" class="radio-button" />
            NO
          </label>
        </div>

        <p v-if="errors.communityHealthVolunteer" style="color:red;">
          {{ errors.communityHealthVolunteer }}
        </p>

      <!-- Conditional Org Input -->
      <div
        v-if="form.communityHealthVolunteer === 'YES'"
      >
        <label class="required">{{ fieldDisplayNames.communityHealthOrganization }}</label>
        <input
          id="communityHealthOrganization"
          v-model="form.communityHealthOrganization"
          @input="onInput('communityHealthOrganization')"
        />

        <p v-if="errors.communityHealthOrganization" style="color:red;">
          {{ errors.communityHealthOrganization }}
        </p>
      </div>
      </div>
    </div>
    <!-- Step 2 -->
    <div v-if="currentStep === 2">
      <h2 class="section-title">Educational Background</h2>

      <!-- BACHELOR'S DEGREE TEXT FIELDS -->
      <div
        class="input-container"
        v-for="field in stepFields[2].filter(f =>
          !fileFields.includes(f) &&
          ![
            'graduateStudies',
            'nameOfSchoolGraduateStudies',
            'graduateStudiesTranscriptOfRecords',
            'dateOfGraduation'
          ].includes(f)
        )"
        :key="field"
      >
        <label :for="field" class="required">
          {{ fieldDisplayNames[field as keyof ApplicationForm] }}
        </label>

        <input
          :id="field"
          v-model="form[field as keyof ApplicationForm]"
          @input="onInput(field as keyof ApplicationForm)"
        />

        <p v-if="errors[field as keyof ApplicationForm]" style="color: red">
          {{ errors[field as keyof ApplicationForm] }}
        </p>
      </div>

      <!-- BACHELOR'S TRANSCRIPT FILE UPLOAD -->
      <div class="input-container">
        <label for="bachelorsTranscriptOfRecords" class="required">
          {{ fieldDisplayNames.bachelorsTranscriptOfRecords }}
        </label>

        <div class="upload-minimal">
          <button type="button" class="upload-minimal-btn" @click="bachelorsTranscriptInput?.click()">
            Upload Bachelor's Transcript
          </button>

          <input
            ref="bachelorsTranscriptInput"
            type="file"
            id="bachelorsTranscriptOfRecords"
            class="file-input-hidden"
            accept=".jpg,.jpeg,.png,.doc,.docx,.pdf"
            multiple
            @change="handleFileUpload($event, 'bachelorsTranscriptOfRecords')"
          />

          <div class="minimal-preview" v-if="form.bachelorsTranscriptOfRecords.length > 0">
            <div v-for="(file, index) in form.bachelorsTranscriptOfRecords" :key="index" class="minimal-item">
              <div class="minimal-name">{{ file.name }}</div>
              <button type="button" class="file-remove" @click="removeFile('bachelorsTranscriptOfRecords', index)">✕</button>
            </div>
          </div>

          <div v-else class="empty-state">
            <div class="empty-icon">📁</div>
            <div class="empty-text">No transcript files uploaded</div>
            <div class="empty-subtext">Bachelor's transcript will be shown here</div>
          </div>
        </div>

        <p v-if="errors.bachelorsTranscriptOfRecords" style="color: red">
          {{ errors.bachelorsTranscriptOfRecords }}
        </p>
      </div>

      <!-- GRADUATE STUDIES TEXT FIELD -->
      <div class="input-container">
        <label for="graduateStudies" class="required">
          {{ fieldDisplayNames.graduateStudies }}
        </label>

        <input
          id="graduateStudies"
          v-model="form.graduateStudies"
          @input="onInput('graduateStudies')"
        />

        <p v-if="errors.graduateStudies" style="color: red">
          {{ errors.graduateStudies }}
        </p>
      </div>

      <!-- GRADUATE STUDIES NAME OF SCHOOL (VISIBLE ONLY IF graduateStudies IS FILLED) -->
      <div class="input-container" v-if="hasGraduateStudies">
        <label for="nameOfSchoolGraduateStudies" class="required">
          {{ fieldDisplayNames.nameOfSchoolGraduateStudies }}
        </label>

        <input
          id="nameOfSchoolGraduateStudies"
          v-model="form.nameOfSchoolGraduateStudies"
          @input="onInput('nameOfSchoolGraduateStudies')"
        />

        <p v-if="errors.nameOfSchoolGraduateStudies" style="color: red">
          {{ errors.nameOfSchoolGraduateStudies }}
        </p>
      </div>

      <!-- GRADUATE STUDIES DATE OF GRADUATION -->
      <div class="input-container" v-if="hasGraduateStudies">
        <label for="dateOfGraduation" class="required">
          {{ fieldDisplayNames.dateOfGraduation }}
        </label>

        <input type="date" id="dateOfGraduation" v-model="form.dateOfGraduation" />

        <p v-if="errors.dateOfGraduation" style="color: red">
          {{ errors.dateOfGraduation }}
        </p>
      </div>

      <!-- GRADUATE STUDIES TRANSCRIPT FILE UPLOAD -->
      <div class="input-container" v-if="hasGraduateStudies">
        <label for="graduateStudiesTranscriptOfRecords" class="required">
          {{ fieldDisplayNames.graduateStudiesTranscriptOfRecords }}
        </label>

        <div class="upload-minimal">
          <button type="button" class="upload-minimal-btn" @click="graduateTranscriptInput?.click()">
            Upload Graduate Transcript
          </button>

          <input
            ref="graduateTranscriptInput"
            type="file"
            id="graduateStudiesTranscriptOfRecords"
            class="file-input-hidden"
            accept=".jpg,.jpeg,.png,.doc,.docx,.pdf"
            multiple
            @change="handleFileUpload($event, 'graduateStudiesTranscriptOfRecords')"
          />

          <div class="minimal-preview" v-if="form.graduateStudiesTranscriptOfRecords.length > 0">
            <div v-for="(file, index) in form.graduateStudiesTranscriptOfRecords" :key="index" class="minimal-item">
              <div class="minimal-name">{{ file.name }}</div>
              <button type="button" class="file-remove" @click="removeFile('graduateStudiesTranscriptOfRecords', index)">✕</button>
            </div>
          </div>

          <div v-else class="empty-state">
            <div class="empty-icon">📁</div>
            <div class="empty-text">No files uploaded yet</div>
            <div class="empty-subtext">Graduate transcript files will appear here</div>
          </div>
        </div>

        <p v-if="errors.graduateStudiesTranscriptOfRecords" style="color: red">
          {{ errors.graduateStudiesTranscriptOfRecords }}
        </p>
      </div>
    </div>

    <!-- Step 3 -->
    <div v-if="currentStep === 3">
      <h2 class="section-title">Parents Information</h2>
      <div class="input-container" v-for="field in stepFields[3]" :key="field" style="margin-bottom: 10px;">
        <label :for="field" class="required">{{ fieldDisplayNames[field] }}</label>
        <input :id="field" v-model="form[field]" />
        <p v-if="errors[field]" style="color: red">{{ errors[field] }}</p>
      </div>
        <!-- Combined Annual Income Radio Group -->
      <div class="radio-group-container">
        <label class="required">{{ fieldDisplayNames.combinedAnnualIncomeOfFamily }}</label>

        <div class="radio-group">
          <label class="radio-group__label">
            <input type="radio" value="P 00.00 - P 300 000.00" v-model="form.combinedAnnualIncomeOfFamily" />
            P 00.00 - P 300 000.00
          </label>

          <label class="radio-group__label">
            <input type="radio" value="P 301 000.00 - P 450 000.00" v-model="form.combinedAnnualIncomeOfFamily" />
            P 301 000.00 - P 450 000.00
          </label>

          <label class="radio-group__label">
            <input type="radio" value="P 451 000.00 - P 700 000.00" v-model="form.combinedAnnualIncomeOfFamily" />
            P 451 000.00 - P 700 000.00
          </label>

          <label class="radio-group__label">
            <input type="radio" value="P 701 000.00 - above" v-model="form.combinedAnnualIncomeOfFamily" />
            P 701 000.00 - above
          </label>
        </div>

        <p v-if="errors.combinedAnnualIncomeOfFamily" style="color: red;">
          {{ errors.combinedAnnualIncomeOfFamily }}
        </p>
      </div>

    </div>

    <!-- Step 4 -->
   <div v-if="currentStep === 4">
    <h2 class="section-title">NMAT</h2>
    
    <!-- NMAT SCORE - Keep this text input -->
    <div class="input-container">
      <label for="nmatNationalMedicalAdmissionTestScore" class="required">{{ fieldDisplayNames.nmatNationalMedicalAdmissionTestScore }}</label>
      <input 
        id="nmatNationalMedicalAdmissionTestScore" 
        v-model="form.nmatNationalMedicalAdmissionTestScore" 
        @input="onInput('nmatNationalMedicalAdmissionTestScore')" 
      />
      <p v-if="errors.nmatNationalMedicalAdmissionTestScore" style="color: red">{{ errors.nmatNationalMedicalAdmissionTestScore }}</p>
    </div>

    <!-- NMAT EXAMINATION DATE - Keep this date input -->
    <div class="input-container">
      <label for="dateOfNmatNationalMedicalAdmissionTestExamination" class="required">{{ fieldDisplayNames.dateOfNmatNationalMedicalAdmissionTestExamination }}</label>
      <input 
        type="date" 
        id="dateOfNmatNationalMedicalAdmissionTestExamination" 
        v-model="form.dateOfNmatNationalMedicalAdmissionTestExamination" 
      />
      <p v-if="errors.dateOfNmatNationalMedicalAdmissionTestExamination" style="color: red">{{ errors.dateOfNmatNationalMedicalAdmissionTestExamination }}</p>
    </div>

    <!-- NMAT CERTIFICATION FILE UPLOAD - NEW MINIMAL STYLE -->
    <div class="input-container">
      <label for="nmatNationalMedicalAdmissionTestCertification" class="required">{{ fieldDisplayNames.nmatNationalMedicalAdmissionTestCertification }}</label>
      <div class="upload-minimal">
        <!-- Upload button that triggers hidden file input -->
        <button type="button" class="upload-minimal-btn" @click="nmatCertificationInput?.click()">
          Upload NMAT Certification
        </button>
        <!-- Hidden file input (replaces old visible file input) -->
        <input 
          ref="nmatCertificationInput"
          type="file" 
          id="nmatNationalMedicalAdmissionTestCertification"
          class="file-input-hidden"
          accept=".jpg,.jpeg,.png,.doc,.docx,.pdf"
          multiple
          @change="handleFileUpload($event, 'nmatNationalMedicalAdmissionTestCertification')"
        />
        
        <!-- File preview area - shows uploaded files as minimal list -->
        <div class="minimal-preview" v-if="form.nmatNationalMedicalAdmissionTestCertification.length > 0">
          <div v-for="(file, index) in form.nmatNationalMedicalAdmissionTestCertification" :key="index" class="minimal-item">
            <div class="minimal-name">{{ file.name }}</div>
            <!-- Remove file button -->
            <button type="button" class="file-remove" @click="removeFile('nmatNationalMedicalAdmissionTestCertification', index)">✕</button>
          </div>
        </div>
        <!-- Empty state when no files are uploaded -->
        <div v-else class="empty-state">
          <div class="empty-icon">📁</div>
          <div class="empty-text">No certification files uploaded</div>
          <div class="empty-subtext">NMAT certification will be shown here</div>
        </div>
      </div>
      <p v-if="errors.nmatNationalMedicalAdmissionTestCertification" style="color: red">{{ errors.nmatNationalMedicalAdmissionTestCertification }}</p>
    </div>
  </div>

    <!-- Step 5 -->
    <div v-if="currentStep === 5">
      <h2 class="section-title">Essay</h2>
      <div class="input-container">
        <label for="whyChooseSouthernLuzonStateUniversity" class="required">{{ fieldDisplayNames.whyChooseSouthernLuzonStateUniversity }}</label>
        <textarea
          id="whyChooseSouthernLuzonStateUniversity"
          v-model="form.whyChooseSouthernLuzonStateUniversity"
          rows="6"
          style="width: 100%;"
        ></textarea>
        <p v-if="errors.whyChooseSouthernLuzonStateUniversity" style="color: red">{{ errors.whyChooseSouthernLuzonStateUniversity }}</p>
      </div>
      
    </div>

    <!-- Navigation Buttons -->
    <div class="button-container">
      <button class="navigation-button" type="button" @click="prevStep" :disabled="currentStep === 1">Previous</button>
      <button class="navigation-button" type="button" @click="nextStep" v-if="currentStep < totalSteps">Next</button>
      <button class="button-submit" type="submit" v-else>Submit</button>
    </div>
  </form>
</template>


<style scoped>
label {
    display: block;
    margin-bottom: var(--space-3);
    font-weight: 500;
    color: var(--color-foreground);
    font-size: var(--font-size-p);
    font-family: var(--font-sans);
}
.required::after {
  content: ' *';
  color: red;
}
input,
select,
textarea {
    width: 100%;
    padding: var(--space-4) var(--space-5);
    border: 0.1rem solid var(--color-neutral);
    border-radius: var(--radius-m);
    font-size: var(--font-size-p);
    background: var(--color-background);
    transition: all 0.3s ease;
    font-family: var(--font-alt);
}
select{
  padding-left: 2rem;
}
input:focus,
select:focus,
textarea:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 0.3rem var(--color-primary-20);
}
button {
  padding: var(--space-2) var(--space-4);
  box-shadow: var(--elevation-1);
  cursor: pointer;
  border-radius: var(--radius-m);
  color: var(--color-foreground);
  font-size: var(--font-size-p);
}
.section-title{
  font-size: var(--font-size-h4);
  text-transform: uppercase;
  padding-top: var(--space-4);
}
.form-container {
    max-width: 90rem;
    margin: 0 auto;
    background: var(--color-background);
    border-radius: var(--radius-l);
    box-shadow: var(--elevation-2);
    overflow: hidden;
    padding: var(--space-8);
    border-top: .4rem solid var(--color-primary);
}

.form-header {
    color: var(--color-foreground);
    text-align: center;
    border-bottom: .2rem solid var(--color-neutral);
    padding-bottom: var(--space-8) ;
}

.form-header h1 {
    font-size: var(--font-size-h2);
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-4);
    font-family: var(--font-sans);
}
.form-header p {
    opacity: 0.9;
    font-weight: 300;
    font-size: var(--font-size-p);
    font-family: var(--font-alt);
}
.form-consent {
  padding: var(--space-8) 0;
  border-bottom: .2rem solid var(--color-neutral);
}
.form-consent__title {
  font-size: var(--font-size-h5);
}
.form-consent__checkbox-container {
  display: flex;
  align-items: flex-start; 
}
.form-consent__checkbox{
  width: fit-content;
  margin-top: .5rem;
}
#applicantConsentLabel{
  font-weight: 300;
  font-family: var(--font-alt);
  padding-left: 2rem;
}
.input-container {
  padding: var(--space-4) var(--space-4) 0 var(--space-4);
}
.radio-group{
  display: flex;
  gap: var(--space-4);
}
.radio-group__label{
  display: flex;
}
.radio-button{
  margin-right: .8rem;
}
.radio-group-container{
  padding: var(--space-4) var(--space-4) 0 var(--space-4);
}
.consent-warning{
  color: red;
  font-weight: 500;
  padding-left: 2rem;
}
.consent-text{
  display: flex;
  flex-direction: column;
}
.button-container{
  display: flex;
  gap: var(--space-2);
  padding: 2rem 1.5rem 1rem 1.5rem;
}
.navigation-button{
  background: var(--color-background);
  border: .2rem solid var(--color-neutral);
}
.navigation-button:hover {
  transform: translateY(-0.2rem);
  background: var(--color-light-bg);
  border-color: var(--color-primary);
}
.button-submit{
  background: var(--color-primary);
  color: var(--color-background);
}
.button-submit:hover{
  transform: translateY(-0.2rem);
}
/* === MINIMAL FILE UPLOAD STYLES - ADD THESE TO YOUR EXISTING STYLES === */

.upload-minimal {
  display: flex;
  flex-direction: column;
  gap: var(--space-4);
}

.upload-minimal-btn {
  background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
  color: white;
  border: none;
  border-radius: var(--radius-m);
  padding: var(--space-4) var(--space-6);
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: var(--space-3);
  box-shadow: var(--shadow-sm);
  width: fit-content;
}

.upload-minimal-btn:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
}

.minimal-preview {
  margin-top: var(--space-4);
  width: 100%;
}

.minimal-item {
  display: flex;
  align-items: center;
  padding: var(--space-3) var(--space-4);
  background: white;
  border: 1px solid var(--color-neutral-light);
  border-radius: var(--radius-m);
  margin-bottom: var(--space-2);
  transition: all 0.2s ease;
}

.minimal-item:hover {
  border-color: var(--color-primary);
  background: var(--color-primary-5);
}

.minimal-name {
  flex: 1;
  font-weight: 500;
  font-size: var(--font-size-small);
  color: var(--color-foreground);
}

.file-remove {
  background: none;
  border: none;
  color: var(--color-neutral-dark);
  cursor: pointer;
  padding: var(--space-2);
  border-radius: var(--radius-s);
  transition: all 0.2s ease;
}

.file-remove:hover {
  background: var(--color-error);
  color: white;
}

.empty-state {
  text-align: center;
  padding: var(--space-8);
  color: var(--color-neutral-dark);
  width: 100%;
  border: 2px dashed var(--color-neutral);
  border-radius: var(--radius-m);
  background: var(--color-light-bg);
}

.empty-icon {
  font-size: 2.5rem;
  margin-bottom: var(--space-3);
  opacity: 0.5;
}

.empty-text {
  font-size: var(--font-size-p);
  margin-bottom: var(--space-2);
}

.empty-subtext {
  font-size: var(--font-size-small);
  opacity: 0.7;
}

/* File input hidden but accessible */
.file-input-hidden {
  position: absolute;
  left: -9999px;
}
</style>
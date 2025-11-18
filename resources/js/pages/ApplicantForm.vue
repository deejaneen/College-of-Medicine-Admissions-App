<script lang="ts" setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';

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
  graduateStudiesTranscriptOfRecords: File[];

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
  emailAddress: 'Email Address',
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
  indigenousGroup: 'Are you a member of any indigenous group? (Yes or No)',
  indigenousGroupSpecify: 'If yes, please specify (E.g. Aeta, Agta, Badjao, Mangyan, etc.)',
  physicalDisability: 'Do you have any physical disability or condition? (Yes or No)',
  physicalDisabilitySpecify: 'If yes, please specify',

  bachelorsDegree: 'Bachelors Degree (Do not abbreviate)',
  nameOfSchoolBachelorsDegree: "Name of School attended for the Bachelor's Degree",
  gwaforBachelorsDegree: "GWA for Bachelor's Degree",
  bachelorsTranscriptOfRecords: "Bachelor's Transcript of Records",
  graduateStudies: 'Graduate Studies',
  nameOfSchoolGraduateStudies: 'Name of School attended for Graduate Studies',
  dateOfGraduation: 'Date of Graduation',
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
  communityHealthVolunteer: 'Are any of your parents a Community Health Volunteer? (Yes or No)',
  communityHealthOrganization: 'If yes, under what organization?',

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
  if (!fileFields.includes(field)) {
    const value = form[field];
  }
};

// Validation per step
const validateStep = (step: number) => {
  let valid = true;
  const fields = stepFields[step];

  fields.forEach((field) => {
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

  // Step 1: Indigenous Group
  if (form.indigenousGroup === '') {
    errors.indigenousGroup = "Please select YES or NO.";
    valid = false;
  }
  // if (form.indigenousGroup === 'YES' && form.indigenousGroupSpecify.trim() === '') {
  //   errors.indigenousGroupSpecify = "Please specify the indigenous group.";
  //   valid = false;
  // }

  // Step 1: Physical Disability
  if (form.physicalDisability === '') {
    errors.physicalDisability = "Please select YES or NO.";
    valid = false;
  }
  // if (form.physicalDisability === 'YES' && form.physicalDisabilitySpecify.trim() === '') {
  //   errors.physicalDisabilitySpecify = "Please specify the physical disability.";
  //   valid = false;
  // }

  // Step 3: Community Health Volunteer
  if (form.communityHealthVolunteer === '') {
    errors.communityHealthVolunteer = "Please select YES or NO.";
    valid = false;
  }
  // if (form.communityHealthVolunteer === 'YES' && form.communityHealthOrganization.trim() === '') {
  //   errors.communityHealthOrganization = "Please specify the organization.";
  //   valid = false;
  // }

  // Step 1: Email match
  if (step === 1 && form.emailAddress !== form.verifyEmailAddress) {
    errors.verifyEmailAddress = "Email and Verify Email must match.";
    valid = false;
  }

  return valid;
};

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
    router.push({ name: 'application.submit' });
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
        Southern Luzon State University aims to comply with the Republic Act No. 10173, otherwise known as the Data Privacy Act of 2012. Hence, it recognizes the responsibilty of safeguarding personal privacy through ensuring that any piece of information obtained from SLSU-provided platforms is entered and stored within the University's database system and shall only be accessed by authorized SLSU personnel. Moreover, obtained information shall strictly be used as permitted or required by law to pursue legitimate interests as an academe.
      </p>
    </div>
    <div class="form-consent">
      <div class="form-consent__title">APPLICANT'S CONSENT:</div>
      <div class="form-consent__checkbox-container">
          <input class="form-consent__checkbox" name="applicantConsent" id="applicantConsent" required type="checkbox">
          <label for="applicantConsent" id="applicantConsentLabel">
            I hereby give my censent that my personal data in custody of SLSU may be used by the University for internal reports and for other legitimate academic interests. Futher, I hereby affirm my right to be informed, object to processing, access and rectify, suspend or withdraw my personal data, and be indemnified in case of damages pursuant to eh provisions of the Data Privacy Act of 2012.
          </label>
      </div>
      <p v-if="errors.applicantConsent" style="color: red">
        {{ errors.applicantConsent }}
      </p>
    </div>
    
    <div v-if="currentStep === 1">
      <div  class="input-container" v-for="field in stepFields[1]" :key="field">
        <label :for="field">{{ fieldDisplayNames[field] }}</label>

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
        <label>{{ fieldDisplayNames.indigenousGroup }}</label>

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
      </div>

      <!-- Conditional Specify Field -->
      <div v-if="form.indigenousGroup === 'YES'">
        <label>{{ fieldDisplayNames.indigenousGroupSpecify }}</label>
        <input
          id="indigenousGroupSpecify"
          v-model="form.indigenousGroupSpecify"
          @input="onInput('indigenousGroupSpecify')"
        />
        <p v-if="errors.indigenousGroupSpecify" style="color:red;">
          {{ errors.indigenousGroupSpecify }}
        </p>
      </div>
      <div class="radio-group-container">
        <label>{{ fieldDisplayNames.physicalDisability }}</label>

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
      </div>

      <div
        v-if="form.physicalDisability === 'YES'"
      >
        <label>{{ fieldDisplayNames.physicalDisabilitySpecify }}</label>
        <input
          id="physicalDisabilitySpecify"
          v-model="form.physicalDisabilitySpecify"
          @input="onInput('physicalDisabilitySpecify')"
        />

        <p v-if="errors.physicalDisabilitySpecify" style="color:red;">
          {{ errors.physicalDisabilitySpecify }}
        </p>
      </div>
      <div class="radio-group-container">
        <label>{{ fieldDisplayNames.communityHealthVolunteer }}</label>

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
      </div>
    </div>
    <!-- Step 2 -->
    <div v-if="currentStep === 2">
      <h2>Educational Background</h2>
      <div v-for="field in stepFields[2]" :key="field" style="margin-bottom: 10px;">
        <label :for="field">{{ fieldDisplayNames[field] }}</label>
        <input :id="field" v-model="form[field]" />
        <p v-if="errors[field]" style="color: red">{{ errors[field] }}</p>
      </div>
    </div>

    <!-- Step 3 -->
    <div v-if="currentStep === 3">
      <h2>Parents Information</h2>
      <div v-for="field in stepFields[3]" :key="field" style="margin-bottom: 10px;">
        <label :for="field">{{ fieldDisplayNames[field] }}</label>
        <input :id="field" v-model="form[field]" />
        <p v-if="errors[field]" style="color: red">{{ errors[field] }}</p>
      </div>
    </div>
    

      <!-- Conditional Org Input -->
      <div
        v-if="form.communityHealthVolunteer === 'YES'"
      >
        <label>{{ fieldDisplayNames.communityHealthOrganization }}</label>
        <input
          id="communityHealthOrganization"
          v-model="form.communityHealthOrganization"
          @input="onInput('communityHealthOrganization')"
        />

        <p v-if="errors.communityHealthOrganization" style="color:red;">
          {{ errors.communityHealthOrganization }}
        </p>
      </div>

    <!-- Step 4 -->
    <div v-if="currentStep === 4">
      <h2>NMAT</h2>
      <div v-for="field in stepFields[4]" :key="field" style="margin-bottom: 10px;">
        <label :for="field">{{ fieldDisplayNames[field] }}</label>
        <input :id="field" v-model="form[field]" />
        <p v-if="errors[field]" style="color: red">{{ errors[field] }}</p>
      </div>
    </div>

    <!-- Step 5 -->
    <div v-if="currentStep === 5">
      <h2>Essay</h2>
      <label for="whyChooseSouthernLuzonStateUniversity">{{ fieldDisplayNames.whyChooseSouthernLuzonStateUniversity }}</label>
      <textarea
        id="whyChooseSouthernLuzonStateUniversity"
        v-model="form.whyChooseSouthernLuzonStateUniversity"
        rows="6"
        style="width: 100%;"
      ></textarea>
      <p v-if="errors.whyChooseSouthernLuzonStateUniversity" style="color: red">{{ errors.whyChooseSouthernLuzonStateUniversity }}</p>
    </div>

    <!-- Navigation Buttons -->
    <div style="margin-top: 20px;">
      <button type="button" @click="prevStep" :disabled="currentStep === 1">Previous</button>
      <button type="button" @click="nextStep" v-if="currentStep < totalSteps">Next</button>
      <button type="submit" v-else>Submit</button>
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

input:focus,
select:focus,
textarea:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 0.3rem var(--color-primary-20);
}
.form-container {
    max-width: 90rem;
    margin: var(--space-12) auto;
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
  padding: var(--space-8);
  border-bottom: .2rem solid var(--color-neutral);
}
.form-consent__title {
  font-size: var(--font-size-h4);
}
.form-consent__checkbox-container {
  display: flex;
  align-items: flex-start; 
}
.form-consent__checkbox{
  width: 20%;
  margin-top: .5rem;
}
#applicantConsentLabel{
  font-weight: 300;
  font-family: var(--font-alt);
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
  padding: var(--space-4);
}
</style>
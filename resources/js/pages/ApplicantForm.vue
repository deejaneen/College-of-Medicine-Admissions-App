<script lang="ts" setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';

// Step management
const currentStep = ref(1);
const totalSteps = 5;

// Router for submission
const router = useRouter();

interface Props {
  userEmail: string;
}
const props = defineProps<Props>();

// TypeScript interface for the full form
interface ApplicationForm {
  // Step 1
  emailAddress: string;
  verifyEmailAddress: string;
  lastName: string;
  middleName: string;
  firstName: string;
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
  indigenousGroup: string; // Yes/No
  indigenousGroupSpecify: string; // conditional input
  physicalDisability: string; // Yes/No
  physicalDisabilitySpecify: string; // conditional input

  // Step 2
  bachelorsDegree: string;
  nameOfSchoolBachelorsDegree: string;
  gwaforBachelorsDegree: string;
  trascriptOfRecords: string;

  // Step 3
  fatherLastName: string;
  fatherFirstName: string;
  fatherMiddleName: string;
  fatherOccupation: string;
  motherLastName: string;
  motherFirstName: string;
  motherMiddleName: string;
  motherOccupation: string;
  combinedAnnualIncomeOfFamily: string; // radio
  communityHealthVolunteer: string; // Yes/No
  communityHealthOrganization: string; // conditional input

  // Step 4
  nmatNationalMedicalAdmissionTestScore: string;
  nmatNationalMedicalAdmissionTestCertification: string;
  dateOfNmatNationalMedicalAdmissionTestExamination: string;

  // Step 5
  whyChooseSouthernLuzonStateUniversity: string;
}


const form = reactive<ApplicationForm>({
  // Step 1
  firstName: '',
  middleName: '',
  lastName: '',
  emailAddress: props.userEmail || '',  // prefilled from props
  verifyEmailAddress: '',
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
  indigenousGroup: '',           // Yes/No radio
  indigenousGroupSpecify: '',    // conditional input
  physicalDisability: '',        // Yes/No radio
  physicalDisabilitySpecify: '', // conditional input

  // Step 2
  bachelorsDegree: '',
  nameOfSchoolBachelorsDegree: '',
  gwaforBachelorsDegree: '',
  trascriptOfRecords: '',

  // Step 3
  fatherFirstName: '',
  fatherMiddleName: '',
  fatherLastName: '',
  fatherOccupation: '',
  motherFirstName: '',
  motherMiddleName: '',
  motherLastName: '',
  motherOccupation: '',
  combinedAnnualIncomeOfFamily: '',  // radio group
  communityHealthVolunteer: '',       // Yes/No radio
  communityHealthOrganization: '',    // conditional input

  // Step 4
  nmatNationalMedicalAdmissionTestScore: '',
  nmatNationalMedicalAdmissionTestCertification: '',
  dateOfNmatNationalMedicalAdmissionTestExamination: '',

  // Step 5
  whyChooseSouthernLuzonStateUniversity: '',
});


// Reactive error object
const errors = reactive<Record<string, string>>({});

// Display name mapping
const fieldDisplayNames: Record<keyof ApplicationForm, string> = {
  // Step 1
  firstName: 'First Name',
  middleName: 'Middle Name',
  lastName: 'Last Name',
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
  indigenousGroup: 'Are you a member of any indigenous group?',
  indigenousGroupSpecify: 'If yes, please specify',
  physicalDisability: 'Do you have any physical disability or condition?',
  physicalDisabilitySpecify: 'If yes, please specify',

  // Step 2
  bachelorsDegree: 'Bachelors Degree',
  nameOfSchoolBachelorsDegree: 'Name of School (Bachelors)',
  gwaforBachelorsDegree: 'GWA for Bachelors Degree',
  trascriptOfRecords: 'Transcript of Records',

  // Step 3
  fatherFirstName: "Father's First Name",
  fatherMiddleName: "Father's Middle Name",
  fatherLastName: "Father's Last Name",
  fatherOccupation: "Father's Occupation",
  motherFirstName: "Mother's First Name",
  motherMiddleName: "Mother's Middle Name",
  motherLastName: "Mother's Last Name",
  motherOccupation: "Mother's Occupation",
  combinedAnnualIncomeOfFamily: 'Combined Annual Income',
  communityHealthVolunteer: 'Are any of your parents a Community Health Volunteer?',
  communityHealthOrganization: 'If yes, under what organization?',

  // Step 4
  nmatNationalMedicalAdmissionTestScore: 'NMAT Score',
  nmatNationalMedicalAdmissionTestCertification: 'NMAT Certification',
  dateOfNmatNationalMedicalAdmissionTestExamination: 'NMAT Examination Date',

  // Step 5
  whyChooseSouthernLuzonStateUniversity: 'Why Choose Southern Luzon State University?',
};


// Validation rules per step
const stepFields: Record<number, Array<keyof ApplicationForm>> = {
  1: [
    'emailAddress', 'verifyEmailAddress', 'lastName', 'middleName', 'firstName', 
    'dateOfBirth', 'citizenship', 'permanentHomeAddress', 'municipality',
    'province', 'zipCode', 'activeGmailAccount', 'cellphoneNumber', 'civilStatus', 
    'sex', 'religion', 'indigenousGroup', 'indigenousGroupSpecify', 
    'physicalDisability', 'physicalDisabilitySpecify'
  ],
  2: [
    'bachelorsDegree', 'nameOfSchoolBachelorsDegree', 'gwaforBachelorsDegree', 'trascriptOfRecords'
  ],
  3: [
    'fatherLastName', 'fatherMiddleName', 'fatherFirstName', 'fatherOccupation',
    'motherLastName', 'motherMiddleName', 'motherFirstName',  'motherOccupation',
    'combinedAnnualIncomeOfFamily', 'communityHealthVolunteer', 'communityHealthOrganization'
  ],
  4: [
    'nmatNationalMedicalAdmissionTestScore',
    'nmatNationalMedicalAdmissionTestCertification',
    'dateOfNmatNationalMedicalAdmissionTestExamination'
  ],
  5: ['whyChooseSouthernLuzonStateUniversity'],
};

// Basic validation function
const validateStep = (step: number) => {
  let valid = true;
  const fields = stepFields[step];
  fields.forEach((field) => {
    if (!form[field] || form[field].trim() === '') {
      errors[field] = `${fieldDisplayNames[field]} is required.`;
      valid = false;
    } else {
      delete errors[field];
    }
  });

  // Additional validation for email match
  if (step === 1 && form.emailAddress !== form.verifyEmailAddress) {
    errors.verifyEmailAddress = "Email and Verify Email must match.";
    valid = false;
  }

  return valid;
};

// Navigation functions
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

const shouldUppercase = (field: keyof ApplicationForm) => {
  const noUppercaseFields = [
    'verifyEmailAddress',
    'whyChooseSouthernLuzonStateUniversity',
    'indigenousGroupSpecify',
    'physicalDisabilitySpecify',
  ];
  return !noUppercaseFields.includes(field);
};

// Input handler for uppercase
const onInput = (field: keyof ApplicationForm) => {
  if (shouldUppercase(field) && form[field]) {
    form[field] = form[field].toUpperCase();
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
        <p>Disclamer: Data gathered will be used as an assessment for the offering of Doctor of Medicine in the Academic Year 20XX-20XX at the Southern Luzon State University (Lucena City, Quezon) and other internal reports & legitimate academic interests.</p>
    </div>
    <div>
      <h3>DATA PRIVACY CONSENT/AGREEMENT</h3>
      <p>
        Southern Luzon State University aims to comply with the Republic Act No. 10173, otherwise known as the Data Privacy Act of 2012. Hence, it recognizes the respnsibilty of safeguarding personal privacy through ensuring that any piece of information obtained from SLSU-provided platforms is entered and stored withtin the University's database system and shall only be accessed by authorized SLSU personnel Moreover, obtained information shall strictly be used as permitted or required by law to pursue legitimate interests as an academe.
      </p>
    </div>
    <div>
      <h3>APPLICANT'S CONSENT:</h3>
      <p>
       I hereby give my censent that my personal data in custody of SLSU may be used by the University for internal reports and for other legitimate academic interests. Futher, I hereby affirm my right to be informed, object to processing, access and rectify, suspend or withdraw my personal data, and be indemnified in case of damages pursuant to eh provisions of the Data Privacy Act of 2012.
      </p>
    </div>
    <div v-for="field in stepFields[1]" :key="field" style="margin-bottom: 10px">
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



    <!-- Step 2 -->
    <div v-if="currentStep === 2">
      <h2>Educational Background</h2>
      <div v-for="field in stepFields[2]" :key="field" style="margin-bottom: 10px;">
        <label :for="field">{{ fieldDisplayNames[field] }}</label>
        <input :id="field" v-model="form[field]" @input="field !== 'whyChooseSouthernLuzonStateUniversity' && (form[field] = form[field].toUpperCase())"/>
        <p v-if="errors[field]" style="color: red">{{ errors[field] }}</p>
      </div>
    </div>

    <!-- Step 3 -->
    <div v-if="currentStep === 3">
      <h2>Parents Information</h2>
      <div v-for="field in stepFields[3]" :key="field" style="margin-bottom: 10px;">
        <label :for="field">{{ fieldDisplayNames[field] }}</label>
        <input :id="field" v-model="form[field]" @input="field !== 'whyChooseSouthernLuzonStateUniversity' && (form[field] = form[field].toUpperCase())"/>
        <p v-if="errors[field]" style="color: red">{{ errors[field] }}</p>
      </div>
    </div>

    <!-- Step 4 -->
    <div v-if="currentStep === 4">
      <h2>NMAT</h2>
      <div v-for="field in stepFields[4]" :key="field" style="margin-bottom: 10px;">
        <label :for="field">{{ fieldDisplayNames[field] }}</label>
        <input :id="field" v-model="form[field]" @input="field !== 'whyChooseSouthernLuzonStateUniversity' && (form[field] = form[field].toUpperCase())"/>
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
}

.form-header {
    color: var(--color-foreground);
    padding: var(--space-8);
    text-align: center;
    border-top: .4rem solid var(--color-primary);
}

.form-header h1 {
    font-size: var(--font-size-h2);
    font-weight: 600;
    margin-bottom: var(--space-4);
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

</style>
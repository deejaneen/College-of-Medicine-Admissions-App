<template>
    <div>
        <header>
            <AuthSecondaryNavLayout  :deadline="props.schoolYear?.application_deadline"/>
            <AuthMainNavLayout />
        </header>
        <main class="main-container">
            <ApplicantForm 
                :userEmail="props.userEmail" 
                :isApplicationOpen="props.isApplicationOpen"
                :schoolYear="props.schoolYear"
                :hasSubmitted= "props.hasSubmitted"
            />
        </main>
        <footer>
            <FooterLayout/>
        </footer>
    </div>
</template>

<script lang="ts" setup>
import AuthSecondaryNavLayout from '../layouts/AuthSecondaryNavLayout.vue';
import AuthMainNavLayout from '../layouts/AuthMainNavLayout.vue';
import FooterLayout from '../layouts/FooterLayout.vue';
import ApplicantForm from '../pages/ApplicantForm.vue';

interface Props {
  userEmail: string;
  isApplicationOpen: boolean;    
  schoolYear: {                  
    school_year: string;
    application_deadline: string;
  };
  hasSubmitted: boolean;
}
const props = defineProps<Props>();
</script>

<style scoped>
.main-container{
  background: var(--color-light-bg);
  padding: var(--space-8);
}
</style>
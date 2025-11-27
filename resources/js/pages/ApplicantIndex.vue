<template>
    <div>
        <header>
            <AuthSecondaryNavLayout/>
            <AuthMainNavLayout/>
        </header>
        <main class="main-container">
            <ApplicantDashboard :activeUser="props.activeUser ?? ''" />
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
import ApplicantDashboard from './auth-applicant/ApplicantDashboard.vue';

const props = defineProps<{ activeUser: string }>();
</script>

<style scoped>
.main-container{
  background: var(--color-light-bg);
  padding: var(--space-8);
}
</style>
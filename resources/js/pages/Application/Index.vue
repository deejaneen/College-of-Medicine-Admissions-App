<template>
    <div>
        <header>
            <AuthSecondaryNavLayout :deadline="props.deadline"/>
            <AuthMainNavLayout/>
        </header>
        <main>
            <ApplicantDashboard />
        </main>
        <footer>
            <FooterLayout/>
        </footer>
    </div>
</template>

<script lang="ts" setup>
import AuthSecondaryNavLayout from '../../../js/layouts/AuthSecondaryNavLayout.vue';
import AuthMainNavLayout from '../../../js/layouts/AuthMainNavLayout.vue';
import ApplicantDashboard from '@/pages/auth-applicant/ApplicantDashboard.vue';
import FooterLayout from '../../../js/layouts/FooterLayout.vue';

</script>


<script lang="ts">
export default {
    props: {
        email: String,
        name: String,
    },
};
</script>
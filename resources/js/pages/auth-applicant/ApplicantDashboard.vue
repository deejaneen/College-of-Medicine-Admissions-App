<template>
    <div class="dashboard-container">
        <div class="dashboard__banner">
            <div class="dashboard__title">
                Dashboard
            </div>
           
        </div>
        <div class="dashboard__card">
            <p>
                Hello {{props.activeUser}}, welcome to SLSU College of Medicine Admissions Portal. 
            </p>
            <!-- <p class="dashboard__card-status">
                {{ statusMessage }}
            </p> -->
        </div>
    </div>
</template>

<script lang="ts" setup>
// Props
interface Props {
  activeUser: string;
}
const props = defineProps<Props>();
</script>

<script lang="ts">
    export default {
        name: "ApplicantDashboard"
    }
</script>

<style scoped>

.submission-status {
    font-size: var(--font-size-h5);
}
/* Applicant Auth Dashboard */
.dashboard-container {
    background: var(--color-background);
    padding: var(--space-8);
    height: auto;
    border-radius: var(--radius-l);
    box-shadow: var(--elevation-2);
    margin: 0 auto;
    max-width: 90rem;
    border-top: .4rem solid var(--color-primary);
}
.dashboard__banner{
 padding-bottom: 2rem;
}
.dashboard__title{
    font-size: var(--font-size-h4);
    color: var(--color-primary);
}
.dashboard__card {
     border-radius: var(--radius-l);
    background: var(--background-gradient-cards);
    gap: 2rem;
    display: flex;
    align-items: left;
    flex-direction: column;
}
.dashboard__card p {
    font-size: var(--font-size-p);
}

@media (max-width: 640px) {
    .dashboard__card p {
        font-size: 1.4rem;
    }
}
@media (max-width: 768px) {
    .dashboard__card p {
        font-size: 1.6rem;
    }
    .dashboard__card {
        padding: var(--space-5);
    }
    .dashboard-container{
        padding: var(--space-5);
    }
}

</style>
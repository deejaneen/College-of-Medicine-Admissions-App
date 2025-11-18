<template>
    <div class="dashboard-container">
        <div class="dashboard__banner">
            <h1>
                Dashboard
            </h1>
           
        </div>
        <div class="dashboard__card">
            <p>
                Hello name, thank you for applying to SLSU College of Allied Medicine. 
            </p>
            <p class="dashboard__card-status">
                Your application is being processed. You will receive an email regarding the result once your application has been reviewed.
            </p>
        </div>
    </div>
</template>

<script lang="ts" setup>
</script>

<script lang="ts">
    export default {
        name: "ApplicantDashboard"
    }
</script>

<style scoped>

.submission-status {
    font-size: var(--font-p);
}
/* Applicant Auth Dashboard */
.dashboard-container {
    background: var(--background);
    padding: 6rem 12.8rem;
    height: auto;
}
.dashboard__banner{
 padding-bottom: 2rem;
}
.dashboard__card {
    border-radius: 2rem;
    background: var(--background-gradient-cards);
    padding: 6rem;
    gap: 2rem;
    display: flex;
    align-items: left;
    flex-direction: column;
}
.dashboard__card p {
    font-size: var(--font-h6);
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
        padding: 2rem;
    }
    .dashboard-container{
        padding: 2rem;
    }
}

</style>
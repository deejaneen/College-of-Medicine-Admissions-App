<template>
    <div>
        <header>
            <AuthSecondaryNavLayout/>
            <AuthMainNavLayout/>
        </header>
        <main class="main-container">
            <ApplicantForm :userEmail="email ?? ''" />
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
</script>


<script lang="ts">
export default {
    props: {
        email: String,
    },
};
</script>

<style scoped>
.main-container{
    background: var(--color-light-bg);
}
</style>
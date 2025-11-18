<template>
    <div>
        <header>
            <AuthSecondaryNavLayout/>
            <AuthMainNavLayout/>
        </header>
        <main class="main-container">
            <ApplicantForm :userEmail="props.userEmail ?? ''" />
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

const props = defineProps<{ userEmail: string }>();
</script>

<style scoped>
.main-container{
  background: var(--color-light-bg);
}
</style>
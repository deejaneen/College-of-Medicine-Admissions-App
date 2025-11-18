<template>
    <div>
        <header>
            <AuthSecondaryNavLayout/>
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
import AuthSecondaryNavLayout from '../layouts/AuthSecondaryNavLayout.vue';
import AuthMainNavLayout from '../layouts/AuthMainNavLayout.vue';
import FooterLayout from '../layouts/FooterLayout.vue';
</script>


<script lang="ts">
export default {
    props: {
        email: String,
        name: String,
    },
};
</script>
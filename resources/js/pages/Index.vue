<template>
    <div class="main_container">
        <header>
            <SecondaryNavLayout :deadline="props.deadline"/>
             <MainNavLayout/>
        </header>
        <main>
            <HeroSectionLayout />
            <RequirementsAndQualificationsLayout/>
        </main>
        <footer>
            <FooterLayout/>
        </footer>
    </div>
        
   
</template>

<script lang="ts" setup>
import SecondaryNavLayout from '../layouts/SecondaryNavLayout.vue';
import MainNavLayout from '../layouts/MainNavLayout.vue';
import HeroSectionLayout from '../layouts/HeroSectionLayout.vue';
import RequirementsAndQualificationsLayout from '../layouts/RequirementsAndQualificationsLayout.vue';
import FooterLayout from '../layouts/FooterLayout.vue';

interface Props {
  deadline?: string;
}

const props = defineProps<Props>();

</script>

<style scoped>
.main_container{
    background: var(--color-primary);
}
</style>
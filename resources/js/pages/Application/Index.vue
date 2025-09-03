<template>
    <div>
        <header>
            <HeaderNavLayout />
            <MainNavLayout />
        </header>
        <main>
            <HeroSectionLayout />
            <RequirementsAndQualificationsLayout/>
        </main>
        <footer>
            
        </footer>
    </div>
        
   
</template>

<script lang="ts" setup>
import HeaderNavLayout from '@/layouts/HeaderNavLayout.vue';
import MainNavLayout from '@/layouts/MainNavLayout.vue';
import HeroSectionLayout from '@/layouts/HeroSectionLayout.vue';
import RequirementsAndQualificationsLayout from '@/layouts/RequirementsAndQualificationsLayout.vue';
</script>
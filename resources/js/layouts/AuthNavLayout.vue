<template>
    <div>
        <header>
            <template >
                <UserNav/>
            </template>
            <ul>
                <li>Personal Information</li>
                <li>Educational Background</li>
                <li>Family Income</li>
                <li>NMAT Information</li>
            </ul>
        </header>
    </div>
    
</template>

<script lang="ts" setup>
import UserNav from '../components/UserNav.vue';
</script>

<script lang="ts">
export default {
  name: 'AuthNavLayout',
  'UserNav': UserNav,

}
</script>

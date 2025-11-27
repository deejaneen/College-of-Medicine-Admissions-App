<template>
    <AdminIndex>
        <h1>Variables</h1>
        <div>Variables content here</div>
    </AdminIndex>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import AdminIndex from '../AdminIndex.vue';

export default defineComponent({
    name: "AdminVariables",
    components: {
        AdminIndex
    }
});
</script>
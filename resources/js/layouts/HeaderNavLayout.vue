<template>
    
    <section class="header-nav-container">
        <ul class="header-nav">
            <li class="submission-deadline">Next Submission Date: <b>30 June 2025</b></li>
            <li>
                <Link href="/" class="slsu-backlink"><span class="underline-animation">SLSU Website</span></Link>
            </li>
        </ul>
    </section>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
</script>

<script lang="ts">
    export default {
        name: 'HeaderNavLayout',
        metaInfo: {
        title: 'Submission Deadline and SLSU Website Navigation',
        meta: [
          { name: 'description', content: 'This is a custom meta description for my awesome page.' },
          // Add other meta tags as needed
        ],
      },
    }
</script>
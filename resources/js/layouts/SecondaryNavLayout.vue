<template>
    <section class="header-nav-container">
        <ul class="header-nav">
            <li class="submission-deadline">
                <span v-if="deadline">Next Submission Deadline: <b>{{ formatDate(deadline) }}</b></span>
                <span v-else><b>Applications Closed</b></span>
            </li>
            <li>
                <Link href="/" class="slsu-backlink"><span class="underline-animation">SLSU Website</span></Link>
            </li>
        </ul>
    </section>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';

interface Props {
  deadline?: string; 
}

const { deadline } = defineProps<Props>();

function formatDate(dateString: string) {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
}
</script>

<script lang="ts">
    export default {
        name: 'HeaderNavLayout',
        metaInfo: {
        title: 'Submission Deadline and SLSU Website Navigation',
      },
    }
</script>

<style scoped>
/* Your existing CSS remains the same */
.header-nav-container {
    background: var(--color-primary);
    color: var(--color-background);
    padding: var(--space-3) var(--space-32);
    font-family: var(--font-sans);
    position: relative;
    z-index: 5;
}

.header-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    list-style: none;
    margin: 0;
    padding: 0;
    font-size: var(--font-size-p);
    font-weight: 500;
    letter-spacing: +0.5%;
}

.submission-deadline b {
    color: var(--color-secondary);
    margin-left: var(--space-2);
}

/* Style for "Applications Closed" text */
.submission-deadline span:has(b:only-child) b {
    margin-left: 0;
    color: var(--color-secondary); 
}

.slsu-backlink {
    color: var(--color-background);
    text-decoration: none;
    transition: all 0.3s ease;
    padding: var(--space-1) var(--space-2);
    border-radius: var(--radius-s);
}

.slsu-backlink:hover {
    background: var(--color-primary-40);
}

.underline-animation {
    position: relative;
}

.underline-animation::after {
    content: '';
    position: absolute;
    width: 0;
    height: 0.2rem;
    bottom: -0.2rem;
    left: 0;
    background: var(--color-secondary);
    transition: width 0.3s ease;
}

.slsu-backlink:hover .underline-animation::after {
    width: 100%;
}

/* Your existing responsive CSS remains the same */
@media (max-width: 480px) {
    .header-nav-container {
        padding: var(--space-2) var(--space-3);
    }
    
    .header-nav li{
        white-space: nowrap;
    }
    .header-nav {
        flex-direction: column;
        gap: var(--space-2);
        font-size: 1.4rem;
        text-align: center;
    }
    
    .submission-deadline b {
        margin-left: var(--space-1);
    }
}

@media (max-width: 768px) {
    .header-nav-container {
        padding: var(--space-2) var(--space-4);
    }
    
    .header-nav {
        font-size: 1.4rem;
    }
}

@media (max-width: 1024px) {
    .header-nav-container {
        padding: var(--space-3) var(--space-16);
    }
}

@media (max-width: 1440px) {
    .header-nav-container {
        padding: var(--space-3) var(--space-24);
    }
}
</style>
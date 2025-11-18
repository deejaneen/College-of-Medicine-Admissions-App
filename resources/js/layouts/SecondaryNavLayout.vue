<template>
    <section class="header-nav-container">
        <ul class="header-nav">
            <li class="submission-deadline">Next Submission Deadline: <b>30 June 2025</b></li>
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

<style scoped>
.header-nav-container {
    background: var(--color-primary);
    color: var(--color-background);
    padding: var(--space-3) var(--space-32);
    font-family: var(--font-sans);
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

/* Phone (480px and down) */
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

/* Tablet (768px and down) */
@media (max-width: 768px) {
    .header-nav-container {
        padding: var(--space-2) var(--space-4);
    }
    
    .header-nav {
        font-size: 1.4rem;
    }
}

/* Small Desktop (1024px and down) */
@media (max-width: 1024px) {
    .header-nav-container {
        padding: var(--space-3) var(--space-16);
    }
}

/* Large Desktop (1440px and down) */
@media (max-width: 1440px) {
    .header-nav-container {
        padding: var(--space-3) var(--space-24);
    }
}

/* Everything above 1440px uses the default padding (space-32) */
</style>
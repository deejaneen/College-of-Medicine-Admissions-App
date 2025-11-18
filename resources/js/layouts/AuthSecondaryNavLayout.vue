<template>
    <section class="header-nav-container">
        <ul class="header-nav">
            <li class="submission-deadline">Next Submission Deadline: <b>30 June 2025</b></li>
        </ul>
    </section>
</template>

<script lang="ts" setup>
</script>

<script lang="ts">
    export default {
        name: "AuthSecondaryNavLayout"
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
    justify-content: start;
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

/* Phone (480px and down) */
@media (max-width: 480px) {
    .header-nav-container {
        padding: var(--space-2) var(--space-3);
    }
    
    .header-nav {
        font-size: 1.4rem;
        text-align: center;
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
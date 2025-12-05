<template>
    <nav v-if="links && links.length > 3" class="pagination-container">
        <ul class="pagination-list">
            <li v-for="(link, index) in links" :key="index">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    class="pagination-link"
                    :class="{ 'pagination-link-active': link.active }"
                    v-html="link.label"
                    preserve-scroll
                    preserve-state
                />
                <span
                    v-else
                    class="pagination-link pagination-link-disabled"
                    v-html="link.label"
                />
            </li>
        </ul>
    </nav>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

defineProps<{
    links: PaginationLink[];
}>();
</script>

<script lang="ts">
export default {
    name: 'Pagination',
}
</script>

<style scoped>
.pagination-container {
    margin: 2rem 0;
    padding: 1rem 0;

}

.pagination-list {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 0.5rem;
    justify-content: center;
    align-items: center;
}

.pagination-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 4rem;
    height: 4rem;
    padding: 0 0.75rem;
    border-radius: var(--radius-s);
    background-color: var(--color-light-bg);
    color: var(--color-primary);
    text-decoration: none;
    font-size: var(--font-size-p);
    font-weight: 500;
    border: 1px solid var(--color-neutral);
    transition: all 0.2s ease;
    cursor: pointer;
}

.pagination-link:hover:not(.pagination-link-disabled) {
    transform: translateY(-1px);
}

.pagination-link-active {
    background-color: var(--color-primary);
    color: white;
    border-color: var(--color-primary);
}

.pagination-link-disabled {
    background-color: var(--color-light-bg);
    color: var(--color-text-disabled);
    border-color: var(--color-neutral-light);
    cursor: not-allowed;
    opacity: 0.6;
}

/* Special styling for previous/next buttons */
.pagination-link[href*="page="]:first-child,
.pagination-link[href*="page="]:last-child {
    min-width: auto;
    padding: 0 1rem;
}
</style>
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
  margin: var(--space-3) 0;
  padding: var(--space-2) 0;
  overflow: visible;
}

.pagination-list {
  display: flex;
  list-style: none;
  padding: var(--space-1) 0;
  margin: 0;
  gap: 0.25rem;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;
  overflow: visible;
}

.pagination-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 2.8rem;
  height: 2.8rem;
  padding: 0 0.5rem;
  border-radius: var(--radius-xs);
  background-color: var(--color-light-bg);
  color: var(--color-primary);
  text-decoration: none;
  font-size: 1rem;
  font-weight: 500;
  border: 1px solid var(--color-neutral);
  transition: all 0.2s ease;
  cursor: pointer;
  font-family: var(--font-sans);
  position: relative;
  margin: 1px;
}

.pagination-link:hover:not(.pagination-link-disabled) {
  transform: translateY(-1px);
  box-shadow: var(--elevation-1);
}

.pagination-link-active {
  background-color: var(--color-primary);
  color: var(--color-background);
  border-color: var(--color-primary);
  font-weight: 600;
}

.pagination-link-disabled {
  background-color: var(--color-light-bg);
  color: var(--color-text-disabled);
  border-color: var(--color-neutral-light);
  cursor: not-allowed;
  opacity: 0.6;
}

.pagination-link[href*="page="]:first-child,
.pagination-link[href*="page="]:last-child {
  min-width: auto;
  padding: 0 0.75rem;
  font-size: 1rem;
}

@media (max-width: 375px) {
  .pagination-list {
    gap: 0.25rem;
  }
  
  .pagination-link:not(:first-child):not(:last-child) {
    min-width: 2.4rem;
    height: 2.4rem;
    padding: 0 0.25rem;
    font-size: 1rem;
  }
  
  .pagination-link:not(:first-child):not(:last-child):not([aria-label*="Previous"]):not([aria-label*="Next"]) {
    display: none;
  }
  
  .pagination-link[href*="page="]:first-child,
  .pagination-link[href*="page="]:last-child {
    font-size: 1rem;
    padding: 0 0.5rem;
  }
}

@media (min-width: 600px) {
  .pagination-container {
    margin: var(--space-4) 0;
    padding: var(--space-3) 0;
  }
  
  .pagination-list {
    gap: 0.5rem;
  }
  
  .pagination-link {
    min-width: 3.2rem;
    height: 3.2rem;
    padding: 0 0.625rem;
    font-size: var(--font-size-p);
    border-radius: var(--radius-s);
    margin: 1px;
  }
  
  .pagination-link[href*="page="]:first-child,
  .pagination-link[href*="page="]:last-child {
    padding: 0 1rem;
    font-size: var(--font-size-p);
  }
}

@media (min-width: 768px) {
  .pagination-container {
    margin: var(--space-5) 0;
    padding: var(--space-4) 0;
  }
  
  .pagination-list {
    gap: 0.625rem;
  }
  
  .pagination-link {
    min-width: 3.7rem;
    height: 3.7rem;
    padding: 0 0.75rem;
    font-size: var(--font-size-p);
    margin: 1px;
  }
  
  .pagination-link[href*="page="]:first-child,
  .pagination-link[href*="page="]:last-child {
    padding: 0 1.25rem;
    font-size: var(--font-size-p);
  }
}

@media (min-width: 1024px) {
  .pagination-container {
    margin: 2rem 0;
    padding: 1rem 0;
  }
  
  .pagination-list {
    gap: 0.5rem;
  }
  
  .pagination-link {
    min-width: 4rem;
    height: 4rem;
    padding: 0 0.75rem;
    font-size: 1.4rem;
  }
  
  .pagination-link[href*="page="]:first-child,
  .pagination-link[href*="page="]:last-child {
    padding: 0 1rem;
    font-size: 1.4rem;
  }
}

@media (min-width: 1400px) {
  .pagination-link {
    min-width: 4.5rem;
    height: 4.5rem;
    font-size: 1.4rem;
  }
}

.pagination-container,
.pagination-list {
  max-width: 100%;
  overflow-x: auto;
}

@media (hover: none) and (pointer: coarse) {
  .pagination-link {
    min-height: 3rem;
    min-width: 3rem;
  }
  
  @media (min-width: 600px) {
    .pagination-link {
      min-height: 3.5rem;
      min-width: 3.5rem;
    }
  }
}

.pagination-link:focus {
  outline: 2px solid var(--color-primary);
  outline-offset: 2px;
}

.pagination-link:focus:not(.pagination-link-disabled) {
  background-color: var(--color-primary-light);
  color: var(--color-background);
}
</style>
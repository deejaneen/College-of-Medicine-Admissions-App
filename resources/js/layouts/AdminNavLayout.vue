<template>
    <div class="admin-layout">
        <nav class="sidebar" aria-label="Main navigation">
            <div class="sidebar-header">
                
                <div class="sidebar-title">
                    <!-- <img 
                        src="../../../storage/app/public/images/CAMLogo.png"
                        alt="CAM Logo"
                        class="logo-image logo-image__padding"
                    > -->
                    SLSU CAM Admissions Portal</div>
                <span class="material-icons collapse-icon">keyboard_double_arrow_left</span>
            </div>
            <ul class="sidebar-nav">
                    <Link 
                        href="/admin/dashboard" 
                        class="navbar-item"
                        :class="{ 'active': $page.url === '/admin/dashboard' }"
                    >
                        <span class="material-icons">dashboard</span>
                        <span class="nav-text">Dashboard</span>
                    </Link>
                    <Link 
                        href="/admin/applications" 
                        class="navbar-item"
                        :class="{ 'active': $page.url.startsWith('/admin/applications') }"
                    >
                        <span class="material-icons">insert_drive_file</span>
                        <span class="nav-text">Applications</span>
                    </Link>
                    <Link 
                        href="/admin/users" 
                        class="navbar-item"
                        :class="{ 'active': $page.url.startsWith('/admin/users') }"
                    >
                        <span class="material-icons">person</span>
                        <span class="nav-text">Users</span>
                    </Link>
                    <Link 
                        href="/admin/variables" 
                        class="navbar-item"
                        :class="{ 'active': $page.url.startsWith('/admin/variables') }"
                    >
                        <span class="material-icons">data_object</span>
                        <span class="nav-text">Variables</span>
                    </Link>
            </ul>
            <div class="logout-section">
                <div class="logout-text">Logout</div>
                <span class="material-icons">logout_icon</span>
            </div>
        </nav>
    </div>
</template>
<script lang="ts">
    export default {
        name: "AdminNavLayout"
    }
</script>
<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { onMounted } from 'vue'

onMounted(() => {
  if (!document.querySelector('link[href*="material-icons"]')) {
    const link = document.createElement('link')
    link.href = 'https://fonts.googleapis.com/icon?family=Material+Icons'
    link.rel = 'stylesheet'
    document.head.appendChild(link)
  }
})
</script>
<style scoped>
/* --- SIDEBAR BASE --- */
.sidebar {
    background: var(--color-primary-light);
    color: var(--color-background);
    max-width: 30rem;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
    box-shadow: 2px 0 8px rgba(0,0,0,0.1);
}

/* --- HEADER --- */
.sidebar-header {
    background: var(--color-primary);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: var(--space-6);
    gap: 1rem;
}

.sidebar-title {
    font-size: var(--font-size-h6);
    font-weight: 600;
    display: flex;
}

.collapse-icon {
    font-size: var(--font-size-h5);
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 6px;
    transition: opacity 0.2s, background 0.2s;
    flex-shrink: 0;
    width: auto;
    min-width: auto;
    margin-left: auto;
}

.collapse-icon:hover {
    background: rgba(255,255,255,0.15);
}

/* --- NAVIGATION --- */
.sidebar-nav {
    list-style: none;
    padding: 1rem 0;
    margin: 0;
}

.navbar-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: var(--space-3) var(--space-5);
    cursor: pointer;
    transition: background 0.25s, padding-left 0.25s;
    border-radius: 8px;
    opacity: .6;
}

.navbar-item:hover {
    background: rgba(255, 255, 255, 0.18);
    padding-left: 1.75rem;
    opacity: .9;
}

.navbar-item.active{
    opacity: 1;
}
.navbar-item .material-icons,
.nav-text,
.logout-text {
    font-size: var(--font-size-h6);
}

/* --- LOGOUT SECTION --- */
.logout-section {
    margin-top: auto;
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: var(--space-3) var(--space-5);
    cursor: pointer;
    transition: background 0.25s, padding-left 0.25s;
    border-radius: 2rem;
}

.logout-section:hover {
    background: rgba(255,255,255,0.18);
    padding-left: var(--space-5);
}

</style>

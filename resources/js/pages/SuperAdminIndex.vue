<template>
    <div class="admin-layout">
        <!-- Sidebar Navigation -->
        <nav 
            class="sidebar" 
            :class="{ 'collapsed': isSidebarCollapsed }" 
            aria-label="Main navigation"
        >
            <div class="sidebar-header">
                <div class="sidebar-title" v-if="!isSidebarCollapsed">
                    SLSU CAM SuperAdmin
                </div>
                <button 
                    @click="toggleSidebar" 
                    class="collapse-btn"
                    :title="isSidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
                    aria-label="Toggle sidebar"
                >
                    <span class="material-icons collapse-icon">
                        {{ isSidebarCollapsed ? 'keyboard_double_arrow_right' : 'keyboard_double_arrow_left' }}
                    </span>
                </button>
            </div>

            <!-- SUPERADMIN NAVIGATION -->
            <ul class="sidebar-nav">
                
                <!-- Dashboard -->
                <Link 
                    href="/superadmin/dashboard" 
                    class="navbar-item"
                    :class="{ 'active': $page.url === '/superadmin/dashboard', 'collapsed': isSidebarCollapsed }"
                    :title="isSidebarCollapsed ? 'Dashboard' : ''"
                >
                    <span class="material-icons nav-icon">dashboard</span>
                    <span class="nav-text" v-if="!isSidebarCollapsed">Dashboard</span>
                </Link>

                <!-- Add User -->
                <Link 
                    href="/superadmin/add-user" 
                    class="navbar-item"
                    :class="{ 'active': $page.url.startsWith('/superadmin/add-user'), 'collapsed': isSidebarCollapsed }"
                    :title="isSidebarCollapsed ? 'Add New Admin' : ''"
                >
                    <span class="material-icons nav-icon">person_add</span>
                    <span class="nav-text" v-if="!isSidebarCollapsed">Add New Admin</span>
                </Link>
                <!-- Add User -->
                <Link 
                    href="/superadmin/edit-user" 
                    class="navbar-item"
                    :class="{ 'active': $page.url.startsWith('/superadmin/edit-user'), 'collapsed': isSidebarCollapsed }"
                    :title="isSidebarCollapsed ? 'Admins' : ''"
                >
                    <span class="material-icons nav-icon">edit_square</span>
                    <span class="nav-text" v-if="!isSidebarCollapsed">Edit Admin Details</span>
                </Link>
            </ul>

            <!-- Logout section -->
            <div 
                class="logout-section"
                :class="{ 'collapsed': isSidebarCollapsed }"
                @click="showLogoutConfirmation"
                :title="isSidebarCollapsed ? 'Logout' : ''"
            >
                <div class="logout-text" v-if="!isSidebarCollapsed">Logout</div>
                <span class="material-icons">logout</span>
            </div>

            <!-- Tooltip -->
            <div 
                v-if="isSidebarCollapsed && tooltipText && tooltipPosition" 
                class="sidebar-tooltip"
                :style="{
                    top: tooltipPosition.top + 'px',
                    left: tooltipPosition.left + 'px'
                }"
            >
                {{ tooltipText }}
            </div>
            <!-- Logout Confirmation Modal -->
            <div v-if="showLogoutModal" class="modal-overlay">
                <div class="modal-content">
                    <div class="modal-icon warning">
                        <span class="material-icons">logout</span>
                    </div>
                    
                    <h3 class="modal-title">Confirm Logout</h3>
                    
                    <div class="modal-message">
                        <p>Are you sure you want to logout from the Super Admin dashboard?</p>
                    </div>

                    <div class="modal-actions">
                        <button class="btn-secondary" @click="cancelLogout" :disabled="isLoggingOut">
                            Cancel
                        </button>
                        <button class="btn-error" @click="logout" :disabled="isLoggingOut">
                            <span v-if="isLoggingOut" class="spinner"></span>
                            {{ isLoggingOut ? 'Logging out...' : 'Yes, Logout' }}
                        </button>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Mobile overlay -->
        <div 
            v-if="isMobile && !isSidebarCollapsed" 
            class="sidebar-overlay" 
            @click="closeSidebar"
        ></div>

        <main class="main-content" :class="{ 'collapsed': isSidebarCollapsed }">
            <slot />
        </main>
    </div>
</template>

<script lang="ts">
export default {
    name: 'SuperAdminIndex'
}
</script>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted, nextTick } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { watch } from 'vue'

// State
const isSidebarCollapsed = ref(false)
const isMobile = ref(false)
const tooltip = ref<HTMLElement | null>(null)
let currentTooltipItem: HTMLElement | null = null
const tooltipText = ref('')
const tooltipPosition = ref<{top: number, left: number} | null>(null)
const showLogoutModal = ref(false)
const isLoggingOut = ref(false)

// Load collapsed state from localStorage
onMounted(() => {
    // Load saved preference
    const savedState = localStorage.getItem('sidebar-collapsed')
    if (savedState !== null) {
        isSidebarCollapsed.value = JSON.parse(savedState)
    }
    
    // Check if mobile
    checkIfMobile()
    window.addEventListener('resize', checkIfMobile)
    
    // Setup tooltip events
    setupTooltip()
    
    // Load material icons if not already loaded
    if (!document.querySelector('link[href*="material-icons"]')) {
        const link = document.createElement('link')
        link.href = 'https://fonts.googleapis.com/icon?family=Material+Icons'
        link.rel = 'stylesheet'
        document.head.appendChild(link)
    }
})

onUnmounted(() => {
    window.removeEventListener('resize', checkIfMobile)
    removeTooltipEvents()
})

// Methods
function toggleSidebar() {
    isSidebarCollapsed.value = !isSidebarCollapsed.value
    localStorage.setItem('sidebar-collapsed', JSON.stringify(isSidebarCollapsed.value))
}

function closeSidebar() {
    if (isMobile.value) {
        isSidebarCollapsed.value = true
    }
}

function checkIfMobile() {
    isMobile.value = window.innerWidth < 768
    // Auto-collapse on mobile by default
    if (isMobile.value && !isSidebarCollapsed.value) {
        isSidebarCollapsed.value = true
    }
}

function showLogoutConfirmation() {
    showLogoutModal.value = true
}

function logout() {
    isLoggingOut.value = true
    showLogoutModal.value = false
    Inertia.post(route('superadmin.logout'))
}

function cancelLogout() {
    showLogoutModal.value = false
}


// Tooltip functionality for collapsed state
function setupTooltip() {
    const navItems = document.querySelectorAll('.navbar-item')
    
    navItems.forEach(item => {
        item.addEventListener('mouseenter', showTooltip)
        item.addEventListener('mouseleave', hideTooltip)
        item.addEventListener('focus', showTooltip)
        item.addEventListener('blur', hideTooltip)
    })
}

function removeTooltipEvents() {
    const navItems = document.querySelectorAll('.navbar-item')
    
    navItems.forEach(item => {
        item.removeEventListener('mouseenter', showTooltip)
        item.removeEventListener('mouseleave', hideTooltip)
        item.removeEventListener('focus', showTooltip)
        item.removeEventListener('blur', hideTooltip)
    })
}

function showTooltip(event: Event) {
    if (!isSidebarCollapsed.value) return
    
    const target = event.currentTarget as HTMLElement
    const title = target.getAttribute('title')
    
    if (title && tooltip.value) {
        currentTooltipItem = target
        const tooltipContent = tooltip.value.querySelector('.tooltip-content')
        if (tooltipContent) {
            tooltipContent.textContent = title
        }
        
        // Position tooltip
        const rect = target.getBoundingClientRect()
        tooltip.value.style.top = `${rect.top + window.scrollY}px`
        tooltip.value.style.left = `${rect.right + 10}px`
        tooltip.value.style.display = 'block'
        
        nextTick(() => {
            if (tooltip.value) {
                tooltip.value.classList.add('visible')
            }
        })
    }
}

function hideTooltip() {
    if (tooltip.value) {
        tooltip.value.classList.remove('visible')
        setTimeout(() => {
            if (tooltip.value && !currentTooltipItem?.matches(':hover')) {
                tooltip.value.style.display = 'none'
            }
        }, 200)
    }
    currentTooltipItem = null
}

// Watch for collapse state changes to update tooltip events
watch(isSidebarCollapsed, (newVal) => {
    nextTick(() => {
        removeTooltipEvents()
        if (newVal) {
            setupTooltip()
        }
    })
})
</script>

<style scoped>
/* --- MODAL STYLES --- */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    backdrop-filter: blur(4px);
    font-family: var(--font-alt);
}

.modal-content {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.modal-icon.warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.modal-icon .material-icons {
    font-size: var(--font-size-h4);
    color: white;
}

.modal-title {
    font-size: var(--font-size-h4);
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1rem;
    text-align: center;
    font-family: var(--font-alt);
}

.modal-message {
    color: #4b5563;
    line-height: 1.6;
    margin-bottom: 2rem;
    text-align: center;
}

.modal-message p {
    margin-bottom: 1rem;
}

.modal-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 2rem;
}

.btn-primary, .btn-secondary, .btn-error {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.2s ease;
    min-width: 150px;
    font-size: 1.2rem;
    font-family: var(--font-alt);
}

.btn-primary {
    background: #2563eb;
    color: white;
}

.btn-secondary {
    background: white;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-error {
    background: #ef4444;
    color: white;
}

.btn-primary:hover:not(:disabled),
.btn-error:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-secondary:hover:not(:disabled) {
    background: #f9fafb;
    border-color: #9ca3af;
}

.btn-primary:disabled,
.btn-secondary:disabled,
.btn-error:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Spinner for loading state */
.spinner {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s ease-in-out infinite;
    margin-right: 0.5rem;
    vertical-align: middle;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive styles for modal */
@media (max-width: 640px) {
    .modal-content {
        padding: 1.5rem;
        margin: 1rem;
    }
    
    .modal-actions {
        flex-direction: column;
    }
    
    .btn-primary, .btn-secondary, .btn-error {
        width: 100%;
        min-width: unset;
    }
    
    .modal-icon {
        width: 60px;
        height: 60px;
    }
    
    .modal-icon .material-icons {
        font-size: 2rem;
    }
}

/* --- LAYOUT --- */
.admin-layout {
    min-height: auto;
    height: auto;
}

/* --- SIDEBAR (Fixed Position) --- */
.sidebar {
    background: var(--color-primary-light);
    color: var(--color-background);
    width: 250px;
    min-height: 100vh;
    height: 100%;
    display: flex;
    flex-direction: column;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
    box-shadow: 2px 0 8px rgba(0,0,0,0.1);
    transition: width 0.3s ease;
    overflow-x: hidden;
}

.sidebar.collapsed {
    width: 70px;
}

/* --- MAIN CONTENT (Adjusts Based on Sidebar) --- */
.main-content {
    flex: 1;
    margin-left: 250px;
    transition: margin-left 0.3s ease;
    padding: 1rem;
    min-height: auto;
    height: auto;
    background: var(--color-background);
}

.main-content.collapsed {
    margin-left: 70px;
}

/* --- SIDEBAR HEADER --- */
.sidebar-header {
    background: var(--color-primary);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-6);
    gap: 1rem;
    min-height: 70px;
    position: relative;
}

.sidebar.collapsed .sidebar-header {
    justify-content: center;
    padding: var(--space-4);
}

.sidebar-title {
    font-size: var(--font-size-h6);
    font-weight: 600;
    display: flex;
    align-items: center;
    transition: opacity 0.3s ease;
    overflow: hidden;
}

.sidebar.collapsed .sidebar-title {
    opacity: 0;
    width: 0;
    height: 0;
    overflow: hidden;
    position: absolute;
}

.collapse-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
    color: var(--color-background);
    min-width: 40px;
    min-height: 40px;
}

.collapse-btn:hover {
    background: rgba(255, 255, 255, 0.15);
}

.collapse-icon {
    font-size: var(--font-size-h5);
    transition: transform 0.3s ease;
}

/* --- SIDEBAR NAVIGATION --- */
.sidebar-nav {
    list-style: none;
    padding: 1rem 0;
    margin: 0;
    flex: 1;
}

.navbar-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: var(--space-3) var(--space-5);
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 8px;
    opacity: 0.6;
    text-decoration: none;
    color: var(--color-background);
    margin: 0.25rem var(--space-3);
    position: relative;
    white-space: nowrap;
}

.navbar-item:hover {
    background: rgba(255, 255, 255, 0.18);
    opacity: 0.9;
}

.navbar-item.active {
    background: rgba(255, 255, 255, 0.25);
    opacity: 1;
    font-weight: 500;
}

.sidebar.collapsed .navbar-item {
    padding: var(--space-3) var(--space-4);
    justify-content: center;
    margin: 0.25rem 0.5rem;
}

.nav-icon {
    font-size: var(--font-size-h5);
    min-width: 24px;
    text-align: center;
}

.nav-text {
    font-size: var(--font-size-p);
    transition: opacity 0.3s ease, width 0.3s ease;
    overflow: hidden;
    white-space: nowrap;
}

.sidebar.collapsed .nav-text {
    opacity: 0;
    width: 0;
    position: absolute;
}

/* --- LOGOUT SECTION --- */
.logout-section {
    margin-top: auto;
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: var(--space-3) var(--space-5);
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 2rem;
    margin: var(--space-3);
    opacity: 0.7;
    color: var(--color-background);
}

.logout-section:hover {
    background: rgba(255, 255, 255, 0.18);
    opacity: 1;
}

.sidebar.collapsed .logout-section {
    justify-content: center;
    padding: var(--space-3) var(--space-4);
    margin: var(--space-3) 0.5rem;
}

.logout-text {
    font-size: var(--font-size-p);
    transition: opacity 0.3s ease, width 0.3s ease;
    overflow: hidden;
    white-space: nowrap;
}

.sidebar.collapsed .logout-text {
    opacity: 0;
    width: 0;
    position: absolute;
}

.logout-section .material-icons {
    font-size: var(--font-size-h5);
    min-width: 24px;
    text-align: center;
}

/* --- TOOLTIP --- */
.sidebar-tooltip {
    position: fixed;
    background: var(--color-primary);
    color: var(--color-background);
    padding: var(--space-2) var(--space-3);
    border-radius: var(--radius-m);
    font-size: var(--font-size-p);
    z-index: 1100;
    display: none;
    opacity: 0;
    transform: translateX(-10px);
    transition: opacity 0.2s ease, transform 0.2s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    pointer-events: none;
    white-space: nowrap;
}

.sidebar-tooltip.visible {
    opacity: 1;
    transform: translateX(0);
}

.sidebar-tooltip::before {
    content: '';
    position: absolute;
    left: -6px;
    top: 50%;
    transform: translateY(-50%);
    border-width: 6px 6px 6px 0;
    border-style: solid;
    border-color: transparent var(--color-primary) transparent transparent;
}

/* --- OVERLAY FOR MOBILE --- */
.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    display: none;
}

/* --- RESPONSIVE --- */
@media (max-width: 768px) {
    .sidebar {
        width: 70px;
        position: fixed;
        z-index: 1000;
        transition: width 0.3s ease;
    }
    
    .sidebar:not(.collapsed) {
        width: 250px; 
    }
    
    /* On mobile, always show the sidebar, just adjust width */
    .sidebar.collapsed {
        width: 70px;
    }
    
    .main-content,
    .main-content.collapsed {
        margin-left: 70px !important; 
    }
    
    .sidebar:not(.collapsed) ~ .main-content {
        margin-left: 250px !important; 
    }
    
    /* Overlay only needed when sidebar is expanded on mobile */
    .sidebar-overlay {
        display: none; 
    }
    
    .sidebar:not(.collapsed) ~ .sidebar-overlay {
        display: block; 
    }
    
    .sidebar-tooltip {
        display: none !important;
    }
}
</style>
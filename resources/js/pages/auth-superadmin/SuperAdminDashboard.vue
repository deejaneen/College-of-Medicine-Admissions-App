<template>
    <SuperAdminIndex>
        <div class="dashboard-container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Dashboard</h1>
            </div>

            <!-- Created User Logs Table -->
            <div class="content-card">
                <div class="card-header">
                    <div class="card-title-group">
                        <h6 class="card-title">System Activity Logs</h6>
                        <span class="card-subtitle">Most recent system activities</span>
                    </div>
                </div>
                <div class="card-content">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Action</th>
                                    <th>Table</th>
                                    <th>Details</th>
                                    <th>Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="logs.data.length === 0">
                                    <td colspan="5" class="empty-state">
                                        No recent logs to display.
                                    </td>
                                </tr>
                                <tr v-for="log in logs.data" :key="log.id" class="table-row">
                                    <td>
                                        <div class="user-cell">
                                             <span class="material-icons user-icon" :class="`icon-${log.performer_type}`">
                                                {{ log.performer_icon }}
                                            </span>
                                            <div class="performer-info">
                                                <span class="user-name">{{ log.performed_by }}</span>
                                                <span :class="`role-badge role-${log.performer_type}`">
                                                    {{ log.performer_type }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span :class="`action-badge action-${log.action.toLowerCase()}`">
                                            {{ log.action }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-name">{{ log.table_name }}</div>
                                    </td>
                                    <td>
                                        <div class="details-cell">
                                            {{ log.details || 'No additional details' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-cell">
                                            {{ formatDate(log.performed_at) }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination Component -->
                    <Pagination 
                        v-if="logs.links && logs.links.length > 3"
                        :links="logs.links"
                        :from="logs.meta?.from"
                        :to="logs.meta?.to"
                        :total="logs.meta?.total"
                        :current-page="logs.meta?.current_page"
                        :last-page="logs.meta?.last_page"
                    />
                </div>
            </div>

            <!-- Quick Actions Card (keep as-is) -->
            <div class="content-card">
                <!-- ... your existing quick actions content ... -->
            </div>
        </div>
    </SuperAdminIndex>
</template>

<script lang="ts" setup>
import SuperAdminIndex from '../SuperAdminIndex.vue';
import Pagination from '../auth-admin/Pagination.vue'; 

// Interfaces
interface LogEntry {
    id: number;
    action: string;
    table_name: string;
    performed_by: string; 
    performer_type: string; 
    performer_icon: string;
    performed_at: string;
    details?: string;
    admin?: {
        id: number;
        name: string;
        role: string;
    };
    user?: {
        id: number;
        name: string;
    };
}

interface PaginatedLogs {
    data: LogEntry[];
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
    meta: {
        current_page: number;
        from: number;
        to: number;
        total: number;
        last_page: number;
        per_page: number;
        path: string;
    };
}

interface Props {
    logs: PaginatedLogs;
}

// Define props without assigning to a variable if not used
const { logs } = defineProps<Props>();

// Helper function to format date
function formatDate(dateString: string) {
    if (!dateString) return 'N/A';
    
    const date = new Date(dateString);
    return date.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

</script>

<style scoped>
.dashboard-container {
    padding: var(--space-6);
    margin: 0 auto;
     background: var(--color-light-bg);
}

.page-header {
    margin-bottom: var(--space-8);
}

.page-title {
    font-size: var(--font-size-h4);
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: var(--space-2);
}

.page-subtitle {
    font-size: var(--font-size-p);
    color: var(--color-text-light);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(24rem, 1fr));
    gap: var(--space-4);
    margin-bottom: var(--space-8);
}

.stat-card {
    background: var(--color-background);
    border-radius: var(--radius-m);
    padding: var(--space-5);
    box-shadow: var(--elevation-1);
    border: 1px solid var(--color-border);
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: var(--space-4);
}

.stat-card:hover {
    box-shadow: var(--elevation-2);
    transform: translateY(-2px);
}

.stat-icon {
    width: 4.8rem;
    height: 4.8rem;
    border-radius: var(--radius-m);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-icon .material-icons {
    font-size: var(--font-size-h5);
    color: white;
}

.stat-card-primary .stat-icon {
    background: var(--gradient-primary);
}

.stat-card-success .stat-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.stat-card-info .stat-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
}

.stat-card-warning .stat-icon {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.stat-card-danger .stat-icon {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.stat-content {
    flex: 1;
    min-width: 0;
}

.stat-items:first-child {
    margin-bottom: var(--space-1);
}

.stat-value {
    font-size: var(--font-size-h5);
    font-weight: 600;
    color: var(--color-foreground);
    line-height: 1;
}

.stat-label {
    font-size: var(--font-size-p);
    font-weight: 500;
    color: var(--color-foreground);
    margin-bottom: var(--space-1);
}

.stat-change {
    font-size: 1.2rem;
    color: var(--color-text-light);
}
.role-badge {
    font-size: 1.4rem;
    text-transform: uppercase;
}
/* Content Cards with Left Border */
.content-card {
    background: var(--color-background);
    border-radius: var(--radius-m);
    box-shadow: var(--elevation-1);
    border: 1px solid var(--color-border);
    border-left: 4px solid var(--color-primary);
    margin-bottom: var(--space-6);
    overflow: hidden;
    max-width: 120rem;
    margin: var(--space-6) auto;
    
}

.card-header {
    padding: var(--space-5) var(--space-6);
    border-bottom: 1px solid var(--color-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--color-light-bg-40);
}

.card-title-group {
    display: flex;
    flex-direction: column;
    gap: var(--space-1);
}

.card-title {
    font-size: var(--font-size-h6);
    font-weight: 600;
    color: var(--color-primary);
    margin: 0;
    text-transform: uppercase;
}

.card-subtitle {
    font-size: var(--font-size-p);
    color: var(--color-text-light);
}

.card-action {
    background: var(--color-primary);
    color: white;
    border: none;
    padding: var(--space-2) var(--space-4);
    border-radius: var(--radius-s);
    font-size: var(--font-size-p);
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: var(--space-2);
    transition: background 0.2s ease;
}

.card-action:hover {
    background: var(--color-primary-80);
}

.card-content {
    padding: var(--space-6);
}

/* Table Styles */
.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    text-align: left;
    padding: var(--space-3) var(--space-4);
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--color-text-light);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid var(--color-border);
    white-space: nowrap;
}

.data-table td {
    padding: var(--space-4);
    border-bottom: 1px solid var(--color-border);
}

.data-table tbody tr:last-child td {
    border-bottom: none;
}

.table-row:hover {
    background: var(--color-light-bg-40);
}

.user-cell {
    display: flex;
    align-items: center;
    gap: var(--space-2);
}

.user-icon {
    color: var(--color-primary);
    font-size: var(--font-size-h6);
}

.user-name { 
    font-size: var(--font-size-p);
    font-weight: 500;
    color: var(--color-foreground);
    margin-right: var(--space-2);
}

.action-badge {
    display: inline-block;
    padding: var(--space-1) var(--space-3);
    border-radius: var(--radius-s);
    font-size: 1.2rem;
    font-weight: 500;
    text-transform: capitalize;
}

.action-create {
    background: var(--color-bright-green-25);
    color: var(--color-primary);
}

.action-update {
    background: var(--color-blue-green-15);
    color: var(--color-primary);
}

.action-delete {
    background: var(--color-rejected);
    color: var(--color-rejected-text);
}

.table-name {
    font-weight: 500;
    color: var(--color-foreground);
}

.details-cell {
    color: var(--color-text-light);
    font-size: 1.2rem;
    max-width: 20rem;
    overflow: hidden;
    text-overflow: ellipsis;
}

.date-cell {
    color: var(--color-text-light);
    font-size: 1.2rem;
    white-space: nowrap;
}

.empty-state {
    text-align: center;
    padding: var(--space-8) !important;
    color: var(--color-text-light);
    font-style: italic;
}

/* Quick Actions Grid with Color Coding */
.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(28rem, 1fr));
    gap: var(--space-4);
}

.action-item {
    display: flex;
    align-items: center;
    padding: var(--space-5);
    background: var(--color-background);
    border: 1px solid var(--color-border);
    border-left: 4px solid var(--color-primary);
    border-radius: var(--radius-m);
    text-decoration: none;
    color: var(--color-foreground);
    transition: all 0.2s ease;
    gap: var(--space-4);
}

.action-item:hover {
    background: var(--color-light-bg-40);
    border-color: var(--color-primary);
    transform: translateY(-2px);
    box-shadow: var(--elevation-2);
}

/* Color-coded left borders for different action items */
.action-item-admin {
    border-left-color: #10b981;
}

.action-item-application {
    border-left-color: #3b82f6;
}

.action-item-period {
    border-left-color: #f59e0b;
}

.action-item-user {
    border-left-color: #ef4444;
}

.action-icon {
    width: 4.8rem;
    height: 4.8rem;
    border-radius: var(--radius-m);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: var(--gradient-primary);
}

.action-icon .material-icons {
    font-size: var(--font-size-h5);
    color: white;
}

.action-content {
    flex: 1;
    min-width: 0;
}

.action-title {
    font-size: var(--font-size-p);
    font-weight: 600;
    margin-bottom: var(--space-1);
    color: var(--color-foreground);
}

.action-description {
    font-size: 1.2rem;
    color: var(--color-text-light);
    line-height: 1.4;
}

.action-arrow {
    color: var(--color-primary);
    font-size: var(--font-size-h6);
    opacity: 0.7;
    transition: all 0.2s ease;
}

.action-item:hover .action-arrow {
    opacity: 1;
    transform: translateX(var(--space-1));
}
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: var(--space-4);
    gap: var(--space-2);
}

.pagination-link {
    padding: 0.5rem 1rem;
    border-radius: var(--radius-s);
    border: 1px solid var(--color-border);
    cursor: pointer;
    background: var(--color-light-bg);
    transition: all 0.2s ease;
}

.pagination-link-active {
    background: var(--color-primary);
    color: var(--color-background);
    font-weight: 600;
}

.pagination-link:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-container {
        padding: var(--space-4);
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .card-header {
        flex-direction: column;
        gap: var(--space-4);
        align-items: flex-start;
    }
    
    .card-action {
        align-self: flex-start;
    }
    
    .data-table {
        font-size: 1.2rem;
    }
    
    .data-table th,
    .data-table td {
        padding: var(--space-2) var(--space-3);
    }
}
</style>
<?php
/**
 * BCN Admin Dashboard Template
 * 
 * @package BCN_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get current user
$current_user = wp_get_current_user();
$user_name = $current_user->display_name ?: $current_user->user_login;

// Get dashboard data
$total_members = wp_count_users()['total_users'];
$new_members_this_month = bcn_get_new_members_this_month();
$active_members = bcn_get_active_members();
$upcoming_events = bcn_get_upcoming_events(5);
$recent_posts = get_posts(array('numberposts' => 5, 'post_status' => 'publish'));
$draft_posts = get_posts(array('numberposts' => 5, 'post_status' => 'draft'));
?>

<div class="bcn-admin-dashboard">
    <!-- Dashboard Header -->
    <div class="bcn-admin-content-header">
        <h1 class="bcn-admin-content-title">Welcome back, <?php echo esc_html($user_name); ?>!</h1>
        <p class="bcn-admin-content-subtitle">Here's what's happening with your Buffalo Cannabis Network</p>
    </div>

    <!-- Dashboard Widgets -->
    <div class="bcn-dashboard-widgets">
        <!-- Member Overview Widget -->
        <div class="bcn-dashboard-widget">
            <div class="bcn-dashboard-widget-header">
                <h3 class="bcn-dashboard-widget-title">
                    <span class="bcn-dashboard-widget-icon">👥</span>
                    Member Overview
                </h3>
            </div>
            <div class="bcn-dashboard-widget-content">
                <div class="bcn-stats-grid">
                    <div class="bcn-stat">
                        <span class="bcn-stat-number" data-stat="total"><?php echo esc_html($total_members); ?></span>
                        <span class="bcn-stat-label">Total Members</span>
                    </div>
                    <div class="bcn-stat">
                        <span class="bcn-stat-number" data-stat="new"><?php echo esc_html($new_members_this_month); ?></span>
                        <span class="bcn-stat-label">New This Month</span>
                    </div>
                    <div class="bcn-stat">
                        <span class="bcn-stat-number" data-stat="active"><?php echo esc_html($active_members); ?></span>
                        <span class="bcn-stat-label">Active Members</span>
                    </div>
                </div>
                <div class="bcn-widget-actions">
                    <a href="<?php echo admin_url('admin.php?page=bcn-members'); ?>" class="bcn-btn bcn-btn-primary bcn-btn-sm">View All Members</a>
                    <a href="<?php echo admin_url('user-new.php'); ?>" class="bcn-btn bcn-btn-secondary bcn-btn-sm">Add Member</a>
                </div>
            </div>
        </div>

        <!-- Upcoming Events Widget -->
        <div class="bcn-dashboard-widget">
            <div class="bcn-dashboard-widget-header">
                <h3 class="bcn-dashboard-widget-title">
                    <span class="bcn-dashboard-widget-icon">📅</span>
                    Upcoming Events
                </h3>
            </div>
            <div class="bcn-dashboard-widget-content">
                <?php if (!empty($upcoming_events)) : ?>
                    <div class="bcn-event-list">
                        <?php foreach ($upcoming_events as $event) : ?>
                            <div class="bcn-event-item">
                                <div class="bcn-event-info">
                                    <h4 class="bcn-event-title"><?php echo esc_html($event->post_title); ?></h4>
                                    <div class="bcn-event-meta">
                                        <span class="bcn-event-date">
                                            <?php 
                                            $event_date = get_post_meta($event->ID, '_bcn_event_date', true);
                                            $event_time = get_post_meta($event->ID, '_bcn_event_time', true);
                                            if ($event_date) {
                                                echo date('M j, Y', strtotime($event_date));
                                                if ($event_time) {
                                                    echo ' ' . date('g:i A', strtotime($event_time));
                                                }
                                            }
                                            ?>
                                        </span>
                                        <span class="bcn-event-location">
                                            <?php echo esc_html(get_post_meta($event->ID, '_bcn_event_location', true)); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="bcn-event-actions">
                                    <a href="<?php echo get_edit_post_link($event->ID); ?>" class="bcn-btn bcn-btn-sm bcn-btn-secondary">Edit</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p class="bcn-text-muted">No upcoming events scheduled.</p>
                <?php endif; ?>
                <div class="bcn-widget-actions">
                    <a href="<?php echo admin_url('admin.php?page=bcn-events'); ?>" class="bcn-btn bcn-btn-primary bcn-btn-sm">View All Events</a>
                    <a href="<?php echo admin_url('post-new.php?post_type=bcn_event'); ?>" class="bcn-btn bcn-btn-secondary bcn-btn-sm">Add Event</a>
                </div>
            </div>
        </div>

        <!-- Content Pipeline Widget -->
        <div class="bcn-dashboard-widget">
            <div class="bcn-dashboard-widget-header">
                <h3 class="bcn-dashboard-widget-title">
                    <span class="bcn-dashboard-widget-icon">📝</span>
                    Content Pipeline
                </h3>
            </div>
            <div class="bcn-dashboard-widget-content">
                <div class="bcn-content-pipeline">
                    <div class="bcn-pipeline-section">
                        <h4 class="bcn-pipeline-section-title">Drafts (<?php echo count($draft_posts); ?>)</h4>
                        <?php if (!empty($draft_posts)) : ?>
                            <div class="bcn-pipeline-items">
                                <?php foreach ($draft_posts as $draft) : ?>
                                    <div class="bcn-pipeline-item" data-id="<?php echo $draft->ID; ?>">
                                        <div class="bcn-pipeline-item-content">
                                            <h5 class="bcn-pipeline-item-title"><?php echo esc_html($draft->post_title); ?></h5>
                                            <span class="bcn-pipeline-item-meta">
                                                <?php echo get_the_date('M j, Y', $draft); ?>
                                            </span>
                                        </div>
                                        <div class="bcn-pipeline-item-actions">
                                            <a href="<?php echo get_edit_post_link($draft->ID); ?>" class="bcn-btn bcn-btn-sm bcn-btn-secondary">Edit</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <p class="bcn-text-muted">No drafts in progress.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="bcn-widget-actions">
                    <a href="<?php echo admin_url('admin.php?page=bcn-content'); ?>" class="bcn-btn bcn-btn-primary bcn-btn-sm">View All Content</a>
                    <a href="<?php echo admin_url('post-new.php'); ?>" class="bcn-btn bcn-btn-secondary bcn-btn-sm">New Post</a>
                </div>
            </div>
        </div>

        <!-- Quick Actions Widget -->
        <div class="bcn-dashboard-widget">
            <div class="bcn-dashboard-widget-header">
                <h3 class="bcn-dashboard-widget-title">
                    <span class="bcn-dashboard-widget-icon">⚡</span>
                    Quick Actions
                </h3>
            </div>
            <div class="bcn-dashboard-widget-content">
                <div class="bcn-quick-actions-grid">
                    <a href="<?php echo admin_url('post-new.php?post_type=bcn_event'); ?>" class="bcn-quick-action">
                        <span class="bcn-quick-action-icon">📅</span>
                        <span class="bcn-quick-action-text">New Event</span>
                    </a>
                    <a href="<?php echo admin_url('post-new.php'); ?>" class="bcn-quick-action">
                        <span class="bcn-quick-action-icon">📝</span>
                        <span class="bcn-quick-action-text">New Post</span>
                    </a>
                    <a href="<?php echo admin_url('user-new.php'); ?>" class="bcn-quick-action">
                        <span class="bcn-quick-action-icon">👤</span>
                        <span class="bcn-quick-action-text">Add Member</span>
                    </a>
                    <a href="<?php echo admin_url('upload.php'); ?>" class="bcn-quick-action">
                        <span class="bcn-quick-action-icon">📁</span>
                        <span class="bcn-quick-action-text">Upload Media</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- System Status Widget -->
        <div class="bcn-dashboard-widget">
            <div class="bcn-dashboard-widget-header">
                <h3 class="bcn-dashboard-widget-title">
                    <span class="bcn-dashboard-widget-icon">🔧</span>
                    System Status
                </h3>
            </div>
            <div class="bcn-dashboard-widget-content">
                <div class="bcn-system-status">
                    <div class="bcn-status-item">
                        <span class="bcn-status-label">WordPress Version:</span>
                        <span class="bcn-status-value"><?php echo get_bloginfo('version'); ?></span>
                    </div>
                    <div class="bcn-status-item">
                        <span class="bcn-status-label">PHP Version:</span>
                        <span class="bcn-status-value"><?php echo PHP_VERSION; ?></span>
                    </div>
                    <div class="bcn-status-item">
                        <span class="bcn-status-label">Memory Usage:</span>
                        <span class="bcn-status-value"><?php echo size_format(memory_get_usage(true)); ?> / <?php echo ini_get('memory_limit'); ?></span>
                    </div>
                    <div class="bcn-status-item">
                        <span class="bcn-status-label">Last Backup:</span>
                        <span class="bcn-status-value"><?php echo bcn_get_last_backup_date(); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Widget -->
        <div class="bcn-dashboard-widget bcn-dashboard-widget-wide">
            <div class="bcn-dashboard-widget-header">
                <h3 class="bcn-dashboard-widget-title">
                    <span class="bcn-dashboard-widget-icon">📊</span>
                    Recent Activity
                </h3>
            </div>
            <div class="bcn-dashboard-widget-content">
                <div class="bcn-activity-feed">
                    <?php
                    $recent_activity = bcn_get_recent_activity(10);
                    if (!empty($recent_activity)) :
                        foreach ($recent_activity as $activity) :
                    ?>
                        <div class="bcn-activity-item">
                            <div class="bcn-activity-icon">
                                <?php echo $activity['icon']; ?>
                            </div>
                            <div class="bcn-activity-content">
                                <p class="bcn-activity-text"><?php echo esc_html($activity['text']); ?></p>
                                <span class="bcn-activity-time"><?php echo $activity['time']; ?></span>
                            </div>
                        </div>
                    <?php
                        endforeach;
                    else :
                    ?>
                        <p class="bcn-text-muted">No recent activity.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard Specific Styles */
.bcn-admin-dashboard {
    max-width: 1400px;
    margin: 0 auto;
}

.bcn-dashboard-widgets {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: var(--bcn-admin-spacing-xl);
    margin-bottom: var(--bcn-admin-spacing-xl);
}

.bcn-dashboard-widget-wide {
    grid-column: 1 / -1;
}

.bcn-widget-actions {
    margin-top: var(--bcn-admin-spacing-lg);
    display: flex;
    gap: var(--bcn-admin-spacing-sm);
    flex-wrap: wrap;
}

.bcn-event-list {
    display: flex;
    flex-direction: column;
    gap: var(--bcn-admin-spacing-md);
}

.bcn-event-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--bcn-admin-spacing-md);
    background: var(--bcn-admin-gray-50);
    border-radius: var(--bcn-admin-radius-md);
    border-left: 3px solid var(--bcn-admin-primary);
}

.bcn-event-info h4 {
    margin: 0 0 var(--bcn-admin-spacing-xs) 0;
    font-size: var(--bcn-admin-font-size-sm);
    font-weight: 600;
    color: var(--bcn-admin-gray-800);
}

.bcn-event-meta {
    display: flex;
    flex-direction: column;
    gap: var(--bcn-admin-spacing-xs);
    font-size: var(--bcn-admin-font-size-xs);
    color: var(--bcn-admin-gray-600);
}

.bcn-pipeline-section {
    margin-bottom: var(--bcn-admin-spacing-lg);
}

.bcn-pipeline-section-title {
    font-size: var(--bcn-admin-font-size-sm);
    font-weight: 600;
    color: var(--bcn-admin-gray-800);
    margin: 0 0 var(--bcn-admin-spacing-md) 0;
}

.bcn-pipeline-items {
    display: flex;
    flex-direction: column;
    gap: var(--bcn-admin-spacing-sm);
}

.bcn-pipeline-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--bcn-admin-spacing-sm) var(--bcn-admin-spacing-md);
    background: var(--bcn-admin-gray-50);
    border-radius: var(--bcn-admin-radius-md);
    cursor: move;
    transition: background-color var(--bcn-admin-transition-fast);
}

.bcn-pipeline-item:hover {
    background: var(--bcn-admin-gray-100);
}

.bcn-pipeline-item-content h5 {
    margin: 0 0 var(--bcn-admin-spacing-xs) 0;
    font-size: var(--bcn-admin-font-size-sm);
    font-weight: 500;
    color: var(--bcn-admin-gray-800);
}

.bcn-pipeline-item-meta {
    font-size: var(--bcn-admin-font-size-xs);
    color: var(--bcn-admin-gray-600);
}

.bcn-quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--bcn-admin-spacing-md);
}

.bcn-quick-action {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: var(--bcn-admin-spacing-lg);
    background: var(--bcn-admin-gray-50);
    border-radius: var(--bcn-admin-radius-md);
    text-decoration: none;
    color: var(--bcn-admin-gray-700);
    transition: all var(--bcn-admin-transition-fast);
    border: 2px solid transparent;
}

.bcn-quick-action:hover {
    background: var(--bcn-admin-white);
    border-color: var(--bcn-admin-primary);
    color: var(--bcn-admin-primary);
    transform: translateY(-2px);
    box-shadow: var(--bcn-admin-shadow-md);
}

.bcn-quick-action-icon {
    font-size: var(--bcn-admin-font-size-2xl);
    margin-bottom: var(--bcn-admin-spacing-sm);
}

.bcn-quick-action-text {
    font-size: var(--bcn-admin-font-size-sm);
    font-weight: 500;
}

.bcn-system-status {
    display: flex;
    flex-direction: column;
    gap: var(--bcn-admin-spacing-sm);
}

.bcn-status-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--bcn-admin-spacing-sm) 0;
    border-bottom: 1px solid var(--bcn-admin-gray-200);
}

.bcn-status-item:last-child {
    border-bottom: none;
}

.bcn-status-label {
    font-size: var(--bcn-admin-font-size-sm);
    color: var(--bcn-admin-gray-600);
}

.bcn-status-value {
    font-size: var(--bcn-admin-font-size-sm);
    font-weight: 500;
    color: var(--bcn-admin-gray-800);
}

.bcn-activity-feed {
    display: flex;
    flex-direction: column;
    gap: var(--bcn-admin-spacing-md);
}

.bcn-activity-item {
    display: flex;
    align-items: flex-start;
    gap: var(--bcn-admin-spacing-md);
    padding: var(--bcn-admin-spacing-md);
    background: var(--bcn-admin-gray-50);
    border-radius: var(--bcn-admin-radius-md);
}

.bcn-activity-icon {
    font-size: var(--bcn-admin-font-size-lg);
    flex-shrink: 0;
}

.bcn-activity-content {
    flex: 1;
}

.bcn-activity-text {
    margin: 0 0 var(--bcn-admin-spacing-xs) 0;
    font-size: var(--bcn-admin-font-size-sm);
    color: var(--bcn-admin-gray-800);
}

.bcn-activity-time {
    font-size: var(--bcn-admin-font-size-xs);
    color: var(--bcn-admin-gray-600);
}

@media (max-width: 768px) {
    .bcn-dashboard-widgets {
        grid-template-columns: 1fr;
    }
    
    .bcn-quick-actions-grid {
        grid-template-columns: 1fr;
    }
    
    .bcn-event-item,
    .bcn-pipeline-item {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--bcn-admin-spacing-sm);
    }
}
</style>

/**
 * BCN Custom Admin Theme - Main JavaScript
 */

class BCNAdmin {
    constructor() {
        this.init();
    }

    init() {
        this.setupDashboard();
        this.setupForms();
        this.setupAutomation();
        this.setupNotifications();
        this.setupMobileMenu();
        this.setupTooltips();
        this.setupModals();
    }

    setupDashboard() {
        // Custom dashboard widgets
        this.createMemberOverview();
        this.createEventCalendar();
        this.createContentPipeline();
        this.setupDashboardCharts();
    }

    setupForms() {
        // Enhanced form functionality
        this.setupAutoSave();
        this.setupValidation();
        this.setupBulkActions();
        this.setupFileUploads();
        this.setupDatePickers();
    }

    setupAutomation() {
        // Automation workflows
        this.setupContentAutomation();
        this.setupMemberAutomation();
        this.setupEventAutomation();
        this.setupEmailAutomation();
    }

    setupNotifications() {
        // Notification system
        this.setupToastNotifications();
        this.setupProgressBars();
        this.setupAlerts();
    }

    setupMobileMenu() {
        const menuToggle = document.querySelector('.bcn-mobile-menu-toggle');
        const sidebar = document.querySelector('.bcn-admin-sidebar');
        
        if (menuToggle && sidebar) {
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
    }

    setupTooltips() {
        // Initialize tooltips
        const tooltipElements = document.querySelectorAll('[data-tooltip]');
        tooltipElements.forEach(element => {
            this.createTooltip(element);
        });
    }

    setupModals() {
        // Initialize modals
        const modalTriggers = document.querySelectorAll('[data-modal]');
        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const modalId = trigger.getAttribute('data-modal');
                this.openModal(modalId);
            });
        });

        // Close modals
        const modalCloses = document.querySelectorAll('.bcn-modal-close');
        modalCloses.forEach(close => {
            close.addEventListener('click', () => {
                this.closeModal(close.closest('.bcn-modal'));
            });
        });

        // Close modal on backdrop click
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('bcn-modal')) {
                this.closeModal(e.target);
            }
        });
    }

    // Dashboard Methods
    createMemberOverview() {
        const memberOverview = document.querySelector('.bcn-member-overview');
        if (!memberOverview) return;

        // Add real-time updates
        this.updateMemberStats();
        setInterval(() => this.updateMemberStats(), 30000); // Update every 30 seconds
    }

    createEventCalendar() {
        const eventCalendar = document.querySelector('.bcn-event-calendar');
        if (!eventCalendar) return;

        // Initialize calendar functionality
        this.initializeEventCalendar();
    }

    createContentPipeline() {
        const contentPipeline = document.querySelector('.bcn-content-pipeline');
        if (!contentPipeline) return;

        // Add drag and drop functionality
        this.setupContentDragDrop();
    }

    setupDashboardCharts() {
        // Initialize charts if Chart.js is available
        if (typeof Chart !== 'undefined') {
            this.createMemberChart();
            this.createEventChart();
            this.createContentChart();
        }
    }

    // Form Methods
    setupAutoSave() {
        const forms = document.querySelectorAll('.bcn-auto-save');
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    this.autoSaveForm(form);
                });
            });
        });
    }

    setupValidation() {
        const forms = document.querySelectorAll('.bcn-form');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });
        });
    }

    setupBulkActions() {
        const bulkActionForms = document.querySelectorAll('.bcn-bulk-actions');
        bulkActionForms.forEach(form => {
            const selectAll = form.querySelector('.bcn-select-all');
            const checkboxes = form.querySelectorAll('input[type="checkbox"]');
            const actionSelect = form.querySelector('.bcn-bulk-action-select');
            const actionButton = form.querySelector('.bcn-bulk-action-button');

            if (selectAll) {
                selectAll.addEventListener('change', () => {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = selectAll.checked;
                    });
                    this.updateBulkActionButton(actionButton, checkboxes);
                });
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    this.updateBulkActionButton(actionButton, checkboxes);
                });
            });

            if (actionButton) {
                actionButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.executeBulkAction(form, actionSelect.value, checkboxes);
                });
            }
        });
    }

    setupFileUploads() {
        const fileInputs = document.querySelectorAll('.bcn-file-upload');
        fileInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                this.handleFileUpload(e.target);
            });
        });
    }

    setupDatePickers() {
        const dateInputs = document.querySelectorAll('.bcn-date-picker');
        dateInputs.forEach(input => {
            // Initialize date picker if available
            if (typeof flatpickr !== 'undefined') {
                flatpickr(input, {
                    dateFormat: 'Y-m-d',
                    enableTime: input.classList.contains('bcn-datetime-picker')
                });
            }
        });
    }

    // Automation Methods
    setupContentAutomation() {
        // Auto-publish scheduled content
        this.checkScheduledContent();
        setInterval(() => this.checkScheduledContent(), 60000); // Check every minute
    }

    setupMemberAutomation() {
        // Auto-sync member data
        this.syncMemberData();
        setInterval(() => this.syncMemberData(), 300000); // Sync every 5 minutes
    }

    setupEventAutomation() {
        // Auto-send event reminders
        this.sendEventReminders();
        setInterval(() => this.sendEventReminders(), 3600000); // Check every hour
    }

    setupEmailAutomation() {
        // Auto-send emails
        this.processEmailQueue();
        setInterval(() => this.processEmailQueue(), 300000); // Process every 5 minutes
    }

    // Notification Methods
    setupToastNotifications() {
        // Create toast container
        if (!document.querySelector('.bcn-toast-container')) {
            const container = document.createElement('div');
            container.className = 'bcn-toast-container';
            document.body.appendChild(container);
        }
    }

    setupProgressBars() {
        const progressBars = document.querySelectorAll('.bcn-progress-bar');
        progressBars.forEach(bar => {
            this.animateProgressBar(bar);
        });
    }

    setupAlerts() {
        const alerts = document.querySelectorAll('.bcn-alert');
        alerts.forEach(alert => {
            this.setupAlertDismiss(alert);
        });
    }

    // Utility Methods
    createTooltip(element) {
        const tooltipText = element.getAttribute('data-tooltip');
        const tooltip = document.createElement('div');
        tooltip.className = 'bcn-tooltip';
        tooltip.textContent = tooltipText;
        document.body.appendChild(tooltip);

        element.addEventListener('mouseenter', () => {
            this.showTooltip(tooltip, element);
        });

        element.addEventListener('mouseleave', () => {
            this.hideTooltip(tooltip);
        });
    }

    showTooltip(tooltip, element) {
        const rect = element.getBoundingClientRect();
        tooltip.style.display = 'block';
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
    }

    hideTooltip(tooltip) {
        tooltip.style.display = 'none';
    }

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('show');
            document.body.classList.add('bcn-modal-open');
        }
    }

    closeModal(modal) {
        if (modal) {
            modal.classList.remove('show');
            document.body.classList.remove('bcn-modal-open');
        }
    }

    updateMemberStats() {
        // AJAX call to update member statistics
        fetch(bcn_admin_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'bcn_update_member_stats',
                nonce: bcn_admin_ajax.nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateMemberStatsDisplay(data.data);
            }
        })
        .catch(error => {
            console.error('Error updating member stats:', error);
        });
    }

    updateMemberStatsDisplay(stats) {
        const totalMembers = document.querySelector('.bcn-stat-number[data-stat="total"]');
        const newMembers = document.querySelector('.bcn-stat-number[data-stat="new"]');
        const activeMembers = document.querySelector('.bcn-stat-number[data-stat="active"]');

        if (totalMembers) totalMembers.textContent = stats.total;
        if (newMembers) newMembers.textContent = stats.new;
        if (activeMembers) activeMembers.textContent = stats.active;
    }

    initializeEventCalendar() {
        // Initialize event calendar functionality
        const calendar = document.querySelector('.bcn-event-calendar');
        if (calendar) {
            // Add calendar functionality here
        }
    }

    setupContentDragDrop() {
        const pipelineItems = document.querySelectorAll('.bcn-pipeline-item');
        pipelineItems.forEach(item => {
            item.draggable = true;
            item.addEventListener('dragstart', (e) => {
                e.dataTransfer.setData('text/plain', item.dataset.id);
            });
        });
    }

    createMemberChart() {
        const ctx = document.getElementById('memberChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'New Members',
                        data: [12, 19, 3, 5, 2, 3],
                        borderColor: 'rgb(52, 152, 219)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    }

    createEventChart() {
        const ctx = document.getElementById('eventChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Workshops', 'Networking', 'Education', 'Social'],
                    datasets: [{
                        label: 'Events',
                        data: [12, 19, 3, 5],
                        backgroundColor: 'rgba(52, 152, 219, 0.8)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    }

    createContentChart() {
        const ctx = document.getElementById('contentChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Published', 'Draft', 'Scheduled'],
                    datasets: [{
                        data: [30, 10, 5],
                        backgroundColor: [
                            'rgb(39, 174, 96)',
                            'rgb(243, 156, 18)',
                            'rgb(52, 152, 219)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    }

    autoSaveForm(form) {
        const formData = new FormData(form);
        formData.append('action', 'bcn_auto_save_form');
        formData.append('nonce', bcn_admin_ajax.nonce);

        fetch(bcn_admin_ajax.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showToast('Form auto-saved', 'success');
            }
        })
        .catch(error => {
            console.error('Auto-save error:', error);
        });
    }

    validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        return isValid;
    }

    updateBulkActionButton(button, checkboxes) {
        const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);
        if (button) {
            button.disabled = checkedBoxes.length === 0;
            button.textContent = `Apply to ${checkedBoxes.length} items`;
        }
    }

    executeBulkAction(form, action, checkboxes) {
        const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);
        if (checkedBoxes.length === 0) return;

        const formData = new FormData();
        formData.append('action', 'bcn_bulk_action');
        formData.append('bulk_action', action);
        formData.append('nonce', bcn_admin_ajax.nonce);
        
        checkedBoxes.forEach(checkbox => {
            formData.append('item_ids[]', checkbox.value);
        });

        fetch(bcn_admin_ajax.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showToast(`Bulk action completed: ${data.message}`, 'success');
                location.reload();
            } else {
                this.showToast(`Error: ${data.message}`, 'error');
            }
        })
        .catch(error => {
            this.showToast('Error executing bulk action', 'error');
        });
    }

    handleFileUpload(input) {
        const files = input.files;
        const preview = input.nextElementSibling;
        
        if (preview && preview.classList.contains('bcn-file-preview')) {
            preview.innerHTML = '';
            
            Array.from(files).forEach(file => {
                const fileItem = document.createElement('div');
                fileItem.className = 'bcn-file-item';
                fileItem.innerHTML = `
                    <span class="bcn-file-name">${file.name}</span>
                    <span class="bcn-file-size">${this.formatFileSize(file.size)}</span>
                `;
                preview.appendChild(fileItem);
            });
        }
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    checkScheduledContent() {
        // Check for content that should be published
        fetch(bcn_admin_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'bcn_check_scheduled_content',
                nonce: bcn_admin_ajax.nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.published > 0) {
                this.showToast(`${data.data.published} items published automatically`, 'success');
            }
        });
    }

    syncMemberData() {
        // Sync member data with CRM
        fetch(bcn_admin_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'bcn_sync_member_data',
                nonce: bcn_admin_ajax.nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.synced > 0) {
                console.log(`${data.data.synced} members synced with CRM`);
            }
        });
    }

    sendEventReminders() {
        // Send event reminders
        fetch(bcn_admin_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'bcn_send_event_reminders',
                nonce: bcn_admin_ajax.nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.sent > 0) {
                console.log(`${data.data.sent} event reminders sent`);
            }
        });
    }

    processEmailQueue() {
        // Process email queue
        fetch(bcn_admin_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'bcn_process_email_queue',
                nonce: bcn_admin_ajax.nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.processed > 0) {
                console.log(`${data.data.processed} emails processed`);
            }
        });
    }

    showToast(message, type = 'info') {
        const container = document.querySelector('.bcn-toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `bcn-toast bcn-toast-${type}`;
        toast.innerHTML = `
            <div class="bcn-toast-content">
                <span class="bcn-toast-message">${message}</span>
                <button class="bcn-toast-close">&times;</button>
            </div>
        `;

        container.appendChild(toast);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            toast.remove();
        }, 5000);

        // Close button
        toast.querySelector('.bcn-toast-close').addEventListener('click', () => {
            toast.remove();
        });
    }

    animateProgressBar(bar) {
        const progress = bar.querySelector('.bcn-progress-fill');
        const percentage = bar.dataset.percentage;
        
        if (progress && percentage) {
            progress.style.width = percentage + '%';
        }
    }

    setupAlertDismiss(alert) {
        const dismissBtn = alert.querySelector('.bcn-alert-dismiss');
        if (dismissBtn) {
            dismissBtn.addEventListener('click', () => {
                alert.remove();
            });
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new BCNAdmin();
});

// Export for use in other scripts
window.BCNAdmin = BCNAdmin;

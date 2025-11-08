/**
 * Submission Admin JavaScript
 * 
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

jQuery(document).ready(function($) {
    'use strict';

    // Handle approval actions
    $('.approve-btn, .approve-submission').on('click', function(e) {
        e.preventDefault();
        
        const postId = $(this).data('id');
        const type = $(this).data('type');
        const button = $(this);
        
        if (confirm('Are you sure you want to approve this submission?')) {
            button.prop('disabled', true).text('Approving...');
            
            $.ajax({
                url: bcnSubmissionAdmin.ajaxurl,
                type: 'POST',
                data: {
                    action: 'approve_submission',
                    post_id: postId,
                    type: type,
                    nonce: bcnSubmissionAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        showMessage('Submission approved successfully!', 'success');
                        button.closest('.submission-card').fadeOut();
                    } else {
                        showMessage('Failed to approve submission: ' + response.data, 'error');
                        button.prop('disabled', false).text('Approve');
                    }
                },
                error: function() {
                    showMessage('Error approving submission', 'error');
                    button.prop('disabled', false).text('Approve');
                }
            });
        }
    });

    // Handle rejection actions
    $('.reject-btn, .reject-submission').on('click', function(e) {
        e.preventDefault();
        
        const postId = $(this).data('id');
        const type = $(this).data('type');
        const button = $(this);
        
        // Show rejection reason modal
        const reason = prompt('Please provide a reason for rejection (optional):');
        
        if (reason !== null) { // User didn't cancel
            button.prop('disabled', true).text('Rejecting...');
            
            $.ajax({
                url: bcnSubmissionAdmin.ajaxurl,
                type: 'POST',
                data: {
                    action: 'reject_submission',
                    post_id: postId,
                    type: type,
                    reason: reason,
                    nonce: bcnSubmissionAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        showMessage('Submission rejected', 'success');
                        button.closest('.submission-card').fadeOut();
                    } else {
                        showMessage('Failed to reject submission: ' + response.data, 'error');
                        button.prop('disabled', false).text('Reject');
                    }
                },
                error: function() {
                    showMessage('Error rejecting submission', 'error');
                    button.prop('disabled', false).text('Reject');
                }
            });
        }
    });

    // Handle feature actions
    $('.feature-btn, .feature-submission').on('click', function(e) {
        e.preventDefault();
        
        const postId = $(this).data('id');
        const type = $(this).data('type');
        const button = $(this);
        
        if (confirm('Are you sure you want to feature this submission?')) {
            button.prop('disabled', true).text('Featuring...');
            
            $.ajax({
                url: bcnSubmissionAdmin.ajaxurl,
                type: 'POST',
                data: {
                    action: 'feature_submission',
                    post_id: postId,
                    type: type,
                    nonce: bcnSubmissionAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        showMessage('Submission featured successfully!', 'success');
                        button.text('Featured').removeClass('button-secondary').addClass('button-primary');
                    } else {
                        showMessage('Failed to feature submission: ' + response.data, 'error');
                        button.prop('disabled', false).text('Feature');
                    }
                },
                error: function() {
                    showMessage('Error featuring submission', 'error');
                    button.prop('disabled', false).text('Feature');
                }
            });
        }
    });

    // Bulk actions
    $('#bulk-action-selector-top, #bulk-action-selector-bottom').on('change', function() {
        const action = $(this).val();
        const submitButton = $(this).closest('.tablenav').find('.button.action');
        
        if (action === 'approve' || action === 'reject' || action === 'feature') {
            submitButton.prop('disabled', false);
        } else {
            submitButton.prop('disabled', true);
        }
    });

    // Handle bulk actions
    $('.button.action').on('click', function(e) {
        const action = $(this).closest('.tablenav').find('select').val();
        const checkedBoxes = $('input[name="post[]"]:checked');
        
        if (checkedBoxes.length === 0) {
            alert('Please select at least one item.');
            e.preventDefault();
            return;
        }
        
        if (action === 'approve' || action === 'reject' || action === 'feature') {
            e.preventDefault();
            
            const postIds = checkedBoxes.map(function() {
                return $(this).val();
            }).get();
            
            if (confirm(`Are you sure you want to ${action} ${postIds.length} submission(s)?`)) {
                processBulkAction(action, postIds);
            }
        }
    });

    // Process bulk actions
    function processBulkAction(action, postIds) {
        const submitButton = $('.button.action');
        submitButton.prop('disabled', true).text('Processing...');
        
        let completed = 0;
        const total = postIds.length;
        
        postIds.forEach(function(postId) {
            $.ajax({
                url: bcnSubmissionAdmin.ajaxurl,
                type: 'POST',
                data: {
                    action: action + '_submission',
                    post_id: postId,
                    type: 'bulk',
                    nonce: bcnSubmissionAdmin.nonce
                },
                success: function(response) {
                    completed++;
                    
                    if (completed === total) {
                        showMessage(`${total} submission(s) ${action}d successfully!`, 'success');
                        location.reload();
                    }
                },
                error: function() {
                    completed++;
                    
                    if (completed === total) {
                        showMessage(`Error processing some submissions`, 'error');
                        location.reload();
                    }
                }
            });
        });
    }

    // Auto-save reviewer notes
    $('textarea[name="reviewer_notes"]').on('blur', function() {
        const textarea = $(this);
        const postId = textarea.closest('.postbox').find('input[name="post_ID"]').val();
        const notes = textarea.val();
        
        // Save notes via AJAX
        $.ajax({
            url: bcnSubmissionAdmin.ajaxurl,
            type: 'POST',
            data: {
                action: 'save_reviewer_notes',
                post_id: postId,
                notes: notes,
                nonce: bcnSubmissionAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    textarea.addClass('saved');
                    setTimeout(() => {
                        textarea.removeClass('saved');
                    }, 2000);
                }
            }
        });
    });

    // Real-time submission counter
    function updateSubmissionCounts() {
        $.ajax({
            url: bcnSubmissionAdmin.ajaxurl,
            type: 'POST',
            data: {
                action: 'get_submission_counts',
                nonce: bcnSubmissionAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    const counts = response.data;
                    
                    // Update dashboard counts
                    $('.overview-card h2').eq(0).text(counts.pending_testimonials);
                    $('.overview-card h2').eq(1).text(counts.pending_blogs);
                    $('.overview-card h2').eq(2).text(counts.total_pending);
                    
                    // Update admin menu badge
                    updateAdminMenuBadge(counts.total_pending);
                }
            }
        });
    }

    // Update admin menu badge
    function updateAdminMenuBadge(count) {
        const menuItem = $('#toplevel_page_bcn-submissions');
        const badge = menuItem.find('.awaiting-mod');
        
        if (count > 0) {
            if (badge.length === 0) {
                menuItem.append('<span class="awaiting-mod">' + count + '</span>');
            } else {
                badge.text(count);
            }
        } else {
            badge.remove();
        }
    }

    // Initialize real-time updates
    if ($('.bcn-submission-admin').length) {
        // Update counts every 30 seconds
        setInterval(updateSubmissionCounts, 30000);
        
        // Initial count update
        updateSubmissionCounts();
    }

    // Submission status filters
    $('.submission-filter').on('change', function() {
        const filter = $(this).val();
        const container = $(this).closest('.submission-container');
        
        if (filter === 'all') {
            container.find('.submission-card').show();
        } else {
            container.find('.submission-card').hide();
            container.find('.submission-card[data-status="' + filter + '"]').show();
        }
    });

    // Search submissions
    $('.submission-search').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        const container = $(this).closest('.submission-container');
        
        container.find('.submission-card').each(function() {
            const title = $(this).find('.submission-header h3').text().toLowerCase();
            const content = $(this).find('.submission-content').text().toLowerCase();
            
            if (title.includes(searchTerm) || content.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Show message to user
    function showMessage(message, type) {
        const messageClass = type === 'success' ? 'notice notice-success' : 'notice notice-error';
        const messageElement = $('<div class="' + messageClass + ' is-dismissible"><p>' + message + '</p></div>');
        
        // Remove existing notices
        $('.notice').remove();
        
        // Add new notice
        $('.wrap h1').after(messageElement);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            messageElement.fadeOut(() => {
                messageElement.remove();
            });
        }, 5000);
        
        // Make dismissible
        messageElement.on('click', '.notice-dismiss', function() {
            messageElement.fadeOut();
        });
    }

    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + Enter to approve
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 13) {
            const focusedCard = $('.submission-card:hover');
            if (focusedCard.length) {
                focusedCard.find('.approve-btn').click();
            }
        }
        
        // Ctrl/Cmd + R to reject
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 82) {
            const focusedCard = $('.submission-card:hover');
            if (focusedCard.length) {
                focusedCard.find('.reject-btn').click();
            }
        }
    });

    // Submission preview modal
    $('.preview-submission').on('click', function(e) {
        e.preventDefault();
        
        const postId = $(this).data('id');
        const type = $(this).data('type');
        
        // Open preview in new window
        const previewUrl = ajaxurl + '?action=preview_submission&post_id=' + postId + '&type=' + type;
        window.open(previewUrl, '_blank', 'width=800,height=600');
    });

    // Quick edit submission
    $('.quick-edit-submission').on('click', function(e) {
        e.preventDefault();
        
        const postId = $(this).data('id');
        const editUrl = 'post.php?post=' + postId + '&action=edit';
        window.open(editUrl, '_blank');
    });

    // Batch operations
    $('.batch-approve').on('click', function(e) {
        e.preventDefault();
        
        const checkedBoxes = $('input[name="post[]"]:checked');
        
        if (checkedBoxes.length === 0) {
            alert('Please select submissions to approve.');
            return;
        }
        
        if (confirm('Are you sure you want to approve all selected submissions?')) {
            const postIds = checkedBoxes.map(function() {
                return $(this).val();
            }).get();
            
            processBulkAction('approve', postIds);
        }
    });

    // Export submissions
    $('.export-submissions').on('click', function(e) {
        e.preventDefault();
        
        const format = $(this).data('format');
        const exportUrl = ajaxurl + '?action=export_submissions&format=' + format + '&nonce=' + bcnSubmissionAdmin.nonce;
        
        // Create hidden iframe to trigger download
        const iframe = $('<iframe>', {
            src: exportUrl,
            style: 'display: none;'
        });
        
        $('body').append(iframe);
        
        setTimeout(() => {
            iframe.remove();
        }, 1000);
    });
});

/**
 * Admin JavaScript for BCN Theme
 * Handles admin-specific functionality and enhancements
 */

(function($) {
    'use strict';

    // Initialize admin functionality
    function initAdmin() {
        initMemberManagement();
        initThemeCustomizer();
        initMediaUploader();
        initFormValidation();
    }

    // Member management enhancements
    function initMemberManagement() {
        // Enhanced member list table
        const $memberTable = $('.wp-list-table');
        if ($memberTable.length && $memberTable.find('th').text().includes('Member')) {
            addMemberTableEnhancements();
        }

        // Member registration form enhancements
        const $memberForm = $('#member-registration-form');
        if ($memberForm.length) {
            enhanceMemberForm();
        }
    }

    function addMemberTableEnhancements() {
        // Add bulk actions for member management
        const $bulkActions = $('.bulkactions');
        if ($bulkActions.length) {
            const bulkOptions = `
                <option value="set-premier">Set as Premier Member</option>
                <option value="set-pro">Set as Pro Member</option>
                <option value="set-featured">Set as Featured</option>
                <option value="unset-featured">Remove Featured Status</option>
            `;
            $bulkActions.find('select[name="action"]').append(bulkOptions);
        }

        // Add quick edit functionality
        $('.wp-list-table tbody tr').each(function() {
            const $row = $(this);
            const memberId = $row.find('.check-column input').val();
            
            // Add quick action buttons
            const quickActions = `
                <div class="row-actions">
                    <span class="edit"><a href="#" data-action="quick-edit" data-member-id="${memberId}">Quick Edit</a> | </span>
                    <span class="view"><a href="#" data-action="view-profile" data-member-id="${memberId}">View Profile</a> | </span>
                    <span class="featured"><a href="#" data-action="toggle-featured" data-member-id="${memberId}">Toggle Featured</a></span>
                </div>
            `;
            $row.find('.column-title').append(quickActions);
        });

        // Handle quick actions
        $(document).on('click', '[data-action="quick-edit"]', function(e) {
            e.preventDefault();
            const memberId = $(this).data('member-id');
            openQuickEditModal(memberId);
        });

        $(document).on('click', '[data-action="toggle-featured"]', function(e) {
            e.preventDefault();
            const memberId = $(this).data('member-id');
            toggleMemberFeatured(memberId);
        });
    }

    function enhanceMemberForm() {
        // Add form validation
        const $form = $('#member-registration-form');
        
        $form.on('submit', function(e) {
            if (!validateMemberForm()) {
                e.preventDefault();
                return false;
            }
        });

        // Real-time validation
        $form.find('input[required], textarea[required]').on('blur', function() {
            validateField($(this));
        });

        // Logo upload preview
        $form.find('input[type="file"]').on('change', function() {
            previewLogoUpload(this);
        });
    }

    function validateMemberForm() {
        let isValid = true;
        const $form = $('#member-registration-form');
        
        $form.find('input[required], textarea[required]').each(function() {
            if (!validateField($(this))) {
                isValid = false;
            }
        });

        return isValid;
    }

    function validateField($field) {
        const value = $field.val().trim();
        const fieldName = $field.attr('name');
        let isValid = true;
        let errorMessage = '';

        // Remove existing error styling
        $field.removeClass('error');
        $field.siblings('.error-message').remove();

        // Validation rules
        if ($field.prop('required') && !value) {
            isValid = false;
            errorMessage = 'This field is required.';
        } else if (fieldName === 'email' && value && !isValidEmail(value)) {
            isValid = false;
            errorMessage = 'Please enter a valid email address.';
        } else if (fieldName === 'phone' && value && !isValidPhone(value)) {
            isValid = false;
            errorMessage = 'Please enter a valid phone number.';
        }

        // Show error if invalid
        if (!isValid) {
            $field.addClass('error');
            $field.after(`<div class="error-message">${errorMessage}</div>`);
        }

        return isValid;
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function isValidPhone(phone) {
        const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
        return phoneRegex.test(phone.replace(/[\s\-\(\)]/g, ''));
    }

    function previewLogoUpload(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const $preview = $('.logo-preview');
                if (!$preview.length) {
                    $(input).after('<div class="logo-preview"><img src="" alt="Logo Preview"></div>');
                }
                $('.logo-preview img').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Theme customizer enhancements
    function initThemeCustomizer() {
        // Add custom controls for member features
        if (typeof wp !== 'undefined' && wp.customize) {
            addMemberCustomizerControls();
        }
    }

    function addMemberCustomizerControls() {
        // Add section for member features
        wp.customize.addSection('bcn_member_features', {
            title: 'Member Features',
            priority: 120
        });

        // Add control for marquee speed
        wp.customize.addControl('bcn_marquee_speed', {
            type: 'select',
            section: 'bcn_member_features',
            label: 'Marquee Speed',
            choices: {
                'slow': 'Slow',
                'medium': 'Medium',
                'fast': 'Fast'
            }
        });

        // Add control for member cards per page
        wp.customize.addControl('bcn_members_per_page', {
            type: 'number',
            section: 'bcn_member_features',
            label: 'Members Per Page',
            input_attrs: {
                min: 1,
                max: 50
            }
        });
    }

    // Media uploader enhancements
    function initMediaUploader() {
        // Enhanced media uploader for member logos
        $(document).on('click', '.upload-logo-button', function(e) {
            e.preventDefault();
            openMediaUploader($(this));
        });
    }

    function openMediaUploader($button) {
        if (typeof wp !== 'undefined' && wp.media) {
            const mediaUploader = wp.media({
                title: 'Choose Member Logo',
                button: {
                    text: 'Use as Logo'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                const $input = $button.siblings('input[type="hidden"]');
                const $preview = $button.siblings('.logo-preview');
                
                $input.val(attachment.id);
                if ($preview.length) {
                    $preview.html(`<img src="${attachment.sizes.thumbnail.url}" alt="Logo Preview">`);
                }
            });

            mediaUploader.open();
        }
    }

    // Form validation utilities
    function initFormValidation() {
        // Add custom validation messages
        addValidationMessages();
        
        // Add form submission feedback
        addSubmissionFeedback();
    }

    function addValidationMessages() {
        // Custom validation for member forms
        const $forms = $('form[data-validate="member"]');
        
        $forms.on('submit', function(e) {
            const $form = $(this);
            let hasErrors = false;

            // Clear previous errors
            $form.find('.error').removeClass('error');
            $form.find('.error-message').remove();

            // Validate required fields
            $form.find('[required]').each(function() {
                if (!$(this).val().trim()) {
                    $(this).addClass('error');
                    $(this).after('<div class="error-message">This field is required.</div>');
                    hasErrors = true;
                }
            });

            if (hasErrors) {
                e.preventDefault();
                showNotification('Please correct the errors below.', 'error');
            }
        });
    }

    function addSubmissionFeedback() {
        // Show success/error messages
        $(document).on('member-form-submitted', function(e, data) {
            if (data.success) {
                showNotification('Member registration successful!', 'success');
            } else {
                showNotification('Registration failed. Please try again.', 'error');
            }
        });
    }

    function showNotification(message, type) {
        const $notification = $(`
            <div class="bcn-notification bcn-notification--${type}">
                <span class="bcn-notification__message">${message}</span>
                <button class="bcn-notification__close">&times;</button>
            </div>
        `);

        $('body').append($notification);

        // Auto-hide after 5 seconds
        setTimeout(() => {
            $notification.fadeOut(() => $notification.remove());
        }, 5000);

        // Manual close
        $notification.find('.bcn-notification__close').on('click', function() {
            $notification.fadeOut(() => $notification.remove());
        });
    }

    // Utility functions
    function openQuickEditModal(memberId) {
        // Implementation for quick edit modal
        console.log('Opening quick edit for member:', memberId);
    }

    function toggleMemberFeatured(memberId) {
        // AJAX call to toggle featured status
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'toggle_member_featured',
                member_id: memberId,
                nonce: bcn_admin.nonce
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Member featured status updated.', 'success');
                    location.reload();
                } else {
                    showNotification('Failed to update featured status.', 'error');
                }
            }
        });
    }

    // Initialize when document is ready
    $(document).ready(function() {
        initAdmin();
    });

})(jQuery);
<?php
/**
 * Events Custom Post Type
 * 
 * @package BCN_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class BCN_Events_CPT {
    
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_filter('manage_bcn_event_posts_columns', array($this, 'custom_columns'));
        add_action('manage_bcn_event_posts_custom_column', array($this, 'custom_column_content'), 10, 2);
        add_filter('manage_edit-bcn_event_sortable_columns', array($this, 'sortable_columns'));
        add_action('pre_get_posts', array($this, 'custom_orderby'));
    }
    
    public function register_post_type() {
        $labels = array(
            'name'                  => 'Events',
            'singular_name'         => 'Event',
            'menu_name'             => 'Events',
            'name_admin_bar'        => 'Event',
            'archives'              => 'Event Archives',
            'attributes'            => 'Event Attributes',
            'parent_item_colon'     => 'Parent Event:',
            'all_items'             => 'All Events',
            'add_new_item'          => 'Add New Event',
            'add_new'               => 'Add New',
            'new_item'              => 'New Event',
            'edit_item'             => 'Edit Event',
            'update_item'           => 'Update Event',
            'view_item'             => 'View Event',
            'view_items'            => 'View Events',
            'search_items'          => 'Search Events',
            'not_found'             => 'Not found',
            'not_found_in_trash'    => 'Not found in Trash',
            'featured_image'        => 'Event Image',
            'set_featured_image'    => 'Set event image',
            'remove_featured_image' => 'Remove event image',
            'use_featured_image'    => 'Use as event image',
            'insert_into_item'      => 'Insert into event',
            'uploaded_to_this_item' => 'Uploaded to this event',
            'items_list'            => 'Events list',
            'items_list_navigation' => 'Events list navigation',
            'filter_items_list'     => 'Filter events list',
        );
        
        $args = array(
            'label'                 => 'Event',
            'description'           => 'BCN Events',
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields'),
            'taxonomies'            => array('event_type', 'event_category'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-calendar-alt',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rest_base'             => 'events',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        );
        
        register_post_type('bcn_event', $args);
    }
    
    public function register_taxonomies() {
        // Event Type Taxonomy
        $event_type_labels = array(
            'name'                       => 'Event Types',
            'singular_name'              => 'Event Type',
            'menu_name'                  => 'Event Types',
            'all_items'                  => 'All Event Types',
            'parent_item'                => 'Parent Event Type',
            'parent_item_colon'          => 'Parent Event Type:',
            'new_item_name'              => 'New Event Type Name',
            'add_new_item'               => 'Add New Event Type',
            'edit_item'                  => 'Edit Event Type',
            'update_item'                => 'Update Event Type',
            'view_item'                  => 'View Event Type',
            'separate_items_with_commas' => 'Separate event types with commas',
            'add_or_remove_items'        => 'Add or remove event types',
            'choose_from_most_used'      => 'Choose from the most used',
            'popular_items'              => 'Popular Event Types',
            'search_items'               => 'Search Event Types',
            'not_found'                  => 'Not Found',
            'no_terms'                   => 'No event types',
            'items_list'                 => 'Event types list',
            'items_list_navigation'      => 'Event types list navigation',
        );
        
        $event_type_args = array(
            'labels'                     => $event_type_labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
        );
        
        register_taxonomy('event_type', array('bcn_event'), $event_type_args);
        
        // Event Category Taxonomy
        $event_category_labels = array(
            'name'                       => 'Event Categories',
            'singular_name'              => 'Event Category',
            'menu_name'                  => 'Event Categories',
            'all_items'                  => 'All Event Categories',
            'parent_item'                => 'Parent Event Category',
            'parent_item_colon'          => 'Parent Event Category:',
            'new_item_name'              => 'New Event Category Name',
            'add_new_item'               => 'Add New Event Category',
            'edit_item'                  => 'Edit Event Category',
            'update_item'                => 'Update Event Category',
            'view_item'                  => 'View Event Category',
            'separate_items_with_commas' => 'Separate event categories with commas',
            'add_or_remove_items'        => 'Add or remove event categories',
            'choose_from_most_used'      => 'Choose from the most used',
            'popular_items'              => 'Popular Event Categories',
            'search_items'               => 'Search Event Categories',
            'not_found'                  => 'Not Found',
            'no_terms'                   => 'No event categories',
            'items_list'                 => 'Event categories list',
            'items_list_navigation'      => 'Event categories list navigation',
        );
        
        $event_category_args = array(
            'labels'                     => $event_category_labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
        );
        
        register_taxonomy('event_category', array('bcn_event'), $event_category_args);
    }
    
    public function add_meta_boxes() {
        add_meta_box(
            'bcn_event_details',
            'Event Details',
            array($this, 'event_details_meta_box'),
            'bcn_event',
            'normal',
            'high'
        );
        
        add_meta_box(
            'bcn_event_registration',
            'Registration Settings',
            array($this, 'event_registration_meta_box'),
            'bcn_event',
            'side',
            'default'
        );
        
        add_meta_box(
            'bcn_event_crm',
            'CRM Integration',
            array($this, 'event_crm_meta_box'),
            'bcn_event',
            'side',
            'default'
        );
    }
    
    public function event_details_meta_box($post) {
        wp_nonce_field('bcn_event_meta_box', 'bcn_event_meta_box_nonce');
        
        $event_date = get_post_meta($post->ID, '_bcn_event_date', true);
        $event_end_date = get_post_meta($post->ID, '_bcn_event_end_date', true);
        $event_time = get_post_meta($post->ID, '_bcn_event_time', true);
        $event_end_time = get_post_meta($post->ID, '_bcn_event_end_time', true);
        $event_location = get_post_meta($post->ID, '_bcn_event_location', true);
        $event_address = get_post_meta($post->ID, '_bcn_event_address', true);
        $event_capacity = get_post_meta($post->ID, '_bcn_event_capacity', true);
        $event_price = get_post_meta($post->ID, '_bcn_event_price', true);
        $event_organizer = get_post_meta($post->ID, '_bcn_event_organizer', true);
        $event_contact_email = get_post_meta($post->ID, '_bcn_event_contact_email', true);
        $event_contact_phone = get_post_meta($post->ID, '_bcn_event_contact_phone', true);
        ?>
        <div class="bcn-form-group">
            <label for="bcn_event_date" class="bcn-form-label">Event Date *</label>
            <input type="date" id="bcn_event_date" name="bcn_event_date" value="<?php echo esc_attr($event_date); ?>" class="bcn-form-control" required>
        </div>
        
        <div class="bcn-form-group">
            <label for="bcn_event_end_date" class="bcn-form-label">End Date</label>
            <input type="date" id="bcn_event_end_date" name="bcn_event_end_date" value="<?php echo esc_attr($event_end_date); ?>" class="bcn-form-control">
        </div>
        
        <div class="bcn-form-group">
            <label for="bcn_event_time" class="bcn-form-label">Start Time</label>
            <input type="time" id="bcn_event_time" name="bcn_event_time" value="<?php echo esc_attr($event_time); ?>" class="bcn-form-control">
        </div>
        
        <div class="bcn-form-group">
            <label for="bcn_event_end_time" class="bcn-form-label">End Time</label>
            <input type="time" id="bcn_event_end_time" name="bcn_event_end_time" value="<?php echo esc_attr($event_end_time); ?>" class="bcn-form-control">
        </div>
        
        <div class="bcn-form-group">
            <label for="bcn_event_location" class="bcn-form-label">Location *</label>
            <input type="text" id="bcn_event_location" name="bcn_event_location" value="<?php echo esc_attr($event_location); ?>" class="bcn-form-control" required>
        </div>
        
        <div class="bcn-form-group">
            <label for="bcn_event_address" class="bcn-form-label">Address</label>
            <textarea id="bcn_event_address" name="bcn_event_address" class="bcn-form-control" rows="3"><?php echo esc_textarea($event_address); ?></textarea>
        </div>
        
        <div class="bcn-form-group">
            <label for="bcn_event_capacity" class="bcn-form-label">Capacity</label>
            <input type="number" id="bcn_event_capacity" name="bcn_event_capacity" value="<?php echo esc_attr($event_capacity); ?>" class="bcn-form-control" min="1">
        </div>
        
        <div class="bcn-form-group">
            <label for="bcn_event_price" class="bcn-form-label">Price</label>
            <input type="number" id="bcn_event_price" name="bcn_event_price" value="<?php echo esc_attr($event_price); ?>" class="bcn-form-control" step="0.01" min="0">
        </div>
        
        <div class="bcn-form-group">
            <label for="bcn_event_organizer" class="bcn-form-label">Organizer</label>
            <input type="text" id="bcn_event_organizer" name="bcn_event_organizer" value="<?php echo esc_attr($event_organizer); ?>" class="bcn-form-control">
        </div>
        
        <div class="bcn-form-group">
            <label for="bcn_event_contact_email" class="bcn-form-label">Contact Email</label>
            <input type="email" id="bcn_event_contact_email" name="bcn_event_contact_email" value="<?php echo esc_attr($event_contact_email); ?>" class="bcn-form-control">
        </div>
        
        <div class="bcn-form-group">
            <label for="bcn_event_contact_phone" class="bcn-form-label">Contact Phone</label>
            <input type="tel" id="bcn_event_contact_phone" name="bcn_event_contact_phone" value="<?php echo esc_attr($event_contact_phone); ?>" class="bcn-form-control">
        </div>
        <?php
    }
    
    public function event_registration_meta_box($post) {
        $registration_required = get_post_meta($post->ID, '_bcn_registration_required', true);
        $registration_url = get_post_meta($post->ID, '_bcn_registration_url', true);
        $registration_deadline = get_post_meta($post->ID, '_bcn_registration_deadline', true);
        $registration_open = get_post_meta($post->ID, '_bcn_registration_open', true);
        ?>
        <div class="bcn-form-group">
            <label class="bcn-form-label">
                <input type="checkbox" name="bcn_registration_required" value="1" <?php checked($registration_required, 1); ?>>
                Registration Required
            </label>
        </div>
        
        <div class="bcn-form-group">
            <label for="bcn_registration_url" class="bcn-form-label">Registration URL</label>
            <input type="url" id="bcn_registration_url" name="bcn_registration_url" value="<?php echo esc_attr($registration_url); ?>" class="bcn-form-control">
        </div>
        
        <div class="bcn-form-group">
            <label for="bcn_registration_deadline" class="bcn-form-label">Registration Deadline</label>
            <input type="datetime-local" id="bcn_registration_deadline" name="bcn_registration_deadline" value="<?php echo esc_attr($registration_deadline); ?>" class="bcn-form-control">
        </div>
        
        <div class="bcn-form-group">
            <label class="bcn-form-label">
                <input type="checkbox" name="bcn_registration_open" value="1" <?php checked($registration_open, 1); ?>>
                Registration Open
            </label>
        </div>
        <?php
    }
    
    public function event_crm_meta_box($post) {
        $crm_event_id = get_post_meta($post->ID, '_bcn_crm_event_id', true);
        $crm_sync_status = get_post_meta($post->ID, '_bcn_crm_sync_status', true);
        $crm_last_sync = get_post_meta($post->ID, '_bcn_crm_last_sync', true);
        ?>
        <div class="bcn-form-group">
            <label for="bcn_crm_event_id" class="bcn-form-label">CRM Event ID</label>
            <input type="text" id="bcn_crm_event_id" name="bcn_crm_event_id" value="<?php echo esc_attr($crm_event_id); ?>" class="bcn-form-control" readonly>
        </div>
        
        <div class="bcn-form-group">
            <label for="bcn_crm_sync_status" class="bcn-form-label">Sync Status</label>
            <select id="bcn_crm_sync_status" name="bcn_crm_sync_status" class="bcn-form-control">
                <option value="pending" <?php selected($crm_sync_status, 'pending'); ?>>Pending</option>
                <option value="synced" <?php selected($crm_sync_status, 'synced'); ?>>Synced</option>
                <option value="error" <?php selected($crm_sync_status, 'error'); ?>>Error</option>
            </select>
        </div>
        
        <div class="bcn-form-group">
            <label for="bcn_crm_last_sync" class="bcn-form-label">Last Sync</label>
            <input type="datetime-local" id="bcn_crm_last_sync" name="bcn_crm_last_sync" value="<?php echo esc_attr($crm_last_sync); ?>" class="bcn-form-control" readonly>
        </div>
        
        <div class="bcn-form-group">
            <button type="button" class="bcn-btn bcn-btn-primary" id="bcn_sync_event">Sync with CRM</button>
        </div>
        <?php
    }
    
    public function save_meta_boxes($post_id) {
        if (!isset($_POST['bcn_event_meta_box_nonce']) || !wp_verify_nonce($_POST['bcn_event_meta_box_nonce'], 'bcn_event_meta_box')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save event details
        $fields = array(
            'bcn_event_date',
            'bcn_event_end_date',
            'bcn_event_time',
            'bcn_event_end_time',
            'bcn_event_location',
            'bcn_event_address',
            'bcn_event_capacity',
            'bcn_event_price',
            'bcn_event_organizer',
            'bcn_event_contact_email',
            'bcn_event_contact_phone',
            'bcn_registration_required',
            'bcn_registration_url',
            'bcn_registration_deadline',
            'bcn_registration_open',
            'bcn_crm_event_id',
            'bcn_crm_sync_status',
            'bcn_crm_last_sync'
        );
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_' . $field, $value);
            } else {
                delete_post_meta($post_id, '_' . $field);
            }
        }
    }
    
    public function custom_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['event_date'] = 'Event Date';
        $new_columns['event_location'] = 'Location';
        $new_columns['event_capacity'] = 'Capacity';
        $new_columns['event_price'] = 'Price';
        $new_columns['event_type'] = 'Type';
        $new_columns['crm_sync'] = 'CRM Sync';
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }
    
    public function custom_column_content($column, $post_id) {
        switch ($column) {
            case 'event_date':
                $event_date = get_post_meta($post_id, '_bcn_event_date', true);
                $event_time = get_post_meta($post_id, '_bcn_event_time', true);
                if ($event_date) {
                    $date = date('M j, Y', strtotime($event_date));
                    if ($event_time) {
                        $date .= ' ' . date('g:i A', strtotime($event_time));
                    }
                    echo $date;
                } else {
                    echo '—';
                }
                break;
                
            case 'event_location':
                $location = get_post_meta($post_id, '_bcn_event_location', true);
                echo $location ? esc_html($location) : '—';
                break;
                
            case 'event_capacity':
                $capacity = get_post_meta($post_id, '_bcn_event_capacity', true);
                echo $capacity ? esc_html($capacity) : '—';
                break;
                
            case 'event_price':
                $price = get_post_meta($post_id, '_bcn_event_price', true);
                if ($price) {
                    echo '$' . number_format($price, 2);
                } else {
                    echo 'Free';
                }
                break;
                
            case 'event_type':
                $terms = get_the_terms($post_id, 'event_type');
                if ($terms && !is_wp_error($terms)) {
                    $term_names = array();
                    foreach ($terms as $term) {
                        $term_names[] = $term->name;
                    }
                    echo implode(', ', $term_names);
                } else {
                    echo '—';
                }
                break;
                
            case 'crm_sync':
                $sync_status = get_post_meta($post_id, '_bcn_crm_sync_status', true);
                $last_sync = get_post_meta($post_id, '_bcn_crm_last_sync', true);
                
                $status_class = 'bcn-sync-pending';
                $status_text = 'Pending';
                
                switch ($sync_status) {
                    case 'synced':
                        $status_class = 'bcn-sync-success';
                        $status_text = 'Synced';
                        break;
                    case 'error':
                        $status_class = 'bcn-sync-error';
                        $status_text = 'Error';
                        break;
                }
                
                echo '<span class="' . $status_class . '">' . $status_text . '</span>';
                if ($last_sync) {
                    echo '<br><small>' . date('M j, Y g:i A', strtotime($last_sync)) . '</small>';
                }
                break;
        }
    }
    
    public function sortable_columns($columns) {
        $columns['event_date'] = 'event_date';
        $columns['event_location'] = 'event_location';
        $columns['event_capacity'] = 'event_capacity';
        $columns['event_price'] = 'event_price';
        
        return $columns;
    }
    
    public function custom_orderby($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }
        
        $orderby = $query->get('orderby');
        
        switch ($orderby) {
            case 'event_date':
                $query->set('meta_key', '_bcn_event_date');
                $query->set('orderby', 'meta_value');
                break;
            case 'event_location':
                $query->set('meta_key', '_bcn_event_location');
                $query->set('orderby', 'meta_value');
                break;
            case 'event_capacity':
                $query->set('meta_key', '_bcn_event_capacity');
                $query->set('orderby', 'meta_value_num');
                break;
            case 'event_price':
                $query->set('meta_key', '_bcn_event_price');
                $query->set('orderby', 'meta_value_num');
                break;
        }
    }
}

// Initialize the Events CPT
new BCN_Events_CPT();

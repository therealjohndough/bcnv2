<?php
/**
 * Internal Linking System
 * 
 * Manages circular link architecture between pages
 * Provides consistent navigation and cross-linking
 * 
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get internal link structure
 */
function bcn_get_internal_links() {
    return [
        'primary_pages' => [
            'home' => [
                'title' => 'Home',
                'url' => home_url('/'),
                'description' => 'Buffalo Cannabis Network - Building The Industry Together'
            ],
            'about' => [
                'title' => 'About BCN',
                'url' => home_url('/about/'),
                'description' => 'Learn about our mission, vision, and values'
            ],
            'membership' => [
                'title' => 'Membership',
                'url' => home_url('/membership/'),
                'description' => 'Join our network and access exclusive benefits'
            ],
            'events' => [
                'title' => 'Events',
                'url' => home_url('/events/'),
                'description' => 'Upcoming networking events and educational workshops'
            ],
            'news' => [
                'title' => 'News',
                'url' => home_url('/news/'),
                'description' => 'Latest industry news and BCN updates'
            ],
            'contact' => [
                'title' => 'Contact',
                'url' => home_url('/contact/'),
                'description' => 'Get in touch with our team'
            ]
        ],
        'secondary_pages' => [
            'member_directory' => [
                'title' => 'Member Directory',
                'url' => home_url('/members/directory/'),
                'description' => 'Connect with other BCN members'
            ],
            'resources' => [
                'title' => 'Resources',
                'url' => home_url('/resources/'),
                'description' => 'Industry resources and compliance guides'
            ],
            'advocacy' => [
                'title' => 'Advocacy',
                'url' => home_url('/advocacy/'),
                'description' => 'Policy advocacy and government relations'
            ],
            'community' => [
                'title' => 'Community',
                'url' => home_url('/community/'),
                'description' => 'Community engagement and social equity initiatives'
            ]
        ],
        'content_hubs' => [
            'cultivation' => [
                'title' => 'Cultivation',
                'url' => home_url('/cultivation/'),
                'description' => 'Resources and networking for cultivators'
            ],
            'processing' => [
                'title' => 'Processing',
                'url' => home_url('/processing/'),
                'description' => 'Information and connections for processors'
            ],
            'retail' => [
                'title' => 'Retail',
                'url' => home_url('/retail/'),
                'description' => 'Support and resources for retail operations'
            ],
            'ancillary' => [
                'title' => 'Ancillary Services',
                'url' => home_url('/ancillary/'),
                'description' => 'Professional services supporting the cannabis industry'
            ]
        ]
    ];
}

/**
 * Get contextual links for a specific page
 */
function bcn_get_contextual_links($current_page = '') {
    $links = bcn_get_internal_links();
    
    $contextual_links = [
        'home' => [
            'primary' => ['about', 'membership', 'events'],
            'secondary' => ['news', 'contact'],
            'cta' => ['membership', 'events']
        ],
        'about' => [
            'primary' => ['membership', 'events', 'advocacy'],
            'secondary' => ['home', 'contact', 'community'],
            'cta' => ['membership', 'contact']
        ],
        'membership' => [
            'primary' => ['events', 'member_directory', 'resources'],
            'secondary' => ['about', 'contact', 'community'],
            'cta' => ['events', 'contact']
        ],
        'events' => [
            'primary' => ['membership', 'news', 'member_directory'],
            'secondary' => ['home', 'about', 'resources'],
            'cta' => ['membership', 'news']
        ],
        'news' => [
            'primary' => ['events', 'advocacy', 'community'],
            'secondary' => ['home', 'about', 'resources'],
            'cta' => ['membership', 'events']
        ],
        'contact' => [
            'primary' => ['membership', 'events', 'about'],
            'secondary' => ['home', 'resources', 'community'],
            'cta' => ['membership', 'events']
        ]
    ];
    
    return isset($contextual_links[$current_page]) ? $contextual_links[$current_page] : $contextual_links['home'];
}

/**
 * Generate contextual link HTML
 */
function bcn_render_contextual_links($current_page = '', $type = 'primary', $style = 'default') {
    $links = bcn_get_internal_links();
    $contextual = bcn_get_contextual_links($current_page);
    
    if (!isset($contextual[$type])) {
        return '';
    }
    
    $link_items = [];
    foreach ($contextual[$type] as $link_key) {
        if (isset($links['primary_pages'][$link_key])) {
            $link_items[] = $links['primary_pages'][$link_key];
        } elseif (isset($links['secondary_pages'][$link_key])) {
            $link_items[] = $links['secondary_pages'][$link_key];
        } elseif (isset($links['content_hubs'][$link_key])) {
            $link_items[] = $links['content_hubs'][$link_key];
        }
    }
    
    if (empty($link_items)) {
        return '';
    }
    
    $output = '<div class="contextual-links contextual-links-' . esc_attr($type) . ' contextual-links-' . esc_attr($style) . '">';
    
    if ($style === 'cards') {
        $output .= '<div class="links-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">';
        foreach ($link_items as $link) {
            $output .= '<a href="' . esc_url($link['url']) . '" class="link-card" style="
                background: white;
                padding: 1.5rem;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                text-decoration: none;
                color: inherit;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                display: block;
            ">';
            $output .= '<h3 style="font-size: 1.1rem; margin-bottom: 0.5rem; color: var(--primary-color);">' . esc_html($link['title']) . '</h3>';
            $output .= '<p style="font-size: 0.9rem; color: var(--text-color, #666); margin: 0;">' . esc_html($link['description']) . '</p>';
            $output .= '</a>';
        }
        $output .= '</div>';
    } else {
        $output .= '<ul class="links-list" style="list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 1rem;">';
        foreach ($link_items as $link) {
            $output .= '<li><a href="' . esc_url($link['url']) . '" class="link-item" style="
                color: var(--primary-color);
                text-decoration: none;
                font-weight: 500;
                padding: 0.5rem 1rem;
                border: 1px solid var(--primary-color);
                border-radius: 4px;
                transition: all 0.2s ease;
            ">' . esc_html($link['title']) . '</a></li>';
        }
        $output .= '</ul>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Generate breadcrumb navigation
 */
function bcn_render_breadcrumbs($current_page = '') {
    $links = bcn_get_internal_links();
    
    $breadcrumbs = [
        ['title' => 'Home', 'url' => home_url('/')]
    ];
    
    if ($current_page && $current_page !== 'home') {
        if (isset($links['primary_pages'][$current_page])) {
            $breadcrumbs[] = $links['primary_pages'][$current_page];
        }
    }
    
    $output = '<nav class="breadcrumbs" style="
        padding: 1rem 0;
        font-size: 0.9rem;
        color: var(--text-color, #666);
    ">';
    $output .= '<ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 0.5rem;">';
    
    foreach ($breadcrumbs as $index => $crumb) {
        if ($index > 0) {
            $output .= '<li style="color: #ccc;">›</li>';
        }
        
        if ($index === count($breadcrumbs) - 1) {
            $output .= '<li style="color: var(--primary-color); font-weight: 500;">' . esc_html($crumb['title']) . '</li>';
        } else {
            $output .= '<li><a href="' . esc_url($crumb['url']) . '" style="color: inherit; text-decoration: none;">' . esc_html($crumb['title']) . '</a></li>';
        }
    }
    
    $output .= '</ol>';
    $output .= '</nav>';
    
    return $output;
}

/**
 * Generate related content section
 */
function bcn_render_related_content($current_page = '') {
    $contextual = bcn_get_contextual_links($current_page);
    
    if (empty($contextual['primary'])) {
        return '';
    }
    
    $output = '<section class="related-content" style="
        background: var(--light-bg, #f8f9fa);
        padding: 3rem 0;
        margin-top: 4rem;
    ">';
    $output .= '<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">';
    $output .= '<h2 style="
        font-size: 2rem;
        margin-bottom: 2rem;
        color: var(--primary-color);
        text-align: center;
    ">Explore More</h2>';
    $output .= bcn_render_contextual_links($current_page, 'primary', 'cards');
    $output .= '</div>';
    $output .= '</section>';
    
    return $output;
}

/**
 * Generate footer navigation
 */
function bcn_render_footer_navigation() {
    $links = bcn_get_internal_links();
    
    $output = '<div class="footer-nav" style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    ">';
    
    // Primary Pages
    $output .= '<div class="nav-section">';
    $output .= '<h3 style="color: white; margin-bottom: 1rem; font-size: 1.1rem;">Main Pages</h3>';
    $output .= '<ul style="list-style: none; padding: 0; margin: 0;">';
    foreach ($links['primary_pages'] as $link) {
        $output .= '<li style="margin-bottom: 0.5rem;"><a href="' . esc_url($link['url']) . '" style="color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.2s ease;">' . esc_html($link['title']) . '</a></li>';
    }
    $output .= '</ul>';
    $output .= '</div>';
    
    // Secondary Pages
    $output .= '<div class="nav-section">';
    $output .= '<h3 style="color: white; margin-bottom: 1rem; font-size: 1.1rem;">Resources</h3>';
    $output .= '<ul style="list-style: none; padding: 0; margin: 0;">';
    foreach ($links['secondary_pages'] as $link) {
        $output .= '<li style="margin-bottom: 0.5rem;"><a href="' . esc_url($link['url']) . '" style="color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.2s ease;">' . esc_html($link['title']) . '</a></li>';
    }
    $output .= '</ul>';
    $output .= '</div>';
    
    // Content Hubs
    $output .= '<div class="nav-section">';
    $output .= '<h3 style="color: white; margin-bottom: 1rem; font-size: 1.1rem;">Industry Focus</h3>';
    $output .= '<ul style="list-style: none; padding: 0; margin: 0;">';
    foreach ($links['content_hubs'] as $link) {
        $output .= '<li style="margin-bottom: 0.5rem;"><a href="' . esc_url($link['url']) . '" style="color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.2s ease;">' . esc_html($link['title']) . '</a></li>';
    }
    $output .= '</ul>';
    $output .= '</div>';
    
    $output .= '</div>';
    
    return $output;
}

// Add CSS for contextual links
add_action('wp_head', function() {
    echo '<style>
    .contextual-links .link-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15) !important;
    }
    
    .contextual-links .link-item:hover {
        background: var(--primary-color);
        color: white !important;
    }
    
    .footer-nav a:hover {
        color: white !important;
    }
    
    @media (max-width: 768px) {
        .contextual-links .links-grid {
            grid-template-columns: 1fr !important;
        }
        
        .contextual-links .links-list {
            flex-direction: column !important;
        }
        
        .footer-nav {
            grid-template-columns: 1fr !important;
            text-align: center;
        }
    }
    </style>';
});

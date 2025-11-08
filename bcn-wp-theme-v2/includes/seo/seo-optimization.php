<?php
/**
 * SEO Optimization for BCN Theme
 * 
 * @package BCN_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class BCN_SEO_Optimization {
    
    public function __construct() {
        add_action('wp_head', array($this, 'add_schema_markup'));
        add_action('wp_head', array($this, 'add_meta_tags'));
        add_action('wp_head', array($this, 'add_open_graph'));
        add_filter('document_title_parts', array($this, 'optimize_title_tags'));
    }
    
    public function add_schema_markup() {
        if (is_front_page()) {
            $this->add_organization_schema();
        }
    }
    
    private function add_organization_schema() {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Buffalo Cannabis Network',
            'alternateName' => 'BCN',
            'url' => home_url(),
            'description' => 'Buffalo Cannabis Network (BCN) is the premier Buffalo cannabis industry network, empowering brands, operators, and advocates through events, education, and resources.',
            'foundingDate' => '2022',
            'address' => array(
                '@type' => 'PostalAddress',
                'addressLocality' => 'Buffalo',
                'addressRegion' => 'NY',
                'addressCountry' => 'US'
            ),
            'contactPoint' => array(
                '@type' => 'ContactPoint',
                'telephone' => '+1-716-555-BCN1',
                'contactType' => 'customer service',
                'email' => 'info@buffalocannabisnetwork.com'
            )
        );
        
        echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
    }
    
    public function add_meta_tags() {
        if (is_front_page()) {
            echo '<meta name="description" content="Buffalo Cannabis Network (BCN) is WNY\'s hub for cannabis professionals — events, education, and resources to grow your cannabis business and community.">' . "\n";
            echo '<meta name="keywords" content="Buffalo cannabis, WNY cannabis, cannabis networking, cannabis events, cannabis education, cannabis business, New York cannabis, cannabis industry">' . "\n";
        }
    }
    
    public function add_open_graph() {
        if (is_front_page()) {
            echo '<meta property="og:title" content="Buffalo Cannabis Network | Events, Education & Industry Connections in WNY">' . "\n";
            echo '<meta property="og:description" content="Buffalo Cannabis Network (BCN) is WNY\'s hub for cannabis professionals — events, education, and resources to grow your cannabis business and community.">' . "\n";
            echo '<meta property="og:type" content="website">' . "\n";
            echo '<meta property="og:url" content="' . home_url() . '">' . "\n";
        }
    }
    
    public function optimize_title_tags($title) {
        if (is_front_page()) {
            $title['title'] = 'Buffalo Cannabis Network | Events, Education & Industry Connections in WNY';
        }
        return $title;
    }
}

// Initialize SEO optimization
new BCN_SEO_Optimization();

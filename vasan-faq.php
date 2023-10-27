<?php
/**
 * Plugin Name: Vasan FAQ's
 * Description: Frequently Asked Question's container with search filter in live mode
 * Author: Vasanthkumar S
 * Version: 1.0.0
 * Author URI: https://github.com/Vk2401
 *
 * Text Domain: vasan-faq
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class vasanFAQ
{
    public function __construct()
    {
        // Create custom post type
        add_action('init', array($this, 'createCustomPostType'));

        // Add assets (js, css, etc)
        add_action('wp_enqueue_scripts', array($this, 'loadAssets'));

        // Add shortcodes
        add_shortcode('vasanFAQ', array($this, 'loadShortcode'));

        // Register deactivation hook
        register_deactivation_hook(__FILE__, array($this, 'deactivatePlugin'));
  
    }

    public function createCustomPostType()
    {
        $args = array(
            'public' => true,
            'has_archive' => true,
            'supports' => array('title','editor'),
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post', 
            'labels' => array(
                'name' => 'vasanFAQ',
                'singular_name' => 'Vasan FAQ',
            ),
            'menu_icon' => 'dashicons-format-chat', 
        );

        register_post_type('vasanFAQ', $args);
    }

    public function loadAssets()
    {
        wp_enqueue_style(
            'vasanFAQ',
            plugin_dir_url(__FILE__) . 'css/vf.css',
            array(),
            1,
            'all'
        );

        wp_enqueue_script(
            'vasanFAQ',
            plugin_dir_url(__FILE__) . 'js/main.js',
            array(),
            1,
            'all'
        );
    }

    public function loadShortcode()
    {
        $args = array(
            'post_type' => 'vasanFAQ',
            'posts_per_page' => -1,
        );
    
        $faq_query = new WP_Query($args);
        $output = ''; // Store the output in a variable.
    
        if ($faq_query->have_posts()) {
            $output .= '<div class="vf-main-container">';
            $output .= '<h1 class="vf-title"> FAQ\'s</h1>';
            $output .= '<div>';
            $output .= '<input type="text" class="vf-text-input" id="vf-filter" placeholder="Search your question here!" />';
            $output .= '</div>';
            $output .= '<ul class="vfqanda">';
            while ($faq_query->have_posts()) {
                $faq_query->the_post();
                $output .= '<li>';
                $output .= '<strong class="vfquestion">' . get_the_title() . '</strong>';
                $output .= '<span class="vfanswer">' . get_the_content() . '</span>';
                $output .= '</li>';
            }
            $output .= '</ul>';
            $output .= '</div>';
        }
    
        return $output; // Return the generated HTML.
    
    }
    

    public function deactivatePlugin()
    {

        // Delete all posts of the custom post type
        $args = array(
            'post_type' => 'vasanFAQ',
            'posts_per_page' => -1,
            'post_status' => 'any', // Include posts with any status
        );
        $faq_query = new WP_Query($args);

        if ($faq_query->have_posts()) {
            while ($faq_query->have_posts()) {
                $faq_query->the_post();
                wp_delete_post(get_the_ID(), true); // True parameter forces deletion of media attachments
            }
        }
        
        // Unregister the custom post type
        unregister_post_type('vasanFAQ');
        
        // You can also remove other registered components, such as scripts and styles.
        wp_deregister_script('vasanFAQ');
        wp_deregister_style('vasanFAQ');

        // Flush rewrite rules to remove the custom post type's URL structure from the database.
        flush_rewrite_rules();
    }

}

new vasanFAQ();

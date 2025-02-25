<?php

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete all plugin options
delete_option('cec_options');

// Delete all custom post type posts and metadata
$events = get_posts(array(
    'post_type' => 'event',
    'numberposts' => -1,
    'post_status' => 'any'
));

foreach ($events as $event) {
    wp_delete_post($event->ID, true);
}

// Delete custom post type
unregister_post_type('event');

// Clean up database
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '_cec_%'");

// Remove capabilities
$role = get_role('administrator');
if ($role) {
    $role->remove_cap('edit_events');
    $role->remove_cap('publish_events');
    $role->remove_cap('delete_events');
}

// Clear any cached data
wp_cache_flush();
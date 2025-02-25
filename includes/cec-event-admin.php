<?php
class CEC_Event_Admin {
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_event_meta_box'));
        add_action('save_post', array($this, 'save_event_data'));
    }

    public function add_event_meta_box() {
        add_meta_box(
            'cec_event_details',
            'Event Details',
            array($this, 'event_meta_box_callback'),
            'event',
            'normal',
            'high'
        );
    }

    public function event_meta_box_callback($post) {
        // Add nonce for security
        wp_nonce_field('cec_save_event_data', 'cec_event_meta_box_nonce');

        // Get existing values
        $event_date = get_post_meta($post->ID, '_cec_event_date', true);
        $event_time = get_post_meta($post->ID, '_cec_event_time', true);
        $event_location = get_post_meta($post->ID, '_cec_event_location', true);

        // Output fields
        echo '<label for="cec_event_date">Event Date:</label>';
        echo '<input type="date" id="cec_event_date" name="cec_event_date" value="' . esc_attr($event_date) . '" required><br>';

        echo '<label for="cec_event_time">Event Time:</label>';
        echo '<input type="time" id="cec_event_time" name="cec_event_time" value="' . esc_attr($event_time) . '" required><br>';

        echo '<label for="cec_event_location">Event Location:</label>';
        echo '<input type="text" id="cec_event_location" name="cec_event_location" value="' . esc_attr($event_location) . '" required><br>';
    }

    public function save_event_data($post_id) {
        // Check nonce and permissions
        if (!isset($_POST['cec_event_meta_box_nonce']) || !wp_verify_nonce($_POST['cec_event_meta_box_nonce'], 'cec_save_event_data')) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save custom fields
        if (isset($_POST['cec_event_date'])) {
            update_post_meta($post_id, '_cec_event_date', sanitize_text_field($_POST['cec_event_date']));
        }
        if (isset($_POST['cec_event_time'])) {
            update_post_meta($post_id, '_cec_event_time', sanitize_text_field($_POST['cec_event_time']));
        }
        if (isset($_POST['cec_event_location'])) {
            update_post_meta($post_id, '_cec_event_location', sanitize_text_field($_POST['cec_event_location']));
        }

        // Clear the cache when an event is saved
        wp_cache_delete('cec_upcoming_events');
    }
}
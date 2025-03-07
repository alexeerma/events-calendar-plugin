<?php
class CEC_Event_Shortcode {
    public function __construct() {
        add_shortcode('custom_events', array($this, 'render_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    public function render_shortcode($atts) {
        // Check if cached data exists
        $cache_key = 'cec_upcoming_events';
        $cached_events = wp_cache_get($cache_key);

        if ($cached_events !== false) {
            return $cached_events;
        }



        // Query events
        $args = array(
            'post_type' => 'event',
            'posts_per_page' => 4,
            'meta_key' => '_cec_event_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => '_cec_event_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE',
                ),
            ),
        );

        $events = new WP_Query($args);

        if (!$events->have_posts()) {
            return '<p>No upcoming events.</p>';
        }

        // Start building the output
        $output = '<div class="cec-events-container">';
        $output .= '<h2 class="head-cec">NET Spordihalli sündmuste kalender</h2>';
        $output .= '<ul class="cec-events-list">';

        while ($events->have_posts()) {
            $events->the_post();
            $event_thumbnail = get_the_post_thumbnail(get_the_ID(), 'thumbnail', array('class' => 'event-thumbnail'));
            $event_date = get_post_meta(get_the_ID(), '_cec_event_date', true);
            $event_date_formatted = date("d.m.Y", strtotime($event_date));
            $event_time = get_post_meta(get_the_ID(), '_cec_event_time', true);
            $event_location = get_post_meta(get_the_ID(), '_cec_event_location', true);
            $event_url = get_post_meta(get_the_ID(), '_cec_event_url', true);

            $output .= '<li class="events-list">';
            $output .= '<div class="event-grid">';
            $output .= '<div class="event-thumbnail-wrapper">' . $event_thumbnail . '</div>';
            $output .= '<div class="event-content">';
            $output .= '<h3 class="event-head">' . esc_html(get_the_title()) . '</h3>';
            $output .= '<p class="event-date"><strong>Kuupäev:</strong> <span class="event-value">' . esc_html($event_date_formatted) . '</span> | <strong>Aeg:</strong> <span class="event-value">' . esc_html($event_time) . '</span></p>';
            $output .= '</div>';
            if (!empty($event_url)) {
                $output .= '<div class="event-button-wrapper">';
                $output .= '<a href="' . esc_url($event_url) . '" class="event-button" target="_blank">Registreeri</a>';
                $output .= '</div>';
            }
            $output .= '</div>';
            $output .= '</li>';
        }

        $output .= '</ul>';
        $output .= '</div>';

        wp_reset_postdata();

        // Cache the output for 1 hour
        wp_cache_set($cache_key, $output, '', 3600);

        return $output;
    }

    public function enqueue_styles() {
        // Enqueue Google Fonts
        wp_enqueue_style(
            'cec-google-fonts',
            'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap',
            array(), // No dependencies
            null // No versioning for Google Fonts
        );

        wp_enqueue_style(
            'cec-styles',
            CEC_PLUGIN_URL . 'public/styles.css',
            array(),
            CEC_VERSION,
            'all'
        );
    }
}
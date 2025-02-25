<?php
class CEC_Plugin {
    public function init() {
        // Load dependencies
        $this->load_dependencies();

        // Initialize components
        $event_post_type = new CEC_Event_Post_Type();
        $event_admin = new CEC_Event_Admin();
        $event_shortcode = new CEC_Event_Shortcode();
    }

    private function load_dependencies() {
        require_once CEC_PLUGIN_DIR . 'includes/cec-event-post-type.php';
        require_once CEC_PLUGIN_DIR . 'includes/cec-event-admin.php';
        require_once CEC_PLUGIN_DIR . 'includes/cec-event-shortcode.php';
    }
}
<?php
class CEC_Event_Post_Type {
    public function __construct() {
        add_action('init', array($this, 'register_event_post_type'));
        add_action('add_meta_boxes', array($this, 'add_event_url_meta_box'));
        add_action('save_post', array($this, 'save_event_url'));
    }

    public function register_event_post_type() {
        $labels = array(
            'name' => 'Üritused',
            'singular_name' => 'Üritus',
            'add_new' => 'Lisa uus Üritus',
            'add_new_item' => 'Lisa uus Üritus',
            'edit_item' => 'Muuda üritust',
            'new_item' => 'Uus üritus',
            'view_item' => 'Vaata üritust',
            'search_items' => 'Otsi üritust',
            'not_found' => 'Üritusi ei leitud',
            'not_found_in_trash' => 'Üritusi ei leitud prügikastist',
            'menu_name' => 'Üritused',
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'custom-fields' , 'thumbnail'),
            'menu_icon' => 'dashicons-calendar',
        );

        register_post_type('event', $args);
    }

    public function add_event_url_meta_box() {
        add_meta_box(
            'event_url_meta_box',
            'Event URL',
            array($this, 'display_event_url_meta_box'),
            'event',
            'normal',
            'high'
        );
    }

    public function display_event_url_meta_box($post) {
        $event_url = get_post_meta($post->ID, '_cec_event_url', true);
        wp_nonce_field('cec_event_url_nonce', 'cec_event_url_nonce');
        ?>
        <p>
            <label for="event_url">Event URL:</label>
            <input type="url" id="event_url" name="event_url" value="<?php echo esc_url($event_url); ?>" size="50">
        </p>
        <?php
    }

    public function save_event_url($post_id) {
        if (!isset($_POST['cec_event_url_nonce']) || !wp_verify_nonce($_POST['cec_event_url_nonce'], 'cec_event_url_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (isset($_POST['event_url'])) {
            update_post_meta($post_id, '_cec_event_url', esc_url_raw($_POST['event_url']));
        }
    }
}



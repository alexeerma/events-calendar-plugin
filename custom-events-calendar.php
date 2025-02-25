<?php
/*
Plugin Name: Custom Events Calendar
Plugin URI: https://github.com/alexeerma/events-calendar-plugin
Description: A lightweight plugin to manage and display events.
Version: 1.0
Author: Aleksander Eerma
Author URI: https://www.alexeerma.ee/
Github Plugin URI: alexeerma/events-calendar-plugin
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CEC_VERSION', '1.0');
define('CEC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CEC_PLUGIN_URL', plugin_dir_url(__FILE__));

// Autoload classes
spl_autoload_register(function ($class_name) {
    if (strpos($class_name, 'CEC_') === 0) {
        $file = CEC_PLUGIN_DIR . 'includes/' . strtolower(str_replace('_', '-', $class_name)) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// Initialize the plugin
if (class_exists('CEC_Plugin')) {
    $cec_plugin = new CEC_Plugin();
    $cec_plugin->init();
}
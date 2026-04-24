<?php
/**
 * Plugin Name: Eden Engine
 * Description: Eden Engine website sections, shortcodes, and public safe demo components.
 * Version: 0.1.2
 * Author: Jack Lawson
 * Text Domain: eden-engine
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'EDEN_ENGINE_VERSION' ) ) {
    define( 'EDEN_ENGINE_VERSION', '0.1.2' );
}

if ( ! defined( 'EDEN_ENGINE_PLUGIN_FILE' ) ) {
    define( 'EDEN_ENGINE_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'EDEN_ENGINE_ROOT_PATH' ) ) {
    define( 'EDEN_ENGINE_ROOT_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'EDEN_ENGINE_ROOT_URL' ) ) {
    define( 'EDEN_ENGINE_ROOT_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'EDEN_ENGINE_PLUGIN_PATH' ) ) {
    define( 'EDEN_ENGINE_PLUGIN_PATH', EDEN_ENGINE_ROOT_PATH . 'wordpress-plugin/' );
}

if ( ! defined( 'EDEN_ENGINE_PLUGIN_URL' ) ) {
    define( 'EDEN_ENGINE_PLUGIN_URL', EDEN_ENGINE_ROOT_URL . 'wordpress-plugin/' );
}

require_once EDEN_ENGINE_PLUGIN_PATH . 'includes/shortcodes.php';

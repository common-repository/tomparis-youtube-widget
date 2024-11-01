<?php
/*
Plugin Name: TomParisDE YouTube Widget
Description: Your TomParisDE YouTube Widget for Statistics, Banner and More!
Version: 1.0.2
Plugin URI: http://coder.tomparis.de/wordpress-youtube-plugin/
Author: Florian 'TomParisDE' Kirchner
Author URI: http://coder.tomparis.de/
Text Domain: tp-ytw
Domain Path: /languages
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
        exit;
}

if( !class_exists( 'TP_YTW' ) ) {

        /**
         * Main TP_YTW class
         *
         * @since       1.0.0
         */
        class TP_YTW {

                /**
                 * @var         TP_YTW $instance The one true TP_YTW
                 * @since       1.0.0
                 */
                private static $instance;


                /**
                 * Get active instance
                 *
                 * @access      public
                 * @since       1.0.0
                 * @return      object self::$instance The one true TP_YTW
                 */
                public static function instance() {
                        if( !self::$instance ) {
                                self::$instance = new TP_YTW();
                                self::$instance->setup_constants();
                                self::$instance->includes();
                                self::$instance->load_textdomain();
                        }

                        return self::$instance;
                }


                /**
                 * Setup plugin constants
                 *
                 * @access      private
                 * @since       1.0.0
                 * @return      void
                 */
                private function setup_constants() {

                        // Plugin version
                        define( 'TP_YTW_VER', '1.0.2' );

                        // Plugin path
                        define( 'TP_YTW_DIR', plugin_dir_path( __FILE__ ) );

                        // Plugin URL
                        define( 'TP_YTW_URL', plugin_dir_url( __FILE__ ) );

                        // Chache
                        define('TP_YTW_CACHE', 'tp_ytw_cache_');
                }


                /**
                 * Include necessary files
                 *
                 * @access      private
                 * @since       1.0.0
                 * @return      void
                 */
                private function includes() {

                        // Include files and scripts
                        require_once TP_YTW_DIR . 'includes/functions.php';
                        require_once TP_YTW_DIR . 'includes/widget.php';
                        require_once TP_YTW_DIR . 'includes/youtube.php';
                }

                /**
                 * Internationalization
                 *
                 * @access      public
                 * @since       1.0.0
                 * @return      void
                 */
                public function load_textdomain() {

                        // Load the default language files
                        load_plugin_textdomain( 'tp-ytw', false, 'tp-youtube-widget/languages' ); // TODO
                }

                /*
                 * Activation function fires when the plugin is activated.
                 *
                 * @since  1.0.0
                 * @access public
                 * @return void
                 */
                public static function activation() {
                        // nothing
                }

                /*
                 * Uninstall function fires when the plugin is being uninstalled.
                 *
                 * @since  1.0.0
                 * @access public
                 * @return void
                 */
                public static function uninstall() {
                        // nothing
                }
        }

        /**
         * The main function responsible for returning the one true TP_YTW
         * instance to functions everywhere
         *
         * @since       1.0.0
         * @return      \TP_YTW The one true TP_YTW
         */
        function TP_YTW_load() {
                return TP_YTW::instance();
        }

        /**
         * The activation & uninstall hooks are called outside of the singleton because WordPress doesn't
         * register the call from within the class hence, needs to be called outside and the
         * function also needs to be static.
         */
        register_activation_hook( __FILE__, array( 'TP_YTW', 'activation' ) );
        register_uninstall_hook( __FILE__, array( 'TP_YTW', 'uninstall') );
        add_action( 'plugins_loaded', 'TP_YTW_load' );

} // End if class_exists check


function tp_ytw_enqueue_scripts() {
        wp_enqueue_style( 'tp_ytw_style', TP_YTW_URL . '/public/css/style.css' );
}
add_action( 'wp_enqueue_scripts', 'tp_ytw_enqueue_scripts' );
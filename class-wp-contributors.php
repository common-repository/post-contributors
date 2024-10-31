<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.needinfotech.com/
 * @since             1.1.0
 * @package           wordpress_contributors
 *
 * @wordpress-plugin
 * Plugin Name:       WP Posts Contributors
 * Plugin URI:        https://www.needinfotech.com/
 * Description:       Posts Contributors.
 * Version:           1.1.0
 * Author:            NeedInfotech
 * Author URI:        https://www.needinfotech.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-contributors
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Wp_Contributors' ) ) :

	/**
	 * Main Wp_Contributors Class.
	 *
	 * @class Wp_Contributors
	 * @version 1.0.0
	 */
	class Wp_Contributors {

		/**
		 * The single instance of the class.
		 *
		 * @var Wp_Contributors
		 * @since 1.0.0
		 */
		protected static $instance = null;

		/**
		 * Main Wp_Contributors Instance.
		 *
		 * Ensures only one instance of Wp_Contributors is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @see instantiateb_Wp_Contributors()
		 * @return Wp_Contributors - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}


		/**
		 * Wp_Contributors Constructor.
		 *
		 * @since  1.0.0
		 */
		public function __construct() {
			$this->define_constants();
			$this->init_hooks();
			$this->includes();
		}

		/**
		 * Hook into actions and filters.
		 *
		 * @since  1.0.0
		 */
		private function init_hooks() {
			register_activation_hook( __FILE__, array( $this, 'wp_contributors_activate' ) );
		}

		/**
		 * Define Wp_Contributors Constants.
		 *
		 * @since  1.0.0
		 */
		private function define_constants() {

			$this->define( 'WP_CONTRIBUTORS_PLUGIN_FILE', __FILE__ );
			$this->define( 'WP_CONTRIBUTORS_PLUGIN_VERSION', '1.1.0' );
			$this->define( 'WP_CONTRIBUTORS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			$this->define( 'WP_CONTRIBUTORS_VERSION', WP_CONTRIBUTORS_PLUGIN_VERSION );
			$this->define( 'WP_CONTRIBUTORS_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
			$this->define( 'WP_CONTRIBUTORS_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param  string      $name Define constant name.
		 * @param  string|bool $value Define constant value.
		 * @since  1.0.0
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 *
		 * @since  1.0.0
		 */
		public function includes() {
			/* Enqueue mark epic functionalty file. */
			require_once 'includes/class-wp-contributors-admin-metaboxes.php';
			/* Enqueue send mail for most commented and liked activity. */
			require_once 'includes/class-wp-contributors-frontend-box.php';
		}

		/**
		 * Load default admin settings on plugin activation.
		 *
		 * @since  1.0.0
		 */
		public function wp_contributors_activate() {

		}

	}

endif;

/**
 * Main instance of Wp_Contributors.
 *
 * Returns the main instance of Wp_Contributors to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Wp_Contributors
 */
Wp_Contributors::instance();

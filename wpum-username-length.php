<?php
/*
Plugin Name: WPUM Username Length
Plugin URI:  https://wpusermanager.com
Description: Addon for WP User Manager, Set a minimum and maximum length for usernames upon registration.
Version:     2.0.4
Author:      WP User Manager
Author URI:  https://wpusermanager.com/
License:     GPLv3+
Text Domain: wpum-username-length
Domain Path: /languages
*/

/**
 * WPUM Username Length.
 *
 * Copyright (c) 2018 Alessandro Tesoro
 *
 * WPUM Username Length. is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WPUM Username Length. is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * @author     Alessandro Tesoro
 * @version    2.0.0
 * @copyright  (c) 2018 Alessandro Tesoro
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GNU LESSER GENERAL PUBLIC LICENSE
 * @package    wpum-username-length
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPUM_Username_Length' ) ) :

	/**
	 * Main WPUM_Username_Length class.
	 */
	final class WPUM_Username_Length {

		/**
		 * WPUMUL Instance.
		 *
		 * @var WPUM_Username_Length() the WPUM Instance
		 */
		protected static $_instance;

		/**
		 * Main WPUMUL Instance.
		 *
		 * Ensures that only one instance of WPUMUL exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @return WPUM_Username_Length
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
				self::$_instance->run();
			}

			return self::$_instance;
		}

		/**
		 * Only load the addon on the WPUM core hook, ensuring the plugin is active.
		 */
		public function run() {
			add_action( 'after_wpum_init', array( $this, 'init' ) );
		}

		/**
		 * Get things up and running.
		 */
		public function init() {
			if ( ! $this->autoload() ) {
				return;
			}

			// Verify the plugin meets WP and PHP requirements.
			$this->plugin_can_run();

			// Verify the addon can run first. If not, disable the addon automagically.
			$this->addon_can_run();

			// Plugin is activated now proceed.
			$this->setup_constants();
			$this->includes();
			$this->init_hooks();
		}

		/**
		 * Autoload composer and other required classes.
		 *
		 * @return bool
		 */
		protected function autoload() {
			if ( ! file_exists( __DIR__ . '/vendor' ) || ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
				add_action( 'admin_notices', array( $this, 'vendor_failed_notice' ) );

				return false;
			}

			return require __DIR__ . '/vendor/autoload.php';
		}

		/**
		 * Show the Vendor build issue notice.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function vendor_failed_notice() { ?>
			<div class="error">
				<p><?php printf( '<strong>WP User Manager</strong> &mdash; The %s addon plugin cannot be activated as it is missing the vendor directory.', esc_html( 'Username Length' ) ); ?></p>
			</div>
			<?php
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}

		/**
		 * Load required files for the addon.
		 *
		 * @return void
		 */
		public function includes() {
			require_once WPUMUL_PLUGIN_DIR . 'includes/settings.php';
			require_once WPUMUL_PLUGIN_DIR . 'includes/actions.php';
		}

		/**
		 * Setup plugin constants
		 *
		 * @access private
		 * @since 1.0.0
		 * @return void
		 */
		private function setup_constants() {

			// Plugin version.
			if ( ! defined( 'WPUMUL_VERSION' ) ) {
				define( 'WPUMUL_VERSION', '2.0.4' );
			}

			// Plugin Folder Path.
			if ( ! defined( 'WPUMUL_PLUGIN_DIR' ) ) {
				define( 'WPUMUL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL.
			if ( ! defined( 'WPUMUL_PLUGIN_URL' ) ) {
				define( 'WPUMUL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File.
			if ( ! defined( 'WPUMUL_PLUGIN_FILE' ) ) {
				define( 'WPUMUL_PLUGIN_FILE', __FILE__ );
			}

			// Plugin Slug.
			if ( ! defined( 'WPUMUL_SLUG' ) ) {
				define( 'WPUMUL_SLUG', plugin_basename( __FILE__ ) );
			}

		}

		/**
		 * Hook in our actions and filters.
		 *
		 * @return void
		 */
		private function init_hooks() {
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ), 0 );
		}

		/**
		 * Load plugin textdomain.
		 *
		 * @return void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'wpum-username-length', false, basename( dirname( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Verify the plugin meets the WP and php requirements.
		 *
		 * @return boolean
		 */
		public function plugin_can_run() {
			$requirements_check = new WP_Requirements_Check( array(
				'title' => 'WPUM Username Length',
				'php'   => '5.5',
				'wp'    => '4.9.5',
				'file'  => __FILE__,
			) );

			return $requirements_check->passes();
		}

		/**
		 * Verify that the current environment is supported.
		 *
		 * @return boolean
		 */
		private function addon_can_run() {
			$requirements_check = new WPUM_Extension_Activation(
				array(
					'title'        => 'WPUM Username Length',
					'wpum_version' => '2.2',
					'file'         => __FILE__,
				)
			);

			return $requirements_check->passes();
		}

	}

endif;

/**
 * Start the addon.
 *
 * @return object
 */
function WPUMUL() {
	return WPUM_Username_Length::instance();
}

WPUMUL();

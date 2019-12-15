<?php
/**
 * Register new settings for the addon.
 *
 * @package     wpum-username-length
 * @copyright   Copyright (c) 2018, Alessandro Tesoro
 * @license     https://opensource.org/licenses/gpl-license GNU Public License
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register new settings for the addon.
 *
 * @param array $settings
 *
 * @return array
 */
function wpumul_register_settings( $settings ) {

	$settings['registration'][] = array(
		'id'   => 'username_min_length',
		'name' => esc_html__( 'Minimum Length', 'wpum-username-length' ),
		'desc' => esc_html__( 'Set a minimum characters length for the username - leave blank if not needed.', 'wpum-username-length' ),
		'type' => 'text',
	);

	$settings['registration'][] = array(
		'id'   => 'username_max_length',
		'name' => esc_html__( 'Maximum Length', 'wpum-username-length' ),
		'desc' => esc_html__( 'Set a maximum characters length for the username.', 'wpum-username-length' ),
		'type' => 'text',
	);

	return $settings;
}

add_action( 'wpum_registered_settings', 'wpumul_register_settings' );

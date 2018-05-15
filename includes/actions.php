<?php
/**
 * Hook into the registration process and add new validation rules.
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
 * Hook into the registration process and validate the length of the submitted username.
 *
 * @param bool $pass
 * @param array $fields
 * @param array $values
 * @param string $form
 * @return void
 */
function wpumul_verify_username_length( $pass, $fields, $values, $form ) {

	if ( 'registration' == $form && isset( $values['register']['username'] ) ) {

		$username   = $values['register']['username'];
		$min_length = wpum_get_option( 'username_min_length' );
		$max_length = wpum_get_option( 'username_max_length' );
		$length     = strlen( $username );

		if ( ! empty( $min_length ) && $min_length !== '0' ) {
			if ( $length < $min_length ) {
				return new WP_Error( 'username-short', sprintf( esc_html__( 'The username is too short, it must have at least %s characters.', 'wpum-username-length' ), $min_length ) );
			}
		}

		// Check max length.
		if ( ! empty( $max_length ) && $max_length !== '0' ) {
			if ( $length > $max_length ) {
				return new WP_Error( 'username-long', sprintf( esc_html__( 'The username is too long. It cannot exceed %s characters.', 'wpum-username-length' ), $max_length ) );
			}
		}
	}

	return $pass;

}
add_filter( 'submit_wpum_form_validate_fields', 'wpumul_verify_username_length', 10, 4 );

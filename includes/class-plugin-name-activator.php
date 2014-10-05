<?php

/**
 * Fired during plugin activation
 *
 * @link       http://jamesdigioia.com/url-embedlifier/
 * @since      1.0.0
 *
 * @package    URL_Embedlifier
 * @subpackage URL_Embedlifier/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    URL_Embedlifier
 * @subpackage URL_Embedlifier/includes
 * @author     Your Name <email@example.com>
 */
class URL_Embedlifier_Activator {

	/**
	 * Version check.
	 *
	 * Checks to make sure we're running at least version 5.3 of PHP,
	 * which is required for the Embedly library.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if ( ! version_compare( PHP_VERSION, '5.3', '<' ) ) {
			return;
		}

		deactivate_plugins( 'url-embedlifier' );
		wp_die('<p>The <strong>URL Embedlifier</strong> plugin requires PHP version 5.3 or greater.</p>',
			'Plugin Activation Error',  array( 'response' => 200, 'back_link' => true ) );
	}

}

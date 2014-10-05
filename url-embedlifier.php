<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           URL_Embedlifier
 *
 * @wordpress-plugin
 * Plugin Name:       URL Embedlifier
 * Plugin URI:        http://jamesdigioia.com/url-embedlifier/
 * Description:       Saves the URL's Embedly metadata to the database for display
 * Version:           1.0.0
 * Author:            James DiGioia
 * Author URI:        http://jamesdigioia.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       url-embedlifier
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-url-embedlifier-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-url-embedlifier-deactivator.php';

/** This action is documented in includes/class-url-embedlifier-activator.php */
register_activation_hook( __FILE__, array( 'URL_Embedlifier_Activator', 'activate' ) );

/** This action is documented in includes/class-url-embedlifier-deactivator.php */
register_deactivation_hook( __FILE__, array( 'URL_Embedlifier_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-url-embedlifier.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
$url_embedlifier = new URL_Embedlifier();
$url_embedlifier->run();

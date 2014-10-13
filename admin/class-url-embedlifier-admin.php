<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://jamesdigioia.com/url-embedlifier/
 * @since      1.0.0
 *
 * @package    URL_Embedlifier
 * @subpackage URL_Embedlifier/includes
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    URL_Embedlifier
 * @subpackage URL_Embedlifier/admin
 * @author     James DiGioia <jamesorodig@gmail.com>
 */
class URL_Embedlifier_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Render the URL metabox after the title.
	 *
	 * @param  WP_Post $post Current post object
	 */
	public function display_url_box( $post ) {
		if( $post->post_type === 'post' ) {
			$embedlified_url = esc_url( get_post_meta( $post->ID, 'embedlified_url', true ) ); ?>
			<table>
				<tr>
					<td>
						<label for="embedlified_url" style="display: none;">_e( "Embedlify URL" )</label>
						<input type="text" name="embedlified_url" size="80" value="<?php echo $embedlified_url; ?>" style="padding: 5px 8px; font-size: 1.7em; line-height: 100%; height: 1.7em; width: 100%; outline: none; margin: 0; background-color: #fff;" placeholder="Embedlify URL" />
					</td>
				</tr>
			</table>
			<?php
		}
	}

	/**
	 * Saves the URL passed from the metabox to the post meta
	 *
	 * @param  int $post_id ID of the currently saving post
	 */
	public function save_url( $post_id ) {
		if (
			array_key_exists( 'embedlified_url', $_POST ) && $_POST['embedlified_url'] !== '' &&
			( $_POST['embedlified_url'] !== get_post_meta( $post_id, 'embedlified_url', true )
				|| 'yes' === get_post_meta( $post_id, 'embedlify_key_missing', true ) )
		) {
			$url = esc_url_raw( $_POST['embedlified_url'] );

			if ( $url !== get_post_meta( $post_id, 'embedlified_url', true ) ) {
				$result = update_post_meta( $post_id, 'embedlified_url', $url );
				if ( ! $result ) {
					// @todo display error message
					return;
				}
			}

			if ( cmb_get_option( $this->plugin_name, 'urle_embedly_key' ) ) {
				$this->get_embedly_metadata( $post_id, $url );
				$result = delete_post_meta( $post_id, 'embedlify_key_missing' );

			} else {
				update_post_meta( $post_id, 'embedlify_key_missing', 'yes' );
			}
		}
	}

	/**
	 * Gets the Embedly metadata for a given url
	 * and saves it to the metadata of the post_id
	 *
	 * @param  int    $post_id ID of the post to save metadata to
	 * @param  string $url     URL to get Embedly data for
	 */
	public function get_embedly_metadata( $post_id, $url ) {
		$api = new Embedly\Embedly( array(
			'key' => cmb_get_option($this->plugin_name, 'urle_embedly_key')
		) );

		$response = $api->oembed( $url );
		// @todo check if error object

		// Turns the response object into an array
		$oembed = get_object_vars( $response );

		foreach ( $oembed as $key => $value ) {
			if ( $key === 'thumbnail_url' ) {
				// Is there a description we can pass in here?
				$result = $this->sideload_image( $value, $post_id );
				if ( is_wp_error( $result ) ) {
					// @todo error checking
				}
			}

			update_post_meta( $post_id, 'embedlified_' . $key, $value );
		}
	}

	/**
	 * Downloads and sets an image to the post_id's featured image
	 * @param  string $file    URL of the image to download
	 * @param  int    $post_id ID of the post to save thumbnail to
	 * @param  string $desc    Description of the image
	 * @return WP_Error|true   WP_Error on failure, true of success
	 */
	private function sideload_image( $file, $post_id, $desc = null ) {
		if ( ! empty($file) ) {
			// Download file to temp location
			$tmp = download_url( $file );

			// Set variables for storage
			// fix file filename for query strings
			preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
			$file_array['name'] = basename($matches[0]);
			$file_array['tmp_name'] = $tmp;

			// If error storing temporarily, unlink
			if ( is_wp_error( $tmp ) ) {
				@unlink($file_array['tmp_name']);
				$file_array['tmp_name'] = '';
			}

			// do the validation and storage stuff
			$id = media_handle_sideload( $file_array, $post_id, $desc );
			// If error storing permanently, unlink
			if ( is_wp_error($id) ) {
				@unlink($file_array['tmp_name']);
				return $id;
			}

			update_post_meta( $post_id, '_thumbnail_id', $id );

			return true;
		}
	}

	/**
	 * Displays the admin error if we don't have an API key
	 *
	 * @return string HTML for the error notice
	 * @since    0.1.0
	 */
	public function display_api_key_error() {
		global $post;

		if ( 'yes' === get_post_meta( $post->ID, 'embedlify_key_missing', true ) ) { ?>
			<div class="error"><p>
					You haven't added your Embedly API key. Please fill that in <a href="<?php echo admin_url( 'options-general.php?page=' . $this->plugin_name ); ?>">here</a>.
			</p></div><?php
		}
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    0.1.0
	 */
	public function add_plugin_admin_menu() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'URL Embedlifier Settings', $this->plugin_name ),
			__( 'URL Embedlifier', $this->plugin_name ),
			'edit_posts',
			$this->plugin_name,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    0.1.0
	 */
	public function display_plugin_admin_page() {

		include_once( plugin_dir_path( __FILE__ ) . 'partials/settings-page.php' );

	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    0.1.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>'
			),
			$links
		);

	}

}

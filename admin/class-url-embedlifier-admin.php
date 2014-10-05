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
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

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
	 * @var      string    $name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name = $name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in URL_Embedlifier_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The URL_Embedlifier_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in URL_Embedlifier_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The URL_Embedlifier_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Render the URL metabox after the title.
	 *
	 * @param  WP_Post $post Current post object
	 */
	public function display_url_box( $post ) {
		$url = esc_url( get_post_meta( $post->ID, 'embedlified_url', true ) ); ?>
		<table>
			<tr>
				<td>
					<label for="url" style="display: none;">Embedlify URL</label>
					<input type="text" name="url" size="80" value="<?php echo $url; ?>" style="padding: 5px 8px; font-size: 1.7em; line-height: 100%; height: 1.7em; width: 100%; outline: none; margin: 0; background-color: #fff;" placeholder="Embedlify URL" />
				</td>
			</tr>
		</table>
		<?php
	}

}
